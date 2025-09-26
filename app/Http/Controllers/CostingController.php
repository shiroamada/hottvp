<?php

namespace App\Http\Controllers;

use App\Models\Admin\AdminUser;
use App\Models\Assort;
use App\Models\Defined;
use App\Models\Admin\Retail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CostingController extends Controller
{
    /**
     * Display the costing configuration list.
     */
    public function index()
    {
        $user = Auth::guard('admin')->user();
        $parent_id = $this->getParentId($user->id);

        $assorts = Assort::orderBy('duration', 'ASC')->get();

        $costingData = [];

        foreach ($assorts as $assort) {
            // Retail Price
            $retail_price_obj = Retail::where('user_id', $parent_id)->where('assort_id', $assort->id)->first();
            $retail_price = $retail_price_obj ? $retail_price_obj->money : 'N/A';

            // Your Cost (from assort_levels for current user's level)
            $your_cost_obj = DB::table('assort_levels')
                ->where('user_id', $parent_id)
                ->where('assort_id', $assort->id)
                ->where('level_id', $user->level_id)
                ->first();
            $your_cost = $your_cost_obj ? $your_cost_obj->money : 'N/A';

            // Other agent costs (from assort_levels for specific level_ids)
            $diamond_agent_cost_obj = DB::table('assort_levels')
                ->where('user_id', $parent_id)->where('assort_id', $assort->id)->where('level_id', 4)->first();
            $diamond_agent_cost = $diamond_agent_cost_obj ? $diamond_agent_cost_obj->money : 'N/A';

            $gold_agent_cost_obj = DB::table('assort_levels')
                ->where('user_id', $parent_id)->where('assort_id', $assort->id)->where('level_id', 5)->first();
            $gold_agent_cost = $gold_agent_cost_obj ? $gold_agent_cost_obj->money : 'N/A';

            $silver_agent_cost_obj = DB::table('assort_levels')
                ->where('user_id', $parent_id)->where('assort_id', $assort->id)->where('level_id', 6)->first();
            $silver_agent_cost = $silver_agent_cost_obj ? $silver_agent_cost_obj->money : 'N/A';

            $bronze_agent_cost_obj = DB::table('assort_levels')
                ->where('user_id', $parent_id)->where('assort_id', $assort->id)->where('level_id', 7)->first();
            $bronze_agent_cost = $bronze_agent_cost_obj ? $bronze_agent_cost_obj->money : 'N/A';

            // Customized Minimum Cost (from defined_assort_levels if level_id is 8, else from assort_levels)
            if ($user->level_id == 8) {
                $customized_minimum_cost_obj = Defined::where('user_id', $parent_id)->where('assort_id', $assort->id)->first();
            } else {
                $customized_minimum_cost_obj = DB::table('assort_levels')
                    ->where('user_id', $parent_id)->where('assort_id', $assort->id)->where('level_id', 8)->first();
            }
            $customized_minimum_cost = $customized_minimum_cost_obj ? $customized_minimum_cost_obj->money : 'N/A';

            $costingData[] = [
                'id' => $assort->id,
                'type' => $assort->assort_name,
                'retail_price' => $retail_price,
                'your_cost' => $your_cost,
                'diamond_agent_cost' => $diamond_agent_cost,
                'gold_agent_cost' => $gold_agent_cost,
                'silver_agent_cost' => $silver_agent_cost,
                'bronze_agent_cost' => $bronze_agent_cost,
                'customized_minimum_cost' => $customized_minimum_cost,
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
        $parent_id = $this->getParentId($user->id);

        $assort_id = $request->input('assort_id');
        $retail_price = $request->input('retail_price');
        $your_cost = $request->input('your_cost');
        $diamond_agent_cost = $request->input('diamond_agent_cost');
        $gold_agent_cost = $request->input('gold_agent_cost');
        $silver_agent_cost = $request->input('silver_agent_cost');
        $bronze_agent_cost = $request->input('bronze_agent_cost');
        $customized_minimum_cost = $request->input('customized_minimum_cost');

        // --- Validation Logic ---

        // 1. Numeric Check
        $all_costs = $request->only([
            'retail_price', 'your_cost', 'diamond_agent_cost', 'gold_agent_cost',
            'silver_agent_cost', 'bronze_agent_cost', 'customized_minimum_cost'
        ]);
        foreach ($all_costs as $key => $cost) {
            if (!is_numeric($cost)) {
                return response()->json(['success' => false, 'message' => "Price for " . str_replace('_', ' ', $key) . " must be numeric."]);
            }
        }

        // 2. Price ordering check (higher tier agent should have lower or equal cost)
        $agent_costs_for_ordering = [
            (float)$diamond_agent_cost,
            (float)$gold_agent_cost,
            (float)$silver_agent_cost,
            (float)$bronze_agent_cost,
        ];
        for ($i = 0; $i < count($agent_costs_for_ordering) - 1; $i++) {
            if ($agent_costs_for_ordering[$i] > $agent_costs_for_ordering[$i+1]) {
                return response()->json(['success' => false, 'message' => 'Invalid agent cost order: higher tier agents must have a lower or equal cost.']);
            }
        }

        // Agent costs should be less than or equal to retail price
        if ((float)$bronze_agent_cost > (float)$retail_price) {
            return response()->json(['success' => false, 'message' => 'Agent costs cannot be higher than the retail price.']);
        }

        // Customized minimum cost should be >= the best agent price (diamond)
        if ((float)$customized_minimum_cost < (float)$diamond_agent_cost) {
            return response()->json(['success' => false, 'message' => 'Customized minimum cost cannot be lower than the Diamond Agent cost.']);
        }

        // Custom Price vs Existing Custom Users
        $min_defined_cost = Defined::where('generation_id', $parent_id)
                                    ->where('assort_id', $assort_id)
                                    ->min('money');
        if ($min_defined_cost !== null && (float)$customized_minimum_cost > $min_defined_cost) {
            return response()->json(['success' => false, 'message' => 'Customized minimum cost cannot be higher than a price already assigned to an existing custom user.']);
        }

        // 3. Custom Price vs Retail Price
        $assort = Assort::find($assort_id);
        if ($assort) {
            if ($assort->id < 5) {
                if ((float)$customized_minimum_cost >= (float)$retail_price) {
                    return response()->json(['success' => false, 'message' => 'Customized minimum cost must be strictly less than retail price for this item.']);
                }
            } else {
                if ((float)$customized_minimum_cost > (float)$retail_price) {
                    return response()->json(['success' => false, 'message' => 'Customized minimum cost cannot be greater than retail price for this item.']);
                }
            }
        }

        // 4. Manager's own cost check (for National Agents, level 3)
        if ($user->level_id == 3) {
            if ((float)$diamond_agent_cost < (float)$your_cost) {
                return response()->json(['success' => false, 'message' => 'You cannot set an agent cost that is lower than your own cost.']);
            }
        }

        // --- End of Validation Logic ---

        DB::beginTransaction();
        try {
            // Update retail price in defined_retail table
            Retail::updateOrInsert(
                ['user_id' => $parent_id, 'assort_id' => $assort_id],
                ['money' => $retail_price, 'updated_at' => now()]
            );

            // Update your cost in assort_levels table for current user's level
            DB::table('assort_levels')
                ->updateOrInsert(
                    ['user_id' => $parent_id, 'assort_id' => $assort_id, 'level_id' => $user->level_id],
                    ['money' => $your_cost, 'updated_at' => now()]
                );

            // Update other agent costs in assort_levels table
            DB::table('assort_levels')
                ->updateOrInsert(
                    ['user_id' => $parent_id, 'assort_id' => $assort_id, 'level_id' => 4],
                    ['money' => $diamond_agent_cost, 'updated_at' => now()]
                );
            DB::table('assort_levels')
                ->updateOrInsert(
                    ['user_id' => $parent_id, 'assort_id' => $assort_id, 'level_id' => 5],
                    ['money' => $gold_agent_cost, 'updated_at' => now()]
                );
            DB::table('assort_levels')
                ->updateOrInsert(
                    ['user_id' => $parent_id, 'assort_id' => $assort_id, 'level_id' => 6],
                    ['money' => $silver_agent_cost, 'updated_at' => now()]
                );
            DB::table('assort_levels')
                ->updateOrInsert(
                    ['user_id' => $parent_id, 'assort_id' => $assort_id, 'level_id' => 7],
                    ['money' => $bronze_agent_cost, 'updated_at' => now()]
                );

            // Update customized minimum cost
            if ($user->level_id == 8) {
                Defined::updateOrInsert(
                    ['user_id' => $parent_id, 'assort_id' => $assort_id],
                    ['money' => $customized_minimum_cost, 'updated_at' => now()]
                );
            } else {
                DB::table('assort_levels')
                    ->updateOrInsert(
                        ['user_id' => $parent_id, 'assort_id' => $assort_id, 'level_id' => 8],
                        ['money' => $customized_minimum_cost, 'updated_at' => now()]
                    );
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Costing updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Error updating costing: '.$e->getMessage()]);
        }
    }

    /**
     * Helper method to get the top-level parent ID for a given user.
     * This is based on the logic found in the old project's controllers.
     */
    private function getParentId(int $userId): int
    {
        $user = AdminUser::find($userId);
        if (! $user) {
            return 0; // Or throw an exception, depending on desired error handling
        }

        // If the user has no parent (pid is 0 or 1, assuming 1 is the root admin)
        // or if they are already the top-level agent (level_id 3, assuming 3 is the top level)
        // then they are their own parent for costing purposes.
        if ($user->pid <= 1 || $user->level_id == 3) {
            return $user->id;
        }

        // Traverse up the hierarchy until the top-level parent is found
        $current_user = $user;
        while ($current_user->pid > 1 && $current_user->level_id != 3) {
            $parent = AdminUser::find($current_user->pid);
            if (! $parent) {
                break; // Parent not found, stop traversing
            }
            $current_user = $parent;
        }

        return $current_user->id;
    }
}