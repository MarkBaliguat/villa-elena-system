<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $userRole = Auth::user()->role;

        // Check kung allowed ang user role
        if (!in_array($userRole, $roles)) {
            // Redirect based sa role
            if ($userRole === 'guest') {
                return redirect('/');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}