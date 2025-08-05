<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
// use App\Models\ActivationCode;
// We might use this or calculate on the fly
// use App\Models\ActivationCodePreset;
use App\Models\Admin\AdminUser;
use App\Models\AuthCode;

use App\Models\Assort;
use App\Models\Admin\Huobi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Added for type hinting and updates
use Illuminate\Support\Facades\DB; // Added for database transactions
use Illuminate\Support\Str; // Added for generating unique codes

// Added for validation

class DashboardController extends Controller
{
    /**
     * Display the agent dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::guard('admin')->user();
        if (! $user) {
            return redirect()->route('admin.login'); // or your admin login route
        }

        $balance = $user->balance;
        $profit = $user->profit;
        $startOfCurrentMonth = Carbon::now()->startOfMonth();
        $endOfCurrentMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $month = date('m');
        $date = date("Y-m", time());
        $last_month = "0" . (date("m") - 1);
        $last = strtotime("-1 month", time());
        $last_date = date("Y-m", $last);
        
        $monthlyGeneratedCurrentMonth = AuthCode::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])
            ->count();

        $generatedLastMonth = AuthCode::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        $totalGeneratedQuantity = AuthCode::where('user_id', $user->id)->count();

        // HOTCOIN Usage (Last Month) - using user_id and event 'code_generation_cost'
        $usageHotcoinLastMonth = Huobi::where('user_id', $user->id)
            ->where('type', '2')
            // ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])
            ->sum('money');
        $usageHotcoinLastMonth = abs($usageHotcoinLastMonth);

        // Total Profit - assuming 'profit_distribution' event and positive money
        $totalProfit = Huobi::where('user_id', $user->id)
            ->where('event', 'profit_distribution') // Assuming 'profit_distribution' is the event for profit
            ->where('money', '>', 0)
            ->sum('money');

        // This Month Profit
        $thisMonthProfit = Huobi::where('user_id', $user->id)
            ->where('event', 'profit_distribution') // Assuming 'profit_distribution' is the event for profit
            ->where('money', '>', 0)
            ->whereBetween('created_at', [$startOfCurrentMonth, $endOfCurrentMonth])
            ->sum('money');

        // Last Month Profit
        $lastMonthProfit = Huobi::where('user_id', $user->id)
            ->where('event', 'profit_distribution') // Assuming 'profit_distribution' is the event for profit
            ->where('money', '>', 0)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('money');
        $totalMembers = AdminUser::count();
        $activationCodePresets = Assort::where('try_num', '>', 0)->orderBy('assort_name')->get();
        
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
