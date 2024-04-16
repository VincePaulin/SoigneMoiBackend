<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Lang;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
                'address' => 'required|string',
            ], [
                'name.required' => 'Le nom est requis.',
                'email.required' => 'L\'adresse email est requise.',
                'email.email' => 'L\'adresse email doit être une adresse email valide.',
                'email.unique' => 'Cette adresse email est déjà utilisée.',
                'password.required' => 'Le mot de passe est requis.',
                'password.min' => 'Le mot de passe doit avoir au moins :min caractères.',
                'address.required' => 'L\'adresse est requise.',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
            ]);

            return response()->json(['message' => 'Utilisateur enregistré avec succès', 'user' => $user], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 400);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être une adresse email valide.',
            'password.required' => 'Le mot de passe est requis.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user], 200);
        }

        throw ValidationException::withMessages([
            'email' => [Lang::get('auth.failed')],
            'password' => [Lang::get('auth.failed')],
        ]);
    }

    public function getUser(Request $request)
    {
        // Récupérer l'utilisateur authentifié
        $user = $request->user();

        // Retourne les informations de l'utilisateur sous forme de réponse JSON
        return response()->json(['user' => $user], 200);
    }
}
