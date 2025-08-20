<?php

namespace App\Http\Controllers;

use App\Models\Admin\AdminUser;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the agents.
     */
    public function list(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $agents = AdminUser::paginate($perPage);

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