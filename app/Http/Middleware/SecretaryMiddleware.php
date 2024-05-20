<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecretaryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est authentifié
        if (Auth::check()) {
            // Vérifie si l'utilisateur a le rôle d'administrateur
            if (Auth::user()->isSecretary()) {
                // Autorise la requête à continuer
                return $next($request);
            }
        }

        // Retourne une réponse d'erreur non autorisée
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
