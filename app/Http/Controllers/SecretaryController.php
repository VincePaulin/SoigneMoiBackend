<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SecretaryController extends Controller
{
    /**
     * Handle a login request to the application for a secretary.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Attempt to find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if the user is a secretary
        if (!$user->isSecretary()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        // Generate an API token for the user
        $token = $user->createToken('secretary-token')->plainTextToken;

        // Return a success response with the token
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }
}
