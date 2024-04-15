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
        ], [
            'email.unique' => 'Cet email est déjà utilisé.',
        ]);

        // Erreurs
        if ($validator->fails()) {
            return response()->json([
                'message' => 'La création du compte a échoué. ',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Création d'un nouvel utilisateur
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->address = $request->address;
        $user->save();

        // Après l'inscription, connectez automatiquement l'utilisateur
        Auth::login($user);

        // Génération d'un jeton JWT pour l'utilisateur
        $token = $user->createToken('AuthToken')->accessToken;

        // Retourne une réponse de succès avec le jeton d'accès
        return response()->json([
            'message' => 'Utilisateur inscrit avec succès',
            'user' => $user,
            'access_token' => $token,
        ], 201);
    }

}
