<?php

use App\Foundation\Tire;
use App\Models\Admin\Config as SiteConfig;
use App\Models\Admin\SensitiveWord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

use App\Repository\APIHelper;
use Illuminate\Support\Facades\DB;
use App\Models\PreGeneratedCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


/**
 * 生成随机code
 *
 * @param  int  $length
 * @return string
 */
function createCode($length = 12)
{
    $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max = strlen($string) - 1;
    $token = '';
    for ($i = 0; $i < $length; $i++) {
        $token .= $string[mt_rand(0, $max)];
    }

    return $token;
}

/**
 * @Title: getApiByBatch
 * @Description: 批量获取授权码（code码）
 * @param $data
 * @return array
 * @Author: 李军伟
 */
function getApiByBatch($data)
{
    // Common validation
    $user = Auth::guard('admin')->user();
    if (!isset($data['number']) || !is_numeric($data['number']) || $data['number'] <= 0) {
        throw new \InvalidArgumentException('Invalid number of codes: ' . ($data['number'] ?? 'null'));
    }
    if (!isset($data['day']) || !is_numeric($data['day']) || $data['day'] <= 0) {
        throw new \InvalidArgumentException('Invalid valid_day: ' . ($data['day'] ?? 'null'));
    }

    $requested = (int) $data['number'];
    $typeLabel = ($data['day'] ?? 'N/A') . 'days';
    $vendor = 'metvbox';
    $results = [];
    $preGeneratedEnabled = (bool) config('app.pre_generated_codes_enabled');

    // Helper to format return structure
    $format = function (array $codes, string $source) use ($typeLabel, $vendor) {
        $out = [];
        foreach ($codes as $code) {
            $out[] = [
                'code' => $code,
                'type' => $typeLabel,
                'vendor' => $vendor,
                'source' => $source,
            ];
        }
        return $out;
    };

    // Helper to fetch from PreGeneratedCode atomically
    $fetchFromPreGenerated = function (int $take) use ($user, $typeLabel) {
        $codes = [];
        if ($take <= 0) return $codes;
        DB::transaction(function () use ($take, $user, &$codes, $typeLabel) {
            $selected = PreGeneratedCode::whereNull('requested_by')
                ->where('type', $typeLabel)
                ->where('vendor', 'metvbox')
                ->lockForUpdate()
                ->take($take)
                ->get();
            if ($selected->isNotEmpty()) {
                $ids = $selected->pluck('id');
                PreGeneratedCode::whereIn('id', $ids)->update([
                    'requested_by' => $user->id,
                    'requested_at' => now(),
                ]);
                $codes = $selected->pluck('code')->toArray();
            }
        });
        return $codes;
    };

    // Helper to fetch from API with up to 3 retries
    $fetchFromApi = function (int $take) use ($user, $data) {
        $attempts = 0;
        while ($attempts < 3) {
            $attempts++;

            // Use MetVBox API to generate codes
            $metvboxService = app(\App\Services\MetVBoxService::class);
            $response = $metvboxService->generateCode(
                validDays: (int) $data['day'],
                deviceType: 'all',
                quantity: (int) $take
            );

            if (!$response) {
                Log::warning('MetVBox API returned null', ['attempt' => $attempts]);
                continue;
            }

            // Extract codes from response
            // MetVBox normalizes response to have 'data' key with codes array
            $codes = [];

            if (isset($response['data']) && is_array($response['data'])) {
                // Codes are directly in the data array
                foreach ($response['data'] as $code) {
                    if (is_string($code)) {
                        $codes[] = $code;
                    }
                }
            }

            if (empty($codes)) {
                Log::error('MetVBox API: No codes found in response', [
                    'response' => $response,
                    'attempt' => $attempts
                ]);
                continue;
            }

            // Log success with metadata if available
            if (isset($response['metadata'])) {
                Log::info('MetVBox codes generated successfully', [
                    'quantity' => count($codes),
                    'points_deducted' => $response['metadata']['points_deducted'] ?? null,
                    'balance_after' => $response['metadata']['balance_after'] ?? null,
                ]);
            }

            // Return codes
            if (count($codes) >= $take) {
                return array_slice($codes, 0, $take);
            }

            // If partial, return what we have
            if (!empty($codes)) {
                return $codes;
            }
        }

        Log::warning('MetVBox API retries exhausted in getApiByBatch');
        return [];
    };

    // Obsolete - Helper to generate fallback local codes, only for local testing
    // $generateLocal = function (int $take) {
    //     $codes = [];
    //     for ($i = 0; $i < $take; $i++) {
    //         $code = createCode(12);
    //         while (\App\Models\AuthCode::where('auth_code', $code)->exists()) {
    //             $code = createCode(12);
    //         }
    //         $codes[] = $code;
    //     }
    //     return $codes;
    // };

    // Strategy
    if ($preGeneratedEnabled) {
        // PreGenerated first; if insufficient, notify and then try API to fill remainder
        $fromPre = $fetchFromPreGenerated($requested);
        $results = array_merge($results, $format($fromPre, 'PreGeneratedCode'));
        $remaining = $requested - count($fromPre);
        if ($remaining > 0) {
            Log::warning('PreGeneratedCode insufficient with toggle enabled', [
                'type' => $typeLabel,
                'vendor' => $vendor,
                'requested' => $requested,
                'available' => count($fromPre),
                'remaining' => $remaining,
            ]);
            // Notify admin
            try {
                $adminEmail = env('ADMIN_EMAIL', env('MAIL_USERNAME'));
                if ($adminEmail) {
                    $subject = 'PreGenerated Codes Low/Insufficient';
                    $content = 'Requested ' . $requested . ' codes, but only ' . count($fromPre) . ' available in PreGenerated pool. ' .
                        'Type: ' . $typeLabel . ', Vendor: ' . $vendor . '. ' .
                        'Requested By: ' . Auth::guard('admin')->user()->email . ' ' .
                        'PreGeneratedCodeToggle is enabled;';
                    send_email($adminEmail, $subject, $content);
                }
            } catch (\Throwable $e) {
                Log::error('Failed to send PreGeneratedCode depletion email', ['error' => $e->getMessage()]);
            }
            // Do NOT call API or generate locally when toggle enabled – strictly return only pre-generated codes
            // Keep $results as-is to reflect available quantity only
        }
        return $results;
    }

    // Toggle disabled: API first, then fallback to PreGenerated, then local
    $fromApi = $fetchFromApi($requested);
    if (count($fromApi) >= $requested) {
        return $format($fromApi, 'API');
    }
    $results = $format($fromApi, 'API');
    $remaining = $requested - count($fromApi);
    $fromPre = $fetchFromPreGenerated($remaining);
    if (count($fromPre) < $remaining) {
        Log::warning('PreGeneratedCode insufficient during API fallback', [
            'requested' => $remaining,
            'available' => count($fromPre),
        ]);
        // Notify admin
        try {
            $adminEmail = env('ADMIN_EMAIL', env('MAIL_USERNAME'));
            if ($adminEmail) {
                $subject = 'PreGenerated Codes Low/Insufficient';
                $content = 'API unavailable. Requested ' . $requested . ' codes, only ' . count($fromPre) . ' available in PreGenerated pool. Remaining will be locally generated.';
                send_email($adminEmail, $subject, $content);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send PreGeneratedCode depletion email', ['error' => $e->getMessage()]);
        }
        //$fromLocal = $generateLocal($remaining - count($fromPre));
        //return array_merge($results, $format($fromPre, 'PreGeneratedCode'), $format($fromLocal, 'FallbackLocal'));
    }

    return array_merge($results, $format($fromPre, 'PreGeneratedCode'));
}

/**
 * 直接从数据库获取系统后台配置
 *
 * @param  string  $key  key
 * @param  mixed  $default  key不存在时的默认值
 * @return mixed key对应的value
 */
function getConfig($key, $default = null)
{
    $v = SiteConfig::where('key', $key)->value('value');

    return ! is_null($v) ? $v : $default;
}

function getLang($local)
{
    if ($local == 'en') {
        $name = 'top menu';
    } elseif ($local == 'my') {
        $name = 'top menu';
    } else {
        $name = '顶级菜单';
    }

    return $name;
}

/**
 * @Title: getRandChar
 *
 * @Description: 生成随机字符串函数
 *
 * @return null|string
 *
 * @Author: 李军伟
 *
 * @date: 2018/7/18 12:10
 */
function getRandChar($length)
{
    $str = null;
    $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $max = strlen($strPol) - 1;

    for ($i = 0;
        $i < $length;
        $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}

function parseEntityFieldParams($params)
{
    if (strpos($params, 'getFormItemsFrom') === 0 && function_exists($params)) {
        $params = call_user_func($params);
    }

    $items = explode("\n", $params);

    return array_map(function ($item) {
        return explode('=', $item);
    }, $items);
}

function isChecked($value, $options)
{
    return in_array($value, explode(',', $options), true);
}

function xssFilter(Model $data)
{
    $attributes = $data->getAttributes();
    foreach ($attributes as &$v) {
        if (is_string($v)) {
            $v = htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8');
        }
    }
    $data->setRawAttributes($attributes);
}

function initTire()
{
    return Cache::rememberForever('sensitive_words_tire', function () {
        $tires = [];

        foreach (['noun', 'verb', 'exclusive'] as $v) {
            $words = SensitiveWord::query()->select($v)->where($v, '<>', '')->get();

            $tire = new Tire;
            foreach ($words as $k) {
                $tire->add($k->$v);
            }
            $tires[$v] = $tire;
        }

        return $tires;
    });
}

function initTireSingle()
{
    return Cache::rememberForever('sensitive_words_tire_single', function () {
        $types = SensitiveWord::query()->select('type')->groupBy('type')->get();
        $tire = new Tire;
        foreach ($types as $type) {
            $words = SensitiveWord::query()->where('type', $type->type)->get();
            $nouns = [];
            $verbs = [];
            $exclusives = [];
            foreach ($words as $word) {
                if ($word->noun !== '') {
                    $nouns[] = $word->noun;
                } elseif ($word->verb !== '') {
                    $verbs[] = $word->verb;
                } elseif ($word->exclusive !== '') {
                    $exclusives[] = $word->exclusive;
                }
            }

            foreach ($exclusives as $k) {
                $tire->add($k);
            }
            foreach ($verbs as $vk) {
                foreach ($nouns as $nk) {
                    $tire->add($vk.$nk);
                }
            }
        }

        return $tire;
    });
}

function mapTypeToVerbOfSensitiveWords()
{
    return Cache::rememberForever('sensitive_verb_words', function () {
        $words = SensitiveWord::query()->select('verb', 'type')->where('verb', '<>', '')->get();

        $data = [];
        foreach ($words as $word) {
            $data[$word->type != '' ? $word->type : 'others'][] = $word->verb;
        }

        return $data;
    });
}

/**
 * 敏感词检查
 *
 * @param  string  $text  待检查文本
 * @param  string  $type  名词、动词的检测方法。默认为 join 。join：名词和动词相连组合在一起视为违规 all：名词和动词只要同时出现即为违规
 * @param  mixed  $mode  检查模式。仅 $type 为 all 时有效。默认名词、动词、专用词都检查，显示可指定为 noun verb exclusive
 * @return array
 */
function checkSensitiveWords(string $text, $type = 'join', $mode = null)
{
    if (! is_null($mode) && ! in_array($mode, ['noun', 'verb', 'exclusive'])) {
        throw new \InvalidArgumentException('mode参数无效，只能为null值、noun、exclusive');
    }

    if ($type === 'join') {
        $tire = initTireSingle();
        $result = $tire->seek($text);

        return $result;
    }

    $tires = initTire();
    if (! is_null($mode)) {
        return $tires[$mode]->seek($text);
    }

    $result = [];
    $return = [];
    foreach ($tires as $k => $tire) {
        $result[$k] = $tire->seek($text);
    }
    if (! empty($result['noun']) && ! empty($result['verb'])) {
        $data = mapTypeToVerbOfSensitiveWords();
        foreach ($result['noun'] as $noun) {
            $type = Cache::rememberForever('sensitive_words_noun_type:'.$noun, function () use ($noun) {
                return SensitiveWord::query()->where('noun', $noun)->value('type');
            });
            $type = $type ? $type : 'others';
            $verbs = array_intersect($data[$type], $result['verb']);
            if (! empty($verbs)) {
                array_push($verbs, $noun);
                $return[] = implode(' ', $verbs);
            }
        }
    }

    return array_merge($return, $result['exclusive']);
}

/**
 * @Title: order_no
 *
 * @Description: 订单号的生成规则
 *
 * @return string
 *
 * @Author: 李军伟
 */
function order_no()
{
    $yCode = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
    $orderSn = $yCode[intval(date('Y')) - date('Y', time())].
        strtoupper(dechex(date('m'))).
        date('d').substr(time(), -5).
        substr(microtime(), 2, 5).
        sprintf('%02d', rand(0, 99));

    return $orderSn;
}

/**
 * @Title: get_extension
 *
 * @Description: 获取文件后缀
 *
 * @return bool|string
 *
 * @Author: 李军伟
 */
function get_extension($file)
{
    return substr(strrchr($file, '.'), 1);
}

/**
 * @Title: send_email
 *
 * @Description: 邮件发送
 *
 * @param  $to  接收人
 * @param  string  $subject  邮件标题
 * @param  string  $content  邮件内容(html模板渲染后的内容)
 * @return array
 *
 * @Author: 李军伟
 */
function send_email($to, $subject = '', $content = '')
{
    try {
        $recipients = is_array($to) ? array_values($to) : [$to];
        Mail::html($content, function ($message) use ($recipients, $subject) {
            $message->subject($subject);
            foreach ($recipients as $addr) {
                if ($addr) {
                    $message->to($addr);
                }
            }
            // 设置回复地址，默认使用应用的发件地址与名称
            $fromAddress = config('mail.from.address');
            if (!empty($fromAddress)) {
                $message->replyTo($fromAddress, config('mail.from.name', 'wow-tv'));
            }
        });
        return ['status' => 1, 'msg' => '邮件发送成功'];
    } catch (\Throwable $e) {
        Log::error('send_email failed', [
            'error' => $e->getMessage(),
        ]);
        return ['status' => -1, 'msg' => '发送失败: ' . $e->getMessage()];
    }
}

/**
 * @Title: excelExport
 *
 * @Description: excel表格导出
 *
 * @param  string  $fileName  文件名称
 * @param  array  $headArr  表头名称
 * @param  array  $data  要导出的数据
 *
 * @Author: 李军伟
 */
function excelExport($fileName = '', $headArr = [], $data = [])
{
    $fileName .= '_'.date('Y_m_d', time()).'.xls';
    $objExcel = new \PHPExcel;
    $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel5');
    $objExcel->getProperties();
    $key = ord('A');  // 设置表头

    foreach ($headArr as $v) {
        $colum = chr($key);
        $objExcel->setActiveSheetIndex(0)->setCellValue($colum.'1', $v);
        $objExcel->setActiveSheetIndex(0)->setCellValue($colum.'1', $v);
        $key += 1;
    }

    $column = 2;
    $objActSheet = $objExcel->getActiveSheet();
    foreach ($data as $key => $rows) {
        $span = ord('A');
        foreach ($rows as $keyName => $value) {
            $objActSheet->setCellValue(chr($span).$column, $value);
            $span++;
        }
        $column++;
    }

    $fileName = iconv('utf-8', 'gb2312', $fileName); // 重命名表
    $objExcel->setActiveSheetIndex(0);  // 设置活动单指数到第一个表，所以Excel打开这是第一个表
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename='$fileName'");
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output'); // 文件通过浏览器下载
}
// old function
// function httpUrl($url, $params = false, $ispost = 0, $header = array(), $filename, $verify = false)

function httpUrl($url, $params = false, $ispost = 0, $header = [], $filename = null, $verify = false)
{
    $httpInfo = [];
    $ch = curl_init();
    if (! empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // 忽略ssl证书
    if ($verify === true) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    } else {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        if (is_array($params)) {
            $params = http_build_query($params);
        }
        if ($params) {
            file_put_contents($filename, $url.'?'.$params);
            curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    }
    $response = curl_exec($ch);
    if ($response === false) {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        dd($httpInfo);

        return false;
    }
    curl_close($ch);

    return $response;
}

/**
 * 冒泡排序
 *
 * @param  array 排序数组
 * @return array 排序号的数组
 */
function bubbleSort($arr)
{
    $len = count($arr);
    // 该层循环控制 需要冒泡的轮数
    for ($i = 1; $i < $len; $i++) { // 该层循环用来控制每轮 冒出一个数 需要比较的次数
        for ($k = 0; $k < $len - $i; $k++) {
            if ($arr[$k] > $arr[$k + 1]) {
                $tmp = $arr[$k + 1];
                $arr[$k + 1] = $arr[$k];
                $arr[$k] = $tmp;
            }
        }
    }

    return $arr;
}

/**
 * @Title: nonceStr
 *
 * @Description: 生成6位随机数
 *
 * @return string
 *
 * @Author: 李军伟
 */
function nonceStr()
{
    static $seed = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
    $str = '';
    for ($i = 0; $i < 6; $i++) {
        $rand = rand(0, count($seed) - 1);
        $temp = $seed[$rand];
        $str .= $temp;
        unset($seed[$rand]);
        $seed = array_values($seed);
    }

    return $str;
}

// 生成6位不重复的随机数
function rand_code($length = 6)
{
    $chars = '1234567890';
    $str = '';
    $size = strlen($chars);
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[mt_rand(0, $size - 1)];
    }

    return $str;
}

function dates($date)
{
    $timestamp = strtotime($date);
    $start_time = date('Y-m-1 00:00:00', $timestamp);
    $mdays = date('t', $timestamp);
    $end_time = date('Y-m-'.$mdays.' 23:59:59', $timestamp);

    return ['start_time' => $start_time, 'end_time' => $end_time];
}
