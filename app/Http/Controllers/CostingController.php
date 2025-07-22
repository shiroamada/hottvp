<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Assort;

class CostingController extends Controller
{
    /**
     * Display the costing configuration list.
     */
    public function index()
    {
        $user = Auth::guard('admin')->user();
        $parent_id = $user->id; // Assuming current user is the parent for now, will refine later

        $assorts = Assort::orderBy('duration', 'ASC')->get();

        $assort_levels = DB::table('assort_levels')
            ->where('user_id', $parent_id)
            ->get();

        $retail_prices = DB::table('defined_retail')
            ->where('user_id', $parent_id)
            ->get();

        $defined_assort_levels = DB::table('defined_assort_levels')
            ->where('user_id', $parent_id)
            ->get();

        $costingData = [];

        foreach ($assorts as $assort) {
            $your_cost = $assort_levels->where('assort_id', $assort->id)->where('level_id', $user->level_id)->first();
            $retail_price = $retail_prices->where('assort_id', $assort->id)->first();
            $customized_minimum_cost = $defined_assort_levels->where('assort_id', $assort->id)->first();

            // Placeholder for other agent costs - these would typically come from assort_levels for different level_ids
            $diamond_agent_cost = $assort_levels->where('assort_id', $assort->id)->where('level_id', 4)->first(); // Assuming level_id 4 is Diamond
            $gold_agent_cost = $assort_levels->where('assort_id', $assort->id)->where('level_id', 5)->first(); // Assuming level_id 5 is Gold
            $silver_agent_cost = $assort_levels->where('assort_id', $assort->id)->where('level_id', 6)->first(); // Assuming level_id 6 is Silver
            $bronze_agent_cost = $assort_levels->where('assort_id', $assort->id)->where('level_id', 7)->first(); // Assuming level_id 7 is Bronze

            $costingData[] = [
                'id' => $assort->id,
                'type' => $assort->assort_name,
                'retail_price' => $retail_price ? $retail_price->money : 'N/A',
                'your_cost' => $your_cost ? $your_cost->money : 'N/A',
                'diamond_agent_cost' => $diamond_agent_cost ? $diamond_agent_cost->money : 'N/A',
                'gold_agent_cost' => $gold_agent_cost ? $gold_agent_cost->money : 'N/A',
                'silver_agent_cost' => $silver_agent_cost ? $silver_agent_cost->money : 'N/A',
                'bronze_agent_cost' => $bronze_agent_cost ? $bronze_agent_cost->money : 'N/A',
                'customized_minimum_cost' => $customized_minimum_cost ? $customized_minimum_cost->money : 'N/A'
            ];
        }

        return view('costing.index', ['costingData' => $costingData]);
    }

    /**
     * Update the costing configuration.
     */
    public function update(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $assort_id = $request->input('assort_id');
        $retail_price = $request->input('retail_price');
        $your_cost = $request->input('your_cost');
        $diamond_agent_cost = $request->input('diamond_agent_cost');
        $gold_agent_cost = $request->input('gold_agent_cost');
        $silver_agent_cost = $request->input('silver_agent_cost');
        $bronze_agent_cost = $request->input('bronze_agent_cost');
        $customized_minimum_cost = $request->input('customized_minimum_cost');

        DB::beginTransaction();
        try {
            // Update retail price
            DB::table('defined_retail')
                ->updateOrInsert(
                    ['user_id' => $user->id, 'assort_id' => $assort_id],
                    ['money' => $retail_price, 'updated_at' => now()]
                );

            // Update your cost (assort_levels for current user's level)
            DB::table('assort_levels')
                ->updateOrInsert(
                    ['user_id' => $user->id, 'assort_id' => $assort_id, 'level_id' => $user->level_id],
                    ['money' => $your_cost, 'updated_at' => now()]
                );

            // Update other agent costs (assuming level_id 4-7 for Diamond, Gold, Silver, Bronze)
            DB::table('assort_levels')
                ->updateOrInsert(
                    ['user_id' => $user->id, 'assort_id' => $assort_id, 'level_id' => 4],
                    ['money' => $diamond_agent_cost, 'updated_at' => now()]
                );
            DB::table('assort_levels')
                ->updateOrInsert(
                    ['user_id' => $user->id, 'assort_id' => $assort_id, 'level_id' => 5],
                    ['money' => $gold_agent_cost, 'updated_at' => now()]
                );
            DB::table('assort_levels')
                ->updateOrInsert(
                    ['user_id' => $user->id, 'assort_id' => $assort_id, 'level_id' => 6],
                    ['money' => $silver_agent_cost, 'updated_at' => now()]
                );
            DB::table('assort_levels')
                ->updateOrInsert(
                    ['user_id' => $user->id, 'assort_id' => $assort_id, 'level_id' => 7],
                    ['money' => $bronze_agent_cost, 'updated_at' => now()]
                );

            // Update customized minimum cost
            DB::table('defined_assort_levels')
                ->updateOrInsert(
                    ['user_id' => $user->id, 'assort_id' => $assort_id],
                    ['money' => $customized_minimum_cost, 'updated_at' => now()]
                );

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Costing updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error updating costing: ' . $e->getMessage()]);
        }
    }
}
