<?php

namespace App\Http\Controllers;

use App\Models\ActivationCode;
use App\Models\ActivationCodePreset;
use App\Models\AgentMonthlyProfit;
use App\Models\HotcoinTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LicenseCodeController extends Controller
{
    /**
     * Display the license code list view.
     */
    public function index(Request $request): View
    {
        $licenseCodes = ActivationCode::where('generated_by_agent_id', Auth::id())->latest()->paginate(10);

        return view('license.list', compact('licenseCodes'));
    }

    /**
     * Show the form for generating a new license code.
     */
    public function create(): View
    {
        $presets = ActivationCodePreset::where('is_active', true)->get();

        return view('license.generate', compact('presets'));
    }

    /**
     * Store a newly created license code in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'preset_id' => 'required|exists:activation_code_presets,id',
            'quantity' => 'required|integer|min:1|max:100',
            'remarks' => 'nullable|string|max:255',
        ]);

        $preset = ActivationCodePreset::find($request->preset_id);
        $quantity = $request->quantity;
        $totalCost = $preset->hotcoin_cost * $quantity;
        $agent = Auth::user();

        if ($agent->hotcoin_balance < $totalCost) {
            return back()->withErrors(['hotcoin' => 'Insufficient HOTCOIN balance.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $generatedCodes = [];
            for ($i = 0; $i < $quantity; $i++) {
                $generatedCodes[] = [
                    'code' => strtoupper(Str::random(12)),
                    'activation_code_preset_id' => $preset->id,
                    'generated_by_agent_id' => $agent->id,
                    'status' => 'active',
                    'hotcoin_cost_at_generation' => $preset->hotcoin_cost,
                    'duration_days_at_generation' => $preset->duration_days,
                    'generated_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            ActivationCode::insert($generatedCodes);

            $agent->hotcoin_balance -= $totalCost;
            $agent->save();

            HotcoinTransaction::create([
                'user_id' => $agent->id,
                'type' => 'debit',
                'amount' => $totalCost,
                'description' => "Generated {$quantity} x {$preset->name} codes",
                'related_activation_code_id' => null, // This can be improved to link to all generated codes
            ]);

            // Profit sharing
            $upline = $agent->uplineAgent;
            $cost_for_upline = $totalCost;

            while ($upline) {
                $upline_preset_cost = $upline->getCostForPreset($preset);
                $profit = $cost_for_upline - ($upline_preset_cost * $quantity);

                if ($profit > 0) {
                    $upline->hotcoin_balance += $profit;
                    $upline->total_profit_earned += $profit;
                    $upline->save();

                    HotcoinTransaction::create([
                        'user_id' => $upline->id,
                        'type' => 'credit',
                        'amount' => $profit,
                        'description' => "Profit from downline agent {$agent->name} generating {$quantity} x {$preset->name} codes",
                        'related_activation_code_id' => null,
                    ]);

                    $monthlyProfit = AgentMonthlyProfit::firstOrCreate([
                        'agent_id' => $upline->id,
                        'month_year' => now()->format('Y-m'),
                    ]);
                    $monthlyProfit->profit_amount += $profit;
                    $monthlyProfit->save();
                }

                $cost_for_upline = $upline_preset_cost * $quantity;
                $upline = $upline->uplineAgent;
            }

            DB::commit();

            return redirect()->route('license.list')->with('success', 'Successfully generated '.$quantity.' license codes.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'An error occurred while generating the codes. Please try again.'])->withInput();
        }
    }
}
