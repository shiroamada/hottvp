<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Admin\AdminUser;
use App\Models\Admin\Huobi;
use App\Models\AdminHuobi;
use App\Models\Assort;
use App\Models\AuthCode;
use App\Models\Defined;
use App\Models\AssortLevel;

use App\Repository\Admin\EquipmentRepository;
use App\Repository\Admin\AuthCodeRepository;
use App\Repository\Admin\AssortRepository;
use App\Repository\Admin\AdminUserRepository;
use App\Repository\Admin\HuobiRepository;
use App\Repository\Admin\TryCodeRepository;

use App\Services\AdminUtilityService;
use App\Http\Requests\Admin\AuthCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Models\TryCode;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LicenseCodesExport;
use App\Exports\LastBatchExport;
use App\Exports\TrialCodesExport;

class NewLicenseCodeController extends Controller
{
    protected $formNames = ['id', 'assort_id', 'user_id', 'auth_code', 'remark', 'status', 'expire_at', 'number', 'code', 'type', 'day'];

    /**
     * @Title: index
     * @Description: 授权码管理-授权码列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @Author: 李军伟
     */
    public function index(Request $request)
    {

        $perPage = (int)$request->get('limit', env('APP_PAGE'));
        $this->formNames[] = 'created_at';
        $this->formNames[] = 'date2';
        $condition = $request->only($this->formNames);
        $params = $condition;
        if (isset($condition['date2']) && !empty($condition['date2'])) {
            $times = array_map('trim', explode(" to ", $condition['date2']));
            $condition['startTime'] = $times[0];
            $condition['endTime'] = isset($times[1]) ? $times[1] : $times[0];
        }
        unset($condition['date2']);
        $condition['is_try'] = ['=', 1];
        if (Auth::guard('admin')->user()->id != 1) {
            $condition['user_id'] = ['=', Auth::guard('admin')->user()->id];
        }
        if (isset($condition['auth_code']) && $condition['auth_code'] == "") {
            unset($condition['auth_code']);
        }
        if (isset($condition['assort_id']) && $condition['assort_id'] == -1) {
            unset($condition['assort_id']);
        }
        if (isset($condition['status']) && $condition['status'] == -1) {
            unset($condition['status']);
        }

        $data = AuthCodeRepository::list($perPage, $condition);
        if (empty($data))
            return '';
        $data->auth_code = $request->auth_code;
        $data->status = $request->status;
        $data->assort_id = $request->assort_id;
        $data->date2 = $request->date2;

        // 获取授权码类型
        $assort_list = AssortRepository::getByList([]);

        return view('license.list', [
            'lists' => $data,  //列表数据
            'condition' => $params,
            'assort_list' => $assort_list,
        ]);
    }

    /**
     * @Title: list
     * @Description: 试看码管理-试看码列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @Author: 李军伟
     */
    public function list(Request $request)
    {
        $perPage = (int)$request->get('limit', env('APP_PAGE'));
        $this->formNames[] = 'created_at';
        $condition = $request->only($this->formNames);
        if (isset($condition['created_at']) && !empty($condition['created_at'])) {
            $times = array_map('trim', explode(" to ", $condition['created_at']));
            $condition['startTime'] = $times[0];
            $condition['endTime'] = isset($times[1]) ? $times[1] : $times[0];
        }
        unset($condition['created_at']);
        $condition['is_try'] = ['=', 2];
        if (Auth::guard('admin')->user()->id != 1) {
            $condition['user_id'] = ['=', Auth::guard('admin')->user()->id];
        }
        if (isset($condition['auth_code']) && $condition['auth_code'] == "") {
            unset($condition['auth_code']);
        }
        if (isset($condition['status']) && $condition['status'] == -1) {
            unset($condition['status']);
        }

        $data = AuthCodeRepository::list($perPage, $condition);
        if (empty($data))
            return '';
        $data->auth_code = $request->auth_code;
        $data->status = $request->status;

        return view('trial.list', [
            'lists' => $data,  //列表数据
            'condition' => $condition,
        ]);
    }

    /**
     * @Title: records
     * @Description: 获取记录管理-获取记录列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     * @Author: 李军伟
     */
    public function records(Request $request)
    {
        $perPage = (int)$request->get('limit', env('APP_PAGE'));
        $this->formNames[] = 'created_at';
        $condition = $request->only($this->formNames);
        if (Auth::guard('admin')->user()->id != 1) {
            $condition['user_id'] = ['=', Auth::guard('admin')->user()->id];
        }

        $data = TryCodeRepository::list($perPage, $condition);
        if (empty($data))
            return '';
        // 获取授权码可用获取免费数量
        $assort = AssortRepository::findByList([]);

        return view('try.records', [
            'lists' => $data,  //列表数据
            'condition' => $condition,
            'assort' => $assort
        ]);
    }

    /**
     * @Title: create
     * @Description: 授权码管理-新增授权码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function create(AdminUtilityService $utility) // Laravel auto-injects
    {

        $level_id = Auth::guard('admin')->user()->level_id;
        $parent_id = $utility->getParentId(Auth::guard('admin')->user()->id);
        // 如果级别为自定义，则从自定义里面获取数据
        if ($level_id == 8) {
            $where = ['user_id' => Auth::guard('admin')->user()->id];
            $equipment = Defined::query()->where($where)->orderBy('assort_id')->get();
        } else {
            // 先验证该国代有没有自定义级别配置管理,如果没有则获取默认级别配置
            $guodai_where = ['user_id' => $parent_id];
            $res = EquipmentRepository::findByWhere($guodai_where);
            if ($res) {
                // 否则从自己的最上级（国级）获取数据
                $where = ['level_id' => $level_id, 'user_id' => $parent_id];
                $equipment = AssortLevel::query()->where($where)->get();
            } else {
                // 否则从自己的最上级（国级）获取数据
                $where = ['level_id' => $level_id, 'user_id' => 1];
                $equipment = AssortLevel::query()->where($where)->get();
            }
        }

        return view('license.generate', [
            'equipment' => $equipment
        ]);
    }

    /**
     * @Title: add
     * @Description: 试看码管理-新增试看码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function add()
    {
        $availableTrialCodes = Auth::guard('admin')->user()->try_num;
        return view('trial.generate', compact('availableTrialCodes'));
    }

    /**
     * @Title: hold
     * @Description: 试看码管理-保存试看码
     * @param AuthCodeRequest $request
     * @return array
     * @Author: 李军伟
     */
    public function hold(Request $request)
    {
        DB::beginTransaction(); //开启事务
        try {
            $data = $request->only($this->formNames);
            $list = [];
            if ($data['number'] <= 0) {
                return [
                    'code' => 1,
                    'msg' => trans('authCode.code_num'),
                    'redirect' => false
                ];
            }
            $data['day'] = 1;
            if ( $data['number'] > Auth::guard('admin')->user()->try_num) {
                return [
                    'code' => 1,
                    'msg' => trans('authCode.exceed_num'),
                    'redirect' => false
                ];
            }
            $codes = getApiByBatch($data);

            // Check if enough codes were generated
            if (count($codes) < (int)$data['number']) {
                DB::rollback();
                return [
                    'code' => 1,
                    'msg' => trans('authCode.insufficient_pregenerated_codes'),
                    'redirect' => false
                ];
            }

            foreach ($codes as $codeData) {
                if (strlen($codeData['code']) < 10) {
                    DB::rollback(); // Rollback if an invalid code is found
                    return [
                        'code' => 1,
                        'msg' => 'Invalid Code Generated: ' . $codeData['code'],
                        'redirect' => false
                    ];
                }
                $param = [
                    'assort_id' => 5,
                    'user_id' => Auth::guard('admin')->user()->id,
                    'auth_code' => $codeData['code'],
                    'num' => $data['number'],
                    'type' => Auth::guard('admin')->user()->type,
                    'remark' => "Type: {$codeData['type']}, Vendor: {$codeData['vendor']}, Source: " . ($codeData['source'] ?? 'Unknown') . (isset($data['remark']) && $data['remark'] ? ' - ' . $data['remark'] : ''),
                    'is_try' => 2,
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'updated_at' => date("Y-m-d H:i:s", time()),
                ];
                $list[] = $param;
            }
            // 生成code记录
            AuthCode::query()->insert($list);
            // 生成code用户的试看码相应减少
            $where_user = ['id' => Auth::guard('admin')->user()->id];
            AdminUserRepository::decrByTry($where_user, $data['number']);
            DB::commit();  //提交
            return [
                'code' => 0,
                'msg' => trans('general.createSuccess'),
                'redirect' => true,
            ];
        } catch (QueryException $e) {
            DB::rollback();  //回滚
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: save
     * @Description: 授权码管理-保存授权码
     * @param AuthCodeRequest $request
     * @return array
     * @Author: 李军伟
     */
    public function save(AuthCodeRequest $request)
    {
        DB::beginTransaction(); //开启事务
        try {
            $this->formNames[] = 'mini_money';
            $data = $request->only($this->formNames);
            if ($data['mini_money'] <= 0) {
                return [
                    'code' => 1,
                    'msg' => trans('authCode.auth_code_gt'),
                    'redirect' => false
                ];
            }
            // 获取当前用户的级别
            $level_id = Auth::guard('admin')->user()->level_id;
            $parent_id = (new AdminUtilityService())->getParentId(Auth::guard('admin')->user()->id);
            // 获取管理员获得的利润
            $user_where = ['user_id' => $parent_id, 'assort_id' => $data['assort_id'], 'level_id' => 3];
            $user_profit = EquipmentRepository::findByWhere($user_where);
            // 如果级别为自定义，则从自定义里面获取数据
            if ($level_id == 8) {
                $where = ['user_id' => Auth::guard('admin')->user()->id, 'assort_id' => $data['assort_id']];
                $equipment = Defined::query()->where($where)->first();
            } else {
                // 先验证该国代有没有自定义级别配置管理,如果没有则获取默认级别配置
                $guodai_where = ['user_id' => $parent_id];
                $res = EquipmentRepository::findByWhere($guodai_where);
                if ($res) {
                    // 从自己的最上级（国级）获取数据
                    $where = ['level_id' => $level_id, 'assort_id' => $data['assort_id'], 'user_id' => $parent_id];
                    $equipment = AssortLevel::query()->where($where)->first();
                } else {
                    // 该国代有没有自定义级别配置
                    $where = ['level_id' => $level_id, 'assort_id' => $data['assort_id'], 'user_id' => 1];
                    $equipment = AssortLevel::query()->where($where)->first();
                }
            }
            // 如果提交过来的价格和系统的价格不一致，则进行提示让用户重新提交
            if ($equipment->money != $data['mini_money']) {
                return [
                    'code' => 1,
                    'msg' => trans('authCode.auth_query'),
                    'redirect' => false
                ];
            }
            $total = $equipment->money * $data['number'];
            Log::info('User balance: ' . Auth::guard('admin')->user()->balance);
            Log::info('Total cost: ' . $total);
            $user_money = (float) Auth::guard('admin')->user()->balance;
            Log::info('Checking balance and total: ', [
                'total' => $total,
                'user_money' => $user_money,
                'total type' => gettype($total),
                'user_money type' => gettype($user_money),
            ]);

            // 余额不足
            if ($total > $user_money) {
                Log::warning('User balance exceeded', [
                    'user_id' => Auth::guard('admin')->user()->id,
                    'user_balance' => $user_money,
                    'required' => $total,
                ]);
                DB::rollback();
                return [
                    'code' => 1,
                    'msg' => trans('authCode.exceed.balance') . '!',
                    'redirect' => false
                ];
            }

            $day = $equipment->assorts->duration;
            $list = [];
            $total_money = 0.00;
            $try_num = 0;
            // 获取天数
            $data['day'] = $day;
            // 从首页过来的
            $auth_code = "";
            if (isset($data['type']) && $data['type'] == 1) {

                $codes = getApiByBatch($data);
                Log::warning('Codes: ' . json_encode($codes));
                $codeData = $codes[0];
                $auth_code = $codeData['code'];
                if (strlen($auth_code) < 10) {
                    DB::rollback();  //回滚
                    return [
                        'code' => 1,
                        'msg' => trans('authCode.auth_code_fail'). '...',
                        'redirect' => false
                    ];
                }
                $param = [
                    'assort_id' => $data['assort_id'],
                    'user_id' => Auth::guard('admin')->user()->id,
                    'auth_code' => $auth_code,
                    'num' => 1,
                    'type' => Auth::guard('admin')->user()->type,
                    'profit' => isset($user_profit->money) ? $user_profit->money : 0.00,
                    'remark' => "Type: {$codeData['type']}, Vendor: {$codeData['vendor']}, Source: " . ($codeData['source'] ?? 'Unknown') . (isset($data['remark']) && $data['remark'] ? ' - ' . $data['remark'] : ''),
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'updated_at' => date("Y-m-d H:i:s", time()),
                ];
                $list[] = $param;
                $total_money = $equipment->money;
                $try_num = $equipment->assorts->try_num;
            } else {
                // 批量添加的
                // call Helper function
                $codes = getApiByBatch($data);

                if (count($codes) == 0) {
                    return [
                        'code' => 1,
                        'msg' => __('No Code Generated, Please contact Administrator'),
                        'redirect' => false
                    ];
                }

                foreach ($codes as $codeData) {
                    if (strlen($codeData['code']) < 10) {
                        return [
                            'code' => 1,
                            'msg' => __('Invalid Code Length Generated: ') . $codeData['code'],
                            'redirect' => false
                        ];
                    }
                    $param = [
                        'assort_id' => $data['assort_id'],
                        'user_id' => Auth::guard('admin')->user()->id,
                        'auth_code' => $codeData['code'],
                        'num' => $data['number'],
                        'type' => Auth::guard('admin')->user()->type,
                        'profit' => isset($user_profit->money) ? $user_profit->money : 0.00,
                        'remark' => "Type: {$codeData['type']}, Vendor: {$codeData['vendor']}, Source: " . ($codeData['source'] ?? 'Unknown') . (isset($data['remark']) && $data['remark'] ? ' - ' . $data['remark'] : ''),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'updated_at' => date("Y-m-d H:i:s", time()),
                    ];
                    $total_money += $equipment->money;
                    $list[] = $param;
                }
                $try_num = $equipment->assorts->try_num * $data['number'];
            }
            // 生成code记录
            AuthCode::query()->insert($list);
            $info_where = ['auth_code' => $auth_code];
            $info_code = AuthCodeRepository::findByWhere($info_where);
            // 生成code用户的火币相应减少
            $where_user = ['id' => Auth::guard('admin')->user()->id];
            AdminUserRepository::decr($where_user, $total_money);
            if ($try_num > 0) {
                AdminUserRepository::incrByTry($where_user, $try_num);
            }
            // 上级增加相应的金额
            $this->getSuperior($data, $total_money, Auth::guard('admin')->user()->pid, Auth::guard('admin')->user()->id, Auth::guard('admin')->user()->name);
            // 添加火币日志记录（自己的火币记录）
            $huobi = [
                'user_id' => Auth::guard('admin')->user()->id,
                'money' => $equipment->money * $data['number'],
                'status' => 0,
                'type' => 2,
                'number' => $data['number'],
                'event' => Auth::guard('admin')->user()->name . " " . trans('general.generate') . $equipment->assorts->assort_name,
                'own_id' => Auth::guard('admin')->user()->id,   // 事件用户id
                'assort_id' => $equipment->assorts->id,
                'user_account' => Auth::guard('admin')->user()->account,
                'created_at' => date("Y-m-d H:i:s", time()),
                'updated_at' => date("Y-m-d H:i:s", time()),
            ];
            Huobi::query()->insert($huobi);
            if ($try_num > 0) {
                // 添加试用码记录(自己的试用码记录)
                $try = [
                    'user_id' => Auth::guard('admin')->user()->id,
                    'number' => $try_num,
                    'description' => trans('general.generate') . $equipment->assorts->assort_name,
                    'created_at' => date("Y-m-d H:i:s", time()),
                    'updated_at' => date("Y-m-d H:i:s", time()),
                ];
                TryCode::query()->insert($try);
            }

            DB::commit();  //提交
            Log::info('Authorization code(s) created successfully for user ID ' . Auth::guard('admin')->user()->id . '. Total cost: ' . $total_money);
            return [
                'code' => 0,
                'msg' => trans('general.createSuccess'),
                'redirect' => true,
                'data' => $auth_code,
                'id' => isset($info_code->id) ? $info_code->id : "",
                'remark' => isset($info_code->remark) ? $info_code->remark : "",
            ];
        } catch (QueryException $e) {
            Log::warning('Error in save method: ' . $e->getMessage());
            DB::rollback();  //回滚
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: getSuperior
     * @Description: 上级获取利润的记录
     * @param $data
     * @param $total_money
     * @param $pid
     * @Author: 李军伟
     */
    public function getSuperior($data, $total_money, $pid, $id, $name)
    {
        if ($pid > 1) {
            // 获取当前用户的上级可以获取的利润
            // 1 获取上级用户的级别
            $level_where = ['id' => $pid];
            $level = AdminUserRepository::findByWhere($level_where);
            // 最顶级（国代）用户id
            // $parent_id = $this->getParentId(Auth::guard('admin')->user()->id);
            $parent_id = (new AdminUtilityService())->getParentId(Auth::guard('admin')->user()->id);
            // 1.1 如果上级用户已经注销，则直接获取国代用户
            if ($level->is_cancel == 2) {  // 代表该用户已经注销并已经国代管理员都审核通过
                $parent_where = ['id' => $parent_id];
                $level = AdminUserRepository::findByWhere($parent_where);
            }
            // 2 根据上级用户的级别获取该用户的成本  先验证该国代有没有自定义级别配置管理,如果没有则获取默认级别配置
            $guodai_where = ['user_id' => $parent_id];
            $res = EquipmentRepository::findByWhere($guodai_where);
            if ($res) {
                // 如果是自定义代理人，从自定义里面获取
                if ($level->level_id == 8) {
                    $equipment = Defined::query()->where(['user_id' => $level->id, 'assort_id' => $data['assort_id']])->first();
                } else {
                    // 从自己的最上级（国级）获取数据
                    $where = ['level_id' => $level->level_id, 'assort_id' => $data['assort_id'], 'user_id' => $parent_id];
                    $equipment = AssortLevel::query()->where($where)->first();
                }
            } else {
                if ($level->level_id == 8) {
                    $equipment = Defined::query()->where(['user_id' => $level->id, 'assort_id' => $data['assort_id']])->first();
                } else {
                    // 该国代有没有自定义级别配置
                    $where = ['level_id' => $level->level_id, 'assort_id' => $data['assort_id'], 'user_id' => 1];
                    $equipment = AssortLevel::query()->where($where)->first();
                }
            }
            // 3 登录后台当前用户的成本减去上级的成本，就是上级获取的利润
            $level_profit = $equipment->money * $data['number'];
            $profit = $total_money - $level_profit;
            // 4 给上级增加获取的利润
            if ($level->level_id == 3) {
                $level_where = ['id' => $level->id];
            }
            AdminUserRepository::incr($level_where, $profit);
            // 5 记录到日志里面
            // 上级，增加利润明细（根据级别进行增加）
            $huobi = [
                'user_id' => $level->id,   // 上级用户id
                'money' => $profit,
                'status' => 0,   // 状态  0 利润记录  1 充值记录
                'type' => 1,     // 金额状态  1 增加 2 减少
                'number' => $data['number'],
                'event' => $name . " " . trans('general.generate') . $equipment->assorts->assort_name,
                'own_id' => $id,   // 事件用户id
                'create_id' => $id, // 当前用户id
                'assort_id' => $equipment->assorts->id,
                'user_account' => Auth::guard('admin')->user()->account,
                'created_at' => date("Y-m-d H:i:s", time()),
                'updated_at' => date("Y-m-d H:i:s", time()),
            ];
            Huobi::query()->insert($huobi);
            $this->getSuperior($data, $level_profit, $level->pid, $level->id, $level->name);
        }
    }

    /**
     * @Title: update
     * @Description: 授权码管理-更新授权码
     * @param Request $request
     * @param $id
     * @return array
     * @Author: 李军伟
     */
    public function update(Request $request, $id)
    {
        $data = $request->only($this->formNames);
        try {
            if (mb_strlen($data['remark']) > 128) {
                return [
                    'code' => 1,
                    'msg' => trans('general.max_length'),
                    'redirect' => false
                ];
            }
            AuthCodeRepository::update($id, $data);
            return [
                'code' => 0,
                'msg' => trans('general.updateSuccess'),
                'redirect' => true
            ];
        } catch (QueryException $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: remark
     * @Description: 首页更新备注
     * @param Request $request
     * @return array
     * @Author: 李军伟
     */
    public function remark(Request $request)
    {
        $data = $request->only($this->formNames);
        try {
            if (isset($data['remark']) && mb_strlen($data['remark']) > 128) {
                return [
                    'code' => 1,
                    'msg' => trans('general.max_length'),
                    'redirect' => false
                ];
            }

            $where = ['auth_code' => $data['code']];
            $params = ['remark' => $data['remark']];
            AuthCodeRepository::updateByWhere($where, $params);

            return [
                'code' => 0,
                'msg' => trans('general.updateSuccess'),
                'redirect' => true
            ];

        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }




    /**
     * @Title: getApi
     * @Description: 获取单个code
     * @return array
     * @Author: 李军伟
     */
    public function getApi()
    {
        try {
            $code = createCode();
            return [
                'code' => 0,
                'msg' => trans('general.createSuccess'),
                'redirect' => true,
                'data' => $code
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false
            ];
        }
    }

    /**
     * @Title: export
     * @Description: 导入到excel
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @Author: 李军伟
     */
    public function export(Request $request)
    {
        $time = date('Y-m-d-') . rand_code(4);
        $filename = trans('authCode.auth_record') . '-' . $time . '.xlsx';
        return Excel::download(new LicenseCodesExport($request), $filename);
    }

    /**
     * @Title: down
     * @Description: 用户刚生成授权码导出
     * @Author: 李军伟
     */
    public function down()
    {
        $time = date('Y-m-d-') . rand_code(4);
        $filename = trans('authCode.auth_record') . '-' . $time . '.xlsx';
        return Excel::download(new LastBatchExport(), $filename);
    }

    /**
     * @Title: detail
     * @param Request $request
     * @Description: 新创建的授权码列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @Author: 李军伟
     */
    public function detail(Request $request)
    {
        $where['user_id'] = Auth::guard('admin')->user()->id;
        $where['is_try'] = 1;
        $info = AuthCode::query()->where($where)->orderBy('id', 'desc')->first();
        $code_list = AuthCode::query()->where($where)->orderBy('id', 'desc')->limit($info->num)->get();

        return view('license.detail', [
            'code_list' => $code_list,
        ]);
    }


    /**
     * @Title: export
     * @Description: 导入到excel
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @Author: 李军伟
     */
    public function tryExport(Request $request)
    {
        $time = date('Y-m-d-') . rand_code(4);
        $filename = trans('authCode.auth_record') . '-' . $time . '.xlsx';
        return Excel::download(new TrialCodesExport($request), $filename);
    }
}
