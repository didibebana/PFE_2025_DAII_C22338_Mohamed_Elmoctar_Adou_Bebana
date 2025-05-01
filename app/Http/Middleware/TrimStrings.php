<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrimStrings
{
    /**
     * Traite la requête et applique le trim sur les entrées de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Applique le trim sur les champs de la requête
        $request->merge(array_map('trim', $request->all()));

        return $next($request);
    }
}
