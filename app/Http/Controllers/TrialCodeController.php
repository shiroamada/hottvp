<?php

namespace App\Http\Controllers;

use App\Models\AuthCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Artisan;

class TrialCodeController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::guard('admin')->user();
        $query = AuthCode::where('user_id', $user->id)
            ->where('is_try', 2) // Filter for trial codes
            ->with('assort');

        // Apply filters
        if ($request->filled('auth_code')) {
            $query->where('auth_code', 'like', '%' . $request->input('auth_code') . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->input('date_range'));
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }

        $codes = $query->latest()->paginate(20)->withQueryString();

        return view('trial.list', compact('codes'));
    }

    public function create(): View
    {
        $availableTrialCodes = Auth::guard('admin')->user()->try_num;

        return view('trial.generate', compact('availableTrialCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|integer|min:1|max:100',
            'remark' => 'nullable|string|max:255',
        ]);

        $user = Auth::guard('admin')->user();
        $quantity = $request->number;

        if ($quantity > $user->try_num) {
            return back()->withErrors(['error' => 'You do not have enough trial codes available.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $generatedCodes = [];
            // Trial codes are typically for a short duration, e.g., 1 day.
            // The old project hardcoded assort_id = 5 for trial codes. We will do the same for now.
            $expiryDate = now()->addDays(1);

            for ($i = 0; $i < $quantity; $i++) {
                $generatedCodes[] = [
                    'assort_id' => 5, // Hardcoded as per old project logic
                    'user_id' => $user->id,
                    'auth_code' => strtoupper(Str::random(12)),
                    'num' => 1,
                    'type' => $user->type,
                    'remark' => $request->remark,
                    'is_try' => 2, // 2 for trial code
                    'expire_at' => $expiryDate,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('auth_codes')->insert($generatedCodes);

            // Decrement the user's try_num
            $user->decrement('try_num', $quantity);

            DB::commit();

            return redirect()->route('trial.list')->with('success', 'Successfully generated '.$quantity.' trial codes.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while generating the codes. Please try again.'])->withInput();
        }
    }

    /**
     * Run the metvbox:refresh-all-codes artisan command
     * This refreshes all codes created after 2026 with expire_at = NULL
     */
    public function refreshAllArtisan(Request $request)
    {
        try {
            Log::info('Running metvbox:refresh-all-codes artisan command for trial codes');

            // Run the artisan command
            $exitCode = Artisan::call('metvbox:refresh-all-codes');

            if ($exitCode === 0) {
                // Success
                return response()->json([
                    'success' => true,
                    'message' => 'All trial codes refreshed successfully from MetVBox API',
                ]);
            } else {
                // Failure
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to run refresh command',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error running refresh artisan command: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}