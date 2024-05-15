<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Checks if the user is logged in
        if ($request->user()) {
            // Checks if the user is a doctor
            if ($request->user()->isDoctor()) {
                // If yes, allow the request to the following route
                return $next($request);
            }
        }

        // If the user is not logged in or is not a doctor, returns an unauthorized response
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
