<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->guard('admin')->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Check if user has admin permissions
        // This is a placeholder - implement your actual authorization logic
        // based on your application's permission system
        
        return $next($request);
    }
} 