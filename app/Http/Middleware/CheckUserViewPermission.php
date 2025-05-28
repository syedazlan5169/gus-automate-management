<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserViewPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $userToView = $request->route('user');
        $currentUser = auth()->user();

        // Admin can view all roles
        if ($currentUser->role === 'admin') {
            return $next($request);
        }

        // Manager can only view customer and manager roles
        if ($currentUser->role === 'manager') {
            if (!in_array($userToView->role, ['customer', 'manager'])) {
                abort(403, "You don't have permission to view this user's details.");
            }
            return $next($request);
        }

        // Finance can only view customer and finance roles
        if ($currentUser->role === 'finance') {
            if (!in_array($userToView->role, ['customer', 'finance'])) {
                abort(403, "You don't have permission to view this user's details.");
            }
            return $next($request);
        }

        // Customer can only view their own profile
        if ($currentUser->role === 'customer') {
            if ($currentUser->id !== $userToView->id) {
                abort(403, "You don't have permission to view this user's details.");
            }
            return $next($request);
        }

        abort(403, "You don't have permission to view this user's details.");
    }
} 