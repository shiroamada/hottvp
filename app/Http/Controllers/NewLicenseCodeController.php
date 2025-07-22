<?php

namespace App\Http\Controllers;

use App\Models\Admin\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assort;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewLicenseCodeController extends Controller
{
    public function create(): View
    {
        $user = Auth::guard('admin')->user();
        $assort_levels = Assort::query()
            ->join('assort_levels', 'assort_levels.assort_id', '=', 'assorts.id')
            ->where('assort_levels.level_id', $user->level_id)
            ->select('assorts.id', 'assorts.assort_name', 'assort_levels.money')
            ->get();

        return view('license.generate', compact('assort_levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'assort_id' => 'required|exists:assorts,id',
            'number' => 'required|integer|min:1|max:100',
            'remark' => 'nullable|string|max:255',
        ]);

        $assort = Assort::find($request->assort_id);
        $quantity = $request->number;
        $user = Auth::guard('admin')->user();

        $assort_level = DB::table('assort_levels')
            ->where('level_id', $user->level_id)
            ->where('assort_id', $assort->id)
            ->first();

        if (!$assort_level) {
            return back()->withErrors(['error' => 'Cost for this code type is not defined.'])->withInput();
        }

        $totalCost = $assort_level->money * $quantity;

        if ($user->balance < $totalCost) {
            return back()->withErrors(['hotcoin' => 'Insufficient HOTCOIN balance.'])->withInput();
        }

        DB::beginTransaction();
        try {
            $generatedCodes = [];
            for ($i = 0; $i < $quantity; $i++) {
                $generatedCodes[] = [
                    'assort_id' => $assort->id,
                    'user_id' => $user->id,
                    'auth_code' => strtoupper(Str::random(12)),
                    'num' => 1,
                    'type' => $user->type,
                    'remark' => $request->remark,
                    'is_try' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('auth_codes')->insert($generatedCodes);

            $user->balance -= $totalCost;
            $user->save();

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

            if ($user->pid > 1) {
                $this->distributeProfit($user, $assort, $totalCost, $quantity);
            }

            DB::commit();

            return redirect()->route('license.list')->with('success', 'Successfully generated ' . $quantity . ' license codes.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An error occurred while generating the codes. Please try again.'])->withInput();
        }
    }

    private function distributeProfit($user, $assort, $totalCost, $quantity)
    {
        $upline = AdminUser::find($user->pid);

        if ($upline) {
            $upline_assort_level = DB::table('assort_levels')
                ->where('level_id', $upline->level_id)
                ->where('assort_id', $assort->id)
                ->first();

            if ($upline_assort_level) {
                $upline_cost = $upline_assort_level->money * $quantity;
                $profit = $totalCost - $upline_cost;

                if ($profit > 0) {
                    $upline->balance += $profit;
                    $upline->save();

                    DB::table('huobis')->insert([
                        'user_id' => $upline->id,
                        'money' => $profit,
                        'status' => 0,
                        'type' => 1,
                        'number' => $quantity,
                        'event' => $user->name . ' ' . trans('general.generate') . ' ' . $assort->assort_name,
                        'own_id' => $user->id,
                        'create_id' => $user->id,
                        'assort_id' => $assort->id,
                        'user_account' => $user->account,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}