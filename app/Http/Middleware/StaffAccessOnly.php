<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffAccessOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedRoles = ['staff', 'manager', 'admin'];
        
        if (!in_array($request->user()->role, $allowedRoles)) {
            return redirect()->route('client-portal')->with('error', 'Access denied. Staff only area.');
        }

        return $next($request);
    }
}
