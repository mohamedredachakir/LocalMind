<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is logged in and if their role is in the allowed list
        if ($request->user() && in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // If not, redirect or abort
        abort(403, 'Unauthorized action.');
    }
}
