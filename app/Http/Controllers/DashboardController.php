<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
// use App\Models\ActivationCode;
// We might use this or calculate on the fly
// use App\Models\ActivationCodePreset;
use App\Http\Middleware\AdminUtilityMiddleware;
use App\Models\Admin\AdminUser;
use App\Models\AuthCode;
use App\Repository\Admin\HuobiRepository;
use App\Repository\Admin\AuthCodeRepository;
use App\Repository\Admin\AdminUserRepository;
use App\Repository\Admin\EquipmentRepository;
use App\Models\Assort;
use App\Models\AssortLevel;
use App\Models\Admin\Huobi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Added for type hinting and updates
use Illuminate\Support\Facades\DB; // Added for database transactions
use Illuminate\Support\Str; // Added for generating unique codes

// Added for validation

class DashboardController extends Controller
{
    
    public $count = 0;

    /**
     * Display the agent dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $utility = new AdminUtilityMiddleware();

        $user = Auth::guard('admin')->user();
        if (! $user) {
            return redirect()->route('admin.login'); // or your admin login route
        }

        $balance = $user->balance;
        $profit = ['status' => 0, 'type' => 1, 'user_id' => \Auth::guard('admin')->user()->id];        
        $startOfCurrentMonth = Carbon::now()->startOfMonth();
        $endOfCurrentMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $month = date('m');
        $date = date("Y-m", time());
        $last_month = "0" . (date("m") - 1);
        $last = strtotime("-1 month", time());
        $last_date = date("Y-m", $last);

        if (\Auth::guard('admin')->user()->id == 1) {
            $where = ['is_try' => 1];
        } else {
            $where = ['is_try' => 1, 'user_id' => \Auth::guard('admin')->user()->id];
        }// only user id=1 (superadmin) can view all records of all users , else only see own records
        
        $monthlyGeneratedCurrentMonth = AuthCodeRepository::lowerByCode($where, dates($date));
        // $monthlyGeneratedCurrentMonth = AuthCodeRepository::lowerByCode($where, [
        //     'start_time' => $startOfCurrentMonth,
        //     'end_time' => $endOfCurrentMonth,
        // ]);
        
        $generatedLastMonth = AuthCodeRepository::lowerByCode($where, dates($last_date));
        // $generatedLastMonth = AuthCodeRepository::lowerByCode($where, [
        //     'start_time' => $startOfLastMonth,
        //     'end_time' => $endOfLastMonth,
        // ]);

        $totalGeneratedQuantity = AuthCodeRepository::countByCode($where);

        // // HOTCOIN Usage (Last Month) - using user_id and event 'code_generation_cost'
        // $usageHotcoinLastMonth = Huobi::where('user_id', $user->id)
        //     ->where('type', '2')
        //     // ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
        //     ->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])
        //     ->sum('money');
        // $usageHotcoinLastMonth = abs($usageHotcoinLastMonth);
        $expend_where = ['type' => 2, 'user_id' => \Auth::guard('admin')->user()->id];//上月消耗 hotcoin usage
        $usageHotcoinLastMonth = HuobiRepository::expendByHuobi($expend_where, dates($last_date));
        $this->getLevel(\Auth::guard('admin')->user()->id);


        $all_users = AdminUserRepository::getDataByWhere([]);

        $ids = $utility->get_downline(
            $all_users,
            \Auth::guard('admin')->user()->id,
            \Auth::guard('admin')->user()->level_id
        );
        // Total Profit - assuming 'profit_distribution' event and positive money
        $totalProfit = HuobiRepository::lowerByAddProfit($profit);

        // This Month Profit
        $thisMonthProfit = HuobiRepository::lowerByProfit(dates($date), $profit);
        // $thisMonthProfit = Huobi::where('user_id', $user->id)
        //     ->where('type', '1') // Assuming 'profit_distribution' is the event for profit
        //     ->where('money', '>', 0)
        //     ->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])
        //     ->sum('money');

        // Last Month Profit
        $lastMonthProfit = HuobiRepository::lowerByProfit(dates($last_date), $profit);

        // $lastMonthProfit = Huobi::where('user_id', $user->id)
        //     ->where('type', '1') // Assuming 'profit_distribution' is the event for profit
        //     ->where('money', '>', 0)
        //     ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
        //     ->sum('money');

        $lower_month_code = HuobiRepository::lowerByCode(dates($date), $ids);//本月下级生成授权码个数
        $lower_last_month_code = HuobiRepository::lowerByCode(dates($last_date), $ids);//        // 上月下级生成授权码个数
// 这两个没show
$locale = session('customer_lang_name');

        // $totalMembers = AdminUser::count(); //要 show 全部 user 就用这个
        $totalMembers = $this->count; //这个 fetch all downlines show 下线
        // $activationCodePresets = Assort::where('try_num', '>', 0)->orderBy('assort_name')->get();
        $level_id = \Auth::guard('admin')->user()->level_id;

        $parent_id = $utility->getParentId(\Auth::guard('admin')->user()->id);

        if ($level_id == 8) {
            $where = ['user_id' => \Auth::guard('admin')->user()->id];
            $activationCodePresets = Defined::query()->where($where)->orderBy('assort_id')->get();
        } else {
            // 先验证该国代有没有自定义级别配置管理,如果没有则获取默认级别配置
            $guodai_where = ['user_id' => $parent_id];
            $res = EquipmentRepository::findByWhere($guodai_where);
            if ($res) {
                // 否则从自己的最上级（国级）获取数据
                $where = ['level_id' => $level_id, 'user_id' => $parent_id];
                $activationCodePresets = AssortLevel::query()->where($where)->get();
            } else {
                // 否则从自己的最上级（国级）获取数据
                $where = ['level_id' => $level_id, 'user_id' => 1];
                $activationCodePresets = AssortLevel::query()->where($where)->get();
            }
        }
        // $activationCodePresets = AssortLevel::with('assorts')
        // ->where('level_id', $level_id)
        // ->orderBy('assort_id')
        // ->get();
        
        return view('admin.dashboard', compact(
            'balance',
            'monthlyGeneratedCurrentMonth',
            'generatedLastMonth',
            'totalGeneratedQuantity',
            'usageHotcoinLastMonth',
            'thisMonthProfit',
            'lastMonthProfit',
            'totalProfit',
            'totalMembers',
            'activationCodePresets'
        ));
    }
// 获取下级的个数
public function getLevel($id)
{
    $count_where = ['pid' => $id];
    $ids = AdminUserRepository::getIdsByWhere($count_where);
    foreach ($ids as $info) {
        if (!empty($info)) {
            $this->count++;
            $this->getLevel($info);
        }
    }
}
    /**
     * Handle generation of activation codes.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateCode(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'activation_code_preset_id' => ['required', 'exists:assorts,id'],
        ]);

        $preset = Assort::find($validated['activation_code_preset_id']);

        if (! $preset || ! $preset->is_active) {
            return redirect()->route('dashboard')->with('error', 'Selected activation code type is invalid or not active.');
        }

        if ($user->hotcoin_balance < $preset->hotcoin_cost) {
            return redirect()->route('dashboard')->with('error', 'Insufficient HOTCOIN balance to generate this code.');
        }

        try {
            DB::beginTransaction();

            // 1. Deduct cost from user's balance
            $user->hotcoin_balance -= $preset->hotcoin_cost;
            $user->save();

            // 2. Create the Activation Code
            $newCode = AuthCode::create([
                'code' => $this->generateUniqueCode(), // Helper method to generate a unique code string
                'assort_id' => $preset->id,
                'user_id' => $user->id,
                'status' => 'available',
                'hotcoin_cost_at_generation' => $preset->hotcoin_cost,
                'duration_days_at_generation' => $preset->duration_days,
                'generated_at' => now(),
            ]);

            // 3. Create Hotcoin Transaction Log
            Huobi::create([
                'user_id' => $user->id,
                'event' => 'code_generation_cost',
                'money' => -$preset->hotcoin_cost, // Store cost as a negative value for debits
                'description' => 'Cost for generating code: '.$newCode->code.' (Preset: '.$preset->name.')',
                'related_auth_code_id' => $newCode->id,
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Auth code '.$newCode->code.' generated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the exception $e->getMessage()
            return redirect()->route('dashboard')->with('error', 'An error occurred while generating the code. Please try again.');
        }
    }

    /**
     * Generate a unique activation code string.
     *
     * @return string
     */
    private function generateUniqueCode()
    {
        do {
            // Example: A1B2-C3D4-E5F6 (adjust length and format as needed)
            $code = strtoupper(
                Str::random(4).'-'.
                Str::random(4).'-'.
                Str::random(4)
            );
        } while (AuthCode::where('code', $code)->exists());

        return $code;
    }
    
}
