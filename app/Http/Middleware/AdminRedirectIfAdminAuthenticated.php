<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRedirectIfAdminAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }


    /**
     * Get the authentication guard to be used by the middleware.
     *
     * @return string
     */
    protected function guard()
    {
        return 'admin';
    }
}