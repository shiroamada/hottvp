<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait AdminControllerTrait
{
    protected $formNames = [];
    protected $agent = 3;
    
    /**
     * Get utility middleware service from the request
     */
    protected function utility(Request $request = null)
    {
        $request = $request ?? request();
        return $request->attributes->get('utility');
    }

    /**
     * Email sending utility method - delegates to AdminControllerMiddleware
     */
    public function send($name, $to, $subject)
    {
        return app(\App\Http\Middleware\AdminControllerMiddleware::class)->sendEmail($name, $to, $subject);
    }

    /**
     * Check if request is AJAX - delegates to AdminUtilityMiddleware
     */
    protected function isAjax(Request $request)
    {
        return $this->utility($request)->isAjax($request);
    }

    /**
     * Get parent ID - delegates to AdminUtilityMiddleware
     */
    protected function getParentId($id)
    {
        return $this->utility()->getParentId($id);
    }

    /**
     * Get lower IDs - delegates to AdminUtilityMiddleware
     */
    protected function getLowerIdss($id)
    {
        return $this->utility()->getLowerIdss($id);
    }

    /**
     * Get lower IDs by all - delegates to AdminUtilityMiddleware
     */
    protected function getLowerIdsByAll($id, $level_id)
    {
        return $this->utility()->getLowerIdsByAll($id, $level_id);
    }

    /**
     * Get lower by IDs - delegates to AdminUtilityMiddleware
     */
    protected function getLowerByIds($id)
    {
        return $this->utility()->getLowerByIds($id);
    }

    /**
     * Get downline - delegates to AdminUtilityMiddleware
     */
    protected function get_downline($users, $id, $level_id)
    {
        return $this->utility()->get_downline($users, $id, $level_id);
    }
    
    /**
     * Get retail data - delegates to AdminUtilityMiddleware
     */
    protected function getRetail($parent_id)
    {
        return $this->utility()->getRetail($parent_id);
    }
    
    /**
     * Get level cost - delegates to AdminUtilityMiddleware
     */
    protected function getLevelCost($parent_id)
    {
        return $this->utility()->getLevelCost($parent_id);
    }
} 