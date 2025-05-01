<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role) {
            switch ($user->role->nom) {
                case 'administrateur':
                    return redirect('/dashboard/admin');
                case 'coordinateur_axe':
                    return redirect('/dashboard/coordinateur');
                case 'responsable_action':
                    return redirect('/dashboard/responsable');
                case 'consultant':
                    return redirect('/dashboard/consultant');
            }
        }

        return $next($request);
    }
}
