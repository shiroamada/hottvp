<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Repository\Admin\MenuRepository;
use App\Repository\Admin\EntityRepository;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerMiddleware
{
    protected $breadcrumb = [];
    
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for AJAX requests
        // if ($request->ajax()) {
        //     return $next($request);
        // }

        // // Breadcrumb navigation
        // $this->breadcrumb[] = ['title' => '首页', 'url' => route('admin::index')];
        // View::share('breadcrumb', $this->breadcrumb);

        // // Menu generation
        // $route = $request->route();
        // if (is_null($route)) {
        //     return $next($request);
        // }
        
        // $routeName = $request->route()->getName();
        
        // // Get current group
        // $group = MenuRepository::getGroup($routeName);
        // View::share([
        //     'light_cur_route' => $routeName, 
        //     'light_cur_group' => $group
        // ]);
        
        // if (is_null($currentRootMenu = MenuRepository::root($routeName))) {
        //     View::share('light_menu', []);
        // } else {
        //     View::share('light_menu', $currentRootMenu);
        //     if ($routeName !== 'admin::aggregation' && $currentRootMenu['route'] === 'admin::aggregation') {
        //         View::share('autoMenu', EntityRepository::systemMenu());
        //     }
        // }

        return $next($request);
    }

    /**
     * Email sending utility
     */
    public function sendEmail($name, $to, $subject): void
    {
        Mail::raw($name, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });
    }
} 