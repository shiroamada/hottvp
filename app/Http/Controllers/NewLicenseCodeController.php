<?php

namespace App\Http\Controllers;

use App\Models\Admin\AdminUser;
use App\Models\Assort;
use App\Models\AuthCode;
use App\Models\Defined;
use App\Models\AssortLevel;

use App\Http\Requests\Admin\AuthCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    // TODO: Future Modernization - Replace PHPExcel with Maatwebsite/Excel for exports.
    // TODO: Future Modernization - Integrate actual external API calls for code generation (createCode and getApiByBatch if applicable).
    // NOTE: The original AuthCodeController used a protected $formNames property and AuthCodeRequest for validation.
    //       For this migration, request data is accessed directly and validation is handled inline.
    //       Consider creating dedicated Form Request classes for better Laravel 12 practices in a future refactoring.


    public function index(Request $request): View
    {
        $user = Auth::guard('admin')->user();
        $query = AuthCode::where('is_try', 1)->with('assort');

        if ($user->id != 1) {
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('auth_code')) {
            $query->where('auth_code', 'like', '%' . $request->input('auth_code') . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('assort_id')) {
            $query->where('assort_id', $request->input('assort_id'));
        }

        if ($request->filled('date_range')) {
            // Assuming daterangepicker format "YYYY-MM-DD - YYYY-MM-DD"
            $dates = explode(' - ', $request->input('date_range'));
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }

        $codes = $query->latest()->paginate(20)->withQueryString();
        $assorts = Assort::all();

        // Repopulate form fields
        $codes->auth_code = $request->auth_code;
        $codes->status = $request->status;
        $codes->assort_id = $request->assort_id;
        $codes->date_range = $request->date_range;

        return view('license.list', compact('codes', 'assorts'));
    }

    /**
     * @Title: list
     *
     * @Description: 试看码管理-试看码列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     *
     * @Author: 李军伟
     */
    public function list(Request $request)
    {
        $perPage = (int) $request->get('limit', env('APP_PAGE'));
        $condition = $request->only(['auth_code', 'status', 'created_at']); // Only these fields are used in old list method
        $params = $condition; // For repopulating form fields

        $query = AuthCode::where('is_try', 2); // is_try = 2 for trial codes

        if (\Auth::guard('admin')->user()->id != 1) {
            $query->where('user_id', \Auth::guard('admin')->user()->id);
        }

        if (isset($condition['auth_code']) && $condition['auth_code'] !== '') {
            $query->where('auth_code', 'like', '%' . $condition['auth_code'] . '%');
        }
        if (isset($condition['status']) && $condition['status'] != -1) {
            $query->where('status', $condition['status']);
        }
        if (isset($condition['created_at']) && !empty($condition['created_at'])) {
            $dates = explode(' - ', $condition['created_at']);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }

        $lists = $query->latest()->paginate($perPage)->withQueryString();

        // Repopulate form fields
        $lists->auth_code = $request->auth_code;
        $lists->status = $request->status;
        $lists->created_at = $request->created_at; // Assuming this is the date range field

        // Get assort list for filtering, if needed in the view
        $assorts = Assort::all();

        return view('admin.try.index', [
            'lists' => $lists,
            'condition' => $params,
            'assorts' => $assorts, // Pass assorts even if not explicitly used in old list, for consistency
        ]);
    }

    /**
     * @Title: add
     *
     * @Description: 试看码管理-新增试看码
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     * @Author: 李军伟
     */
    public function add()
    {
        return view('admin.try.add');
    }

    /**
     * @Title: hold
     *
     * @Description: 试看码管理-保存试看码
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     *
     * @Author: 李军伟
     */
    public function hold(AuthCodeRequest $request)
    {
        DB::beginTransaction(); // 开启事务
        try {
            $data = $request->only(['number', 'remark']); // Only these fields are used in old hold method
            $user = Auth::guard('admin')->user();

            if (!isset($data['number']) || (int)$data['number'] <= 0) {
                return [
                    'code' => 1,
                    'msg' => trans('authCode.code_num'),
                    'redirect' => false,
                ];
            }

            if ((int)$data['number'] > $user->try_num) {
                return [
                    'code' => 1,
                    'msg' => trans('authCode.exceed_num'),
                    'redirect' => false,
                ];
            }

            $generatedCodes = [];
            for ($i = 0; $i < (int)$data['number']; $i++) {
                $generatedCodes[] = [
                    'assort_id' => 5, // Hardcoded in old controller for trial codes
                    'user_id' => $user->id,
                    'auth_code' => strtoupper(Str::random(12)), // Using Str::random as getApiByBatch is not available
                    'num' => (int)$data['number'],
                    'type' => $user->type,
                    'remark' => $data['remark'] ?? null,
                    'is_try' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Generate code records
            DB::table('auth_codes')->insert($generatedCodes);

            // Decrement user's trial code count
            $user->decrement('try_num', (int)$data['number']);

            DB::commit();  // 提交

            return [
                'code' => 0,
                'msg' => trans('general.createSuccess'),
                'redirect' => true,
            ];
        } catch (\Exception $e) {
            DB::rollback();  // 回滚

            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false,
            ];
        }
    }

    /**
     * @Title: records
     *
     * @Description: 获取记录管理-获取记录列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     *
     * @Author: 李军伟
     */
    public function records(Request $request)
    {
        $perPage = (int) $request->get('limit', env('APP_PAGE'));
        $condition = $request->only(['user_id', 'created_at']); // Fields used in old records method

        $query = TryCode::query();

        if (\Auth::guard('admin')->user()->id != 1) {
            $query->where('user_id', \Auth::guard('admin')->user()->id);
        }

        if (isset($condition['created_at']) && !empty($condition['created_at'])) {
            $dates = explode(' - ', $condition['created_at']);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }

        $lists = $query->latest()->paginate($perPage)->withQueryString();

        // Get assort list for filtering, if needed in the view
        $assorts = Assort::all();

        return view('admin.try.records', [
            'lists' => $lists,
            'condition' => $condition,
            'assort' => $assorts, // Old controller uses 'assort' key here
        ]);
    }

    public function export(Request $request)
    {
        $fileName = "license_codes_" . date('Y_m_d_H_i_s') . ".xlsx";
        return Excel::download(new LicenseCodesExport($request), $fileName);
    }

    public function update(Request $request, string $code_id)
    {
        $code = AuthCode::findOrFail($code_id);
        // Ensure the user can only update their own codes
        if ($code->user_id !== Auth::guard('admin')->id()) {
            abort(403);
        }

        $request->validate([
            'remark' => 'nullable|string|max:128',
        ]);

        $code->update(['remark' => $request->remark]);

        return [
            'code' => 0,
            'msg' => trans('general.updateSuccess'),
            'redirect' => true,
        ];
    }

    
        
    

    /**
     * @Title: getApi
     *
     * @Description: 获取单个code
     *
     * @return array
     *
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
                'data' => $code,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false,
            ];
        }
    }

    public function detail(): View
    {
        $user = Auth::guard('admin')->user();

        // Find the last code generated by the user to determine the batch size
        $lastCode = AuthCode::where('user_id', $user->id)
            ->where('is_try', 1)
            ->latest('id')
            ->first();

        $codes = collect();
        if ($lastCode && $lastCode->num > 0) {
            // Fetch the last batch of codes based on the 'num' field
            $codes = AuthCode::where('user_id', $user->id)
                ->where('is_try', 1)
                ->with('assort')
                ->latest('id')
                ->limit($lastCode->num)
                ->get();
        }

        return view('license.detail', compact('codes'));
    }

    /**
     * @Title: remark
     *
     * @Description: 首页更新备注
     *
     * @return array
     *
     * @Author: 李军伟
     */
    public function remark(Request $request)
    {
        $data = $request->only(['code', 'remark']);

        try {
            if (mb_strlen($data['remark']) > 128) {
                return [
                    'code' => 1,
                    'msg' => trans('general.max_length'),
                    'redirect' => false,
                ];
            }

            $authCode = AuthCode::where('auth_code', $data['code'])->first();

            if (!$authCode) {
                return [
                    'code' => 1,
                    'msg' => 'Code not found.',
                    'redirect' => false,
                ];
            }

            // Ensure the user owns the code
            if ($authCode->user_id !== Auth::guard('admin')->id()) {
                return [
                    'code' => 1,
                    'msg' => 'Unauthorized action.',
                    'redirect' => false,
                ];
            }

            $authCode->update(['remark' => $data['remark']]);

            return [
                'code' => 0,
                'msg' => trans('general.updateSuccess'),
                'redirect' => true,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 1,
                'msg' => $e->getMessage(),
                'redirect' => false,
            ];
        }
    }

    public function down()
    {
        $fileName = "last_batch_" . date('Y_m_d_H_i_s') . ".xlsx";
        return Excel::download(new LastBatchExport(), $fileName);
    }

    public function tryExport(Request $request)
    {
        $fileName = "trial_codes_" . date('Y_m_d_H_i_s') . ".xlsx";
        return Excel::download(new TrialCodesExport($request), $fileName);
    }

    // Temporary method for debugging route issues
    public function testUpdate()
    {
        return response()->json(['message' => 'Test update route reached!']);
    }
    
    public function create(): View
    {
        $user = Auth::guard('admin')->user();
        $level_id = $user->level_id;
        $parent_id = $this->getParentId($user->id);

        $assortsData = collect(); // This will hold the data to be passed to the view as 'assorts'

        // If level is custom (level_id == 8), get data from Defined table
        if ($level_id == 8) {
            $definedItems = Defined::where('user_id', $user->id)->orderBy('assort_id')->get();
            $assortsData = $definedItems->map(function ($item) {
                $assort = Assort::find($item->assort_id);
                if ($assort) {
                    $assort->money = $item->money;
                } else {
                    // Fallback if assort not found, create a basic one
                    $assort = new Assort(['id' => $item->assort_id, 'assort_name' => 'Unknown', 'duration' => 0]);
                    $assort->money = $item->money;
                }
                return $assort;
            });
        } else {
            // Check if the parent has custom level configuration in AssortLevel
            $guodai_has_custom_config = AssortLevel::where('user_id', $parent_id)->first();

            if ($guodai_has_custom_config) {
                // Get data from the parent's custom configuration
                $assortLevels = AssortLevel::where('level_id', $level_id)
                                            ->where('user_id', $parent_id)
                                            ->get();
            } else {
                // Otherwise, get data from the default "country-level" agent (user_id = 1)
                $assortLevels = AssortLevel::where('level_id', $level_id)
                                            ->where('user_id', 1)
                                            ->get();
            }

            $assortsData = $assortLevels->map(function ($item) {
                $assort = Assort::find($item->assort_id);
                if ($assort) {
                    $assort->money = $item->money;
                } else {
                    // Fallback if assort not found
                    $assort = new Assort(['id' => $item->assort_id, 'assort_name' => 'Unknown', 'duration' => 0]);
                    $assort->money = $item->money;
                }
                return $assort;
            });
        }

        return view('license.generate', ['assorts' => $assortsData]);
    }

    public function store(AuthCodeRequest $request)
    {
        $assort = Assort::find($request->assort_id);
        $quantity = $request->number;
        $user = Auth::guard('admin')->user();

        $level_id = $user->level_id;
        $parent_id = $this->getParentId($user->id);

        // Get user_profit for logging into auth_codes table, mirroring old logic
        $user_profit_record = null;
        if ($parent_id > 1) { // Only if there's a relevant parent
            // Assuming level_id 3 is the specific admin level for profit calculation as per old code
            $user_profit_record = AssortLevel::where('user_id', $parent_id)
                                            ->where('assort_id', $assort->id)
                                            ->where('level_id', 3) // Hardcoded level_id 3 from old controller
                                            ->first();
        }
        $profit_per_code = $user_profit_record->money ?? 0.00;

        $equipment = null;
        // Get equipment (AssortLevel or Defined) based on old logic
        if ($level_id == 8) {
            $equipment = Defined::where('user_id', $user->id)
                                ->where('assort_id', $request->assort_id)
                                ->first();
        } else {
            $guodai_has_custom_config = AssortLevel::where('user_id', $parent_id)->first();
            if ($guodai_has_custom_config) {
                $equipment = AssortLevel::where('level_id', $level_id)
                                        ->where('assort_id', $request->assort_id)
                                        ->where('user_id', $parent_id)
                                        ->first();
            } else {
                $equipment = AssortLevel::where('level_id', $level_id)
                                        ->where('assort_id', $request->assort_id)
                                        ->where('user_id', 1)
                                        ->first();
            }
        }

        if (! $equipment) {
            return [
                'code' => 1,
                'msg' => 'Cost for this code type is not defined.',
                'redirect' => false,
            ];
        }

        // Validate mini_money against system price
        if ($request->filled('mini_money') && $equipment->money != $request->mini_money) {
            return [
                'code' => 1,
                'msg' => trans('authCode.auth_query'),
                'redirect' => false,
            ];
        }

        $totalCost = $equipment->money * $quantity;

        if ($user->balance < $totalCost) {
            return [
                'code' => 1,
                'msg' => 'Insufficient HOTCOIN balance.',
                'redirect' => false,
            ];
        }

        DB::beginTransaction();
        try {
            $generatedCodes = [];
            $duration = $assort->duration;
            $expiryDate = now()->addDays($duration);
            $try_num_to_add = 0;

            if ($request->type == 1) { // Single code generation
                $auth_code = createCode(); // Using helper function
                if (strlen($auth_code) != 12) {
                    DB::rollback();
                    return ['code' => 1, 'msg' => trans('authCode.auth_code_fail') . '...', 'redirect' => false];
                }
                $generatedCodes[] = [
                    'assort_id' => $assort->id,
                    'user_id' => $user->id,
                    'auth_code' => $auth_code,
                    'num' => 1,
                    'type' => $user->type,
                    'profit' => $profit_per_code,
                    'remark' => $request->remark,
                    'is_try' => 1,
                    'expire_at' => $expiryDate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $try_num_to_add = $assort->try_num;
            } else {  // Batch code generation
                $codes = getApiByBatch(['number' => $quantity]); // Using getApiByBatch helper
                for ($i = 0; $i < $quantity; $i++) {
                    if (strlen($codes[$i]) != 12) {
                        return ['code' => 1, 'msg' => trans('authCode.exceed'), 'redirect' => false];
                    }
                    $generatedCodes[] = [
                        'assort_id' => $assort->id,
                        'user_id' => $user->id,
                        'auth_code' => $codes[$i],
                        'num' => $quantity,
                        'type' => $user->type,
                        'profit' => $profit_per_code,
                        'remark' => $request->remark,
                        'is_try' => 1,
                        'expire_at' => $expiryDate,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $try_num_to_add = $assort->try_num * $quantity;
            }

            DB::table('auth_codes')->insert($generatedCodes);

            $user->balance -= $totalCost;
            $user->save();

            if ($try_num_to_add > 0) {
                $user->increment('try_num', $try_num_to_add);
            }

            DB::table('huobis')->insert([
                'user_id' => $user->id,
                'money' => $totalCost,
                'status' => 0,
                'type' => 2,
                'number' => $quantity,
                'event' => $user->name . ' ' . trans('general.generate') . ' ' . $assort->assort_name,
                'own_id' => $user->id,
                'assort_id' => $assort->id,
                'user_account' => $user->account,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($try_num_to_add > 0) {
                DB::table('try_codes')->insert([
                    'user_id' => $user->id,
                    'number' => $try_num_to_add,
                    'description' => trans('general.generate') . $assort->assort_name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if ($user->pid > 1) {
                $this->distributeProfit($user, $assort, $totalCost, $quantity);
            }

            DB::commit();

            return redirect()->route('license.list')->with('success', trans('general.createSuccess') . ' ' . $quantity . ' license codes.');
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'code' => 1,
                'msg' => 'An error occurred while generating the codes. Please try again.',
                'redirect' => false,
            ];
        }
    }

    private function distributeProfit($downlineUser, $assort, $downlineCost, $quantity)
    {
        // Stop if there is no upline, or we've reached the top-level admin
        if (is_null($downlineUser->pid) || $downlineUser->pid <= 1) {
            return;
        }

        $upline = AdminUser::find($downlineUser->pid);

        // Stop if the upline doesn't exist
        if (is_null($upline)) {
            return;
        }

        // 1.1 如果上级用户已经注销，则直接获取国代用户
        if ($upline->is_cancel == 2) { // 代表该用户已经注销并已经国代管理员都审核通过
            $upline = AdminUser::find($this->getParentId($downlineUser->id)); // Get the top-level parent
            if (is_null($upline)) { // If even the top-level parent is not found, stop
                return;
            }
        }

        // Find the upline's cost for this assort based on old logic
        $upline_equipment = null;
        $parent_id_for_upline = $this->getParentId($upline->id); // Get the top-level parent for the upline

        if ($upline->level_id == 8) {
            $upline_equipment = Defined::where('user_id', $upline->id)
                                        ->where('assort_id', $assort->id)
                                        ->first();
        } else {
            $guodai_has_custom_config = AssortLevel::where('user_id', $parent_id_for_upline)->first();
            if ($guodai_has_custom_config) {
                $upline_equipment = AssortLevel::where('level_id', $upline->level_id)
                                                ->where('assort_id', $assort->id)
                                                ->where('user_id', $parent_id_for_upline)
                                                ->first();
            } else {
                $upline_equipment = AssortLevel::where('level_id', $upline->level_id)
                                                ->where('assort_id', $assort->id)
                                                ->where('user_id', 1)
                                                ->first();
            }
        }

        // If the upline has a defined cost, calculate their profit
        if ($upline_equipment) {
            $upline_cost = $upline_equipment->money * $quantity;
            $profit = $downlineCost - $upline_cost;

            if ($profit > 0) {
                $upline->balance += $profit;
                $upline->save();

                // Log the profit transaction for the upline
                DB::table('huobis')->insert([
                    'user_id' => $upline->id,
                    'money' => $profit,
                    'status' => 0, // 0 for profit
                    'type' => 1,   // 1 for increase
                    'number' => $quantity,
                    'event' => $downlineUser->name . ' ' . trans('general.generate') . ' ' . $assort->assort_name,
                    'own_id' => $downlineUser->id,
                    'create_id' => $downlineUser->id,
                    'assort_id' => $assort->id,
                    'user_account' => $downlineUser->account,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Recursively call for the next level up in the hierarchy
            $this->distributeProfit($upline, $assort, $upline_cost, $quantity);
        }
        // If the upline does not have a defined cost, the chain is broken, so we stop.
        // Alternatively, you could decide to continue with the downline's cost, but the old logic implies stopping.
    }

    /**
     * @Title: getParentId
     *
     * @Description: 获取最上级（国级）用户id
     *
     * @param int $userId
     * @return int
     */
    private function getParentId(int $userId): int
    {
        $user = AdminUser::find($userId);

        // If the user exists and has a parent (pid > 1), recursively find the parent's parent
        if ($user && $user->pid && $user->pid > 1) {
            return $this->getParentId($user->pid);
        }

        // If no parent or pid is 1, this is the top-level or the immediate parent is the super admin
        return $userId;
    }
}
