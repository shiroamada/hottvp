<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ActivationCode;
use App\Models\ActivationCodePreset;
use App\Http\Requests\LicenseCodeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LicenseCodeController extends Controller
{
    /**
     * Display the license code list view.
     */
    public function index(Request $request): View
    {
        $licenseCodes = [
            (object)['id' => 100091, 'code' => 'THSUWUPUCKGR', 'type' => '90-day license code', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2025-08-21', 'created_time' => '2025-05-21 23:39:11'],
            (object)['id' => 98276, 'code' => 'LSXZIEKOCWZJ', 'type' => '365-day license code', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2025-07-31', 'created_time' => '2024-07-31 16:11:52'],
            (object)['id' => 96977, 'code' => 'RSXURFQCORPA', 'type' => '7-day license code', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2024-04-03', 'created_time' => '2024-03-27 14:39:49'],
            (object)['id' => 96781, 'code' => 'EDZOKOLLYDQC', 'type' => '7-day license code', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2024-03-16', 'created_time' => '2024-03-09 20:06:02'],
            (object)['id' => 96197, 'code' => 'WIKVPRZWSLWT', 'type' => '365-day license code', 'status' => 'Used', 'remarks' => '', 'expired_date' => '2025-02-07', 'created_time' => '2024-02-08 18:48:39'],
            (object)['id' => 70881, 'code' => 'HEBSXIBEKVZM', 'type' => '365-day license code', 'status' => 'Used', 'remarks' => '道洪', 'expired_date' => '2023-08-15', 'created_time' => '2022-08-15 15:22:06'],
            (object)['id' => 29719, 'code' => 'HQHKJUJAWTMP', 'type' => '365-day license code', 'status' => 'Used', 'remarks' => '道洪', 'expired_date' => '2022-08-08', 'created_time' => '2021-08-08 14:52:39'],
        ];
        return view('license.list', compact('licenseCodes'));
    }

    /**
     * Show the license code generation form.
     */
    public function create(): View
    {
        $presets = ActivationCodePreset::where('is_active', true)->get();
        return view('license.generate', compact('presets'));
    }

    /**
     * Handle license code generation.
     */
    public function store(LicenseCodeRequest $request)
    {
        $user = Auth::user();
        $preset = ActivationCodePreset::findOrFail($request->activation_code_preset_id);
        $quantity = $request->input('quantity');
        $remarks = $request->input('remarks');
        $totalCost = $preset->hotcoin_cost * $quantity;

        // Check if user has enough HotCoin
        if ($user->hotcoin_balance < $totalCost) {
            return back()->withErrors(['hotcoin_balance' => __('Insufficient HOTCOIN balance.')]);
        }

        DB::transaction(function () use ($user, $preset, $quantity, $remarks, $totalCost) {
            // Deduct HotCoin
            $user->decrement('hotcoin_balance', $totalCost);

            // Generate codes
            for ($i = 0; $i < $quantity; $i++) {
                ActivationCode::create([
                    'code' => strtoupper(uniqid(bin2hex(random_bytes(3)))),
                    'activation_code_preset_id' => $preset->id,
                    'generated_by_agent_id' => $user->id,
                    'status' => 'available',
                    'hotcoin_cost_at_generation' => $preset->hotcoin_cost,
                    'duration_days_at_generation' => $preset->duration_days,
                    'generated_at' => now(),
                    'expires_at' => now()->addDays($preset->duration_days),
                    // Optionally add remarks if you have a column for it
                ]);
            }
        });

        return redirect()->route('license.list')->with('success', __('License codes generated successfully.'));
    }
}
