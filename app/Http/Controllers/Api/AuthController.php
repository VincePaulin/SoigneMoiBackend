<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validation of incoming data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Errors
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid email or password',
                'errors' => $validator->errors(),
            ], 422);
        }

        // User login attempt
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Recover authenticated user
            $user = Auth::user();
            // Generate a JWT token for the user
            $token = $user->createToken('AuthToken')->accessToken;
            // Return an answer with the token
            return response()->json([
                'message' => 'Logged in successfully',
                'user' => $user,
                'access_token' => $token,
            ], 200);
        } else {
            // Return error response if connection fails
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }
    }

    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validation of incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'address' => 'required|string',
        ]);
    
        // Errors
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Registration failed',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // Create a new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->address = $request->address;
        $user->save();
    
        // After registration, automatically connect user
        Auth::login($user);
    
        // Generate a JWT token for the user
        $token = $user->createToken('AuthToken')->accessToken;
    
        // Return a success response with the access token
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'access_token' => $token,
        ], 201);
    }
}
