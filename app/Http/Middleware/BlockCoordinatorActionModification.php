<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlockCoordinatorActionModification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (Auth::check() && Auth::user()->role_id == 2) {
        //     abort(403, 'Accès interdit aux coordinateurs pour cette action.');
        // }
        dd('access denied');
        return $next($request);
    }
}
