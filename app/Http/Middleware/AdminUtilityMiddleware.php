<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminUtilityMiddleware
{
    protected $idss = [];
    protected $ids_list_all = [];
    protected $ids_list_all_tt = [];
    protected $lowers = [];
    
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Store utility methods in the request for controllers to use
        $request->attributes->set('utility', $this);
        
        return $next($request);
    }

    /**
     * Get parent ID
     */
    public function getParentId($id)
    {
        // Implement parent ID retrieval logic
        return $id;
    }

    /**
     * Get lower IDs
     */
    public function getLowerIdss($id)
    {
        $this->idss[] = $id;
        return $this->idss;
    }

    /**
     * Get lower IDs by all
     */
    public function getLowerIdsByAll($id, $level_id)
    {
        // Implement lower IDs by all retrieval logic
        if ($level_id > 0) {
            $this->ids_list_all[] = $id;
        } else {
            $this->ids_list_all_tt[] = $id;
        }
        
        return [
            'ids_list_all' => $this->ids_list_all, 
            'ids_list_all_tt' => $this->ids_list_all_tt
        ];
    }

    /**
     * Get lower by IDs
     */
    public function getLowerByIds($id)
    {
        $this->lowers[] = $id;
        return $this->lowers;
    }

    /**
     * Get downline
     */
    public function get_downline($users, $id, $level_id)
    {
        // Implement downline retrieval logic
        return [$id];
    }

    /**
     * Check if request is AJAX
     */
    public function isAjax(Request $request)
    {
        if (!$request->ajax()) {
            abort(403, 'Forbidden');
        }
    }
    
    /**
     * Get retail data
     */
    public function getRetail($parent_id)
    {
        // Implement retail data retrieval logic
        return [];
    }
    
    /**
     * Get level cost
     */
    public function getLevelCost($parent_id)
    {
        // Implement level cost retrieval logic
        return [];
    }
} 