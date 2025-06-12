<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CostingController extends Controller
{
    /**
     * Display the costing configuration list.
     */
    public function index()
    {
        $costingData = [
            [
                'id' => 1,
                'type' => '1-day license code',
                'retail_price' => '1.00',
                'your_cost' => '1.00',
                'diamond_agent_cost' => '1.00',
                'gold_agent_cost' => '1.00',
                'silver_agent_cost' => '1.00',
                'bronze_agent_cost' => '1.00',
                'customized_minimum_cost' => '1.00'
            ],
            [
                'id' => 2,
                'type' => '7-day license code',
                'retail_price' => '10.00',
                'your_cost' => '3.00',
                'diamond_agent_cost' => '4.00',
                'gold_agent_cost' => '5.00',
                'silver_agent_cost' => '6.00',
                'bronze_agent_cost' => '7.00',
                'customized_minimum_cost' => '8.00'
            ],
            [
                'id' => 3,
                'type' => '30-day license code',
                'retail_price' => '25.00',
                'your_cost' => '7.50',
                'diamond_agent_cost' => '11.25',
                'gold_agent_cost' => '12.50',
                'silver_agent_cost' => '15.00',
                'bronze_agent_cost' => '18.00',
                'customized_minimum_cost' => '19.00'
            ],
            [
                'id' => 4,
                'type' => '90-day license code',
                'retail_price' => '50.00',
                'your_cost' => '15.00',
                'diamond_agent_cost' => '22.50',
                'gold_agent_cost' => '25.00',
                'silver_agent_cost' => '30.00',
                'bronze_agent_cost' => '36.00',
                'customized_minimum_cost' => '37.00'
            ],
            [
                'id' => 5,
                'type' => '180-day license code',
                'retail_price' => '95.00',
                'your_cost' => '30.00',
                'diamond_agent_cost' => '45.00',
                'gold_agent_cost' => '50.00',
                'silver_agent_cost' => '60.00',
                'bronze_agent_cost' => '72.00',
                'customized_minimum_cost' => '73.00'
            ],
            [
                'id' => 6,
                'type' => '365-day license code',
                'retail_price' => '180.00',
                'your_cost' => '60.00',
                'diamond_agent_cost' => '81.00',
                'gold_agent_cost' => '90.00',
                'silver_agent_cost' => '108.00',
                'bronze_agent_cost' => '130.00',
                'customized_minimum_cost' => '131.00'
            ]
        ];

        return view('costing.index', ['costingData' => $costingData]);
    }

    /**
     * Update the costing configuration.
     */
    public function update(Request $request)
    {
        // Get the ID from the request body
        $id = $request->input('id');
        
        // In a real application, you would validate the input and update the database
        // For example:
        // $validated = $request->validate([
        //     'id' => 'required|integer',
        //     'retail_price' => 'required|numeric',
        //     'your_cost' => 'required|numeric',
        //     'diamond_agent_cost' => 'required|numeric',
        //     'gold_agent_cost' => 'required|numeric',
        //     'silver_agent_cost' => 'required|numeric',
        //     'bronze_agent_cost' => 'required|numeric',
        //     'customized_minimum_cost' => 'required|numeric',
        // ]);
        // 
        // $costing = Costing::find($id);
        // $costing->update($validated);
        
        return response()->json(['success' => true]);
    }
}
