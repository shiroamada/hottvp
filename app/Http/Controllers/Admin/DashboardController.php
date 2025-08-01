<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivationCode;
// We might use this or calculate on the fly
use App\Models\ActivationCodePreset;
use App\Models\Admin\AdminUser;
use App\Models\HotcoinTransaction;
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

        $monthlyGeneratedCurrentMonth = ActivationCode::where('generated_by_agent_id', $user->id)
            ->whereBetween('generated_at', [$startOfCurrentMonth, $endOfCurrentMonth])
            ->count();

        $generatedLastMonth = ActivationCode::where('generated_by_agent_id', $user->id)
            ->whereBetween('generated_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        $totalGeneratedQuantity = ActivationCode::where('generated_by_agent_id', $user->id)->count();

        $usageHotcoinLastMonth = Huobi::where('create_id', $user->id)
            ->where('event', 'code_generation_cost')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('money');
        $usageHotcoinLastMonth = abs($usageHotcoinLastMonth);

        $thisMonthProfit = $profit; // Use profit as total profit for now
        $lastMonthProfit = $profit; // Use profit as total profit for now
        $totalProfit = $profit;
        $totalMembers = AdminUser::where('pid', $user->id)->count();
        $activationCodePresets = ActivationCodePreset::where('is_active', true)->orderBy('name')->get();

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
            'activation_code_preset_id' => ['required', 'exists:activation_code_presets,id'],
        ]);

        $preset = ActivationCodePreset::find($validated['activation_code_preset_id']);

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
            $newCode = ActivationCode::create([
                'code' => $this->generateUniqueCode(), // Helper method to generate a unique code string
                'activation_code_preset_id' => $preset->id,
                'generated_by_agent_id' => $user->id,
                'status' => 'available',
                'hotcoin_cost_at_generation' => $preset->hotcoin_cost,
                'duration_days_at_generation' => $preset->duration_days,
                'generated_at' => now(),
            ]);

            // 3. Create Hotcoin Transaction Log
            HotcoinTransaction::create([
                'agent_id' => $user->id,
                'type' => 'code_generation_cost',
                'money' => -$preset->hotcoin_cost, // Store cost as a negative value for debits
                'description' => 'Cost for generating code: '.$newCode->code.' (Preset: '.$preset->name.')',
                'related_activation_code_id' => $newCode->id,
                'transaction_date' => now(),
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Activation code '.$newCode->code.' generated successfully!');

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
        } while (ActivationCode::where('code', $code)->exists());

        return $code;
    }
}
