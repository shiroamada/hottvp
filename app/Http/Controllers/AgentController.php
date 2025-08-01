<?php

namespace App\Http\Controllers;

use App\Models\User; // Assuming agents are users

class AgentController extends Controller
{
    /**
     * Display a listing of the agents.
     */
    public function list()
    {
        // For now, we'll return an empty array of agents.
        // In the future, you would fetch this from the database.
        // e.g., $agents = User::where('is_agent', true)->get();
        $agents = [];

        return view('agent.list', compact('agents'));
    }

    /**
     * Show the form for creating a new agent.
     */
    public function create()
    {
        return view('agent.create');
    }
}
