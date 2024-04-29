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
    // AuthController.php

    public function register(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string', // Ajout du champ "first_name" requis
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
                'address' => 'required|string',
            ], [
                'first_name.required' => 'Le prénom est requis.',
                'name.required' => 'Le nom est requis.',
                'email.required' => 'L\'adresse email est requise.',
                'email.email' => 'L\'adresse email doit être une adresse email valide.',
                'email.unique' => 'Cette adresse email est déjà utilisée.',
                'password.required' => 'Le mot de passe est requis.',
                'password.min' => 'Le mot de passe doit avoir au moins :min caractères.',
                'address.required' => 'L\'adresse est requise.',
            ]);

            $user = User::create([
                'first_name' => $request->first_name, // Sauvegarde du champ "first_name"
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
            ]);

            // Authentifier automatiquement l'utilisateur nouvellement enregistré
            Auth::login($user);

            // Générer un token pour l'utilisateur nouvellement enregistré
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json(['message' => 'Utilisateur enregistré avec succès', 'user' => $user, 'token' => $token], 201);
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

        // Vérifie si l'email existe dans la base de données
        $userExists = User::where('email', $request->email)->exists();

        if (!$userExists) {
            return response()->json(['message' => 'Adresse email ou mot de passe incorrect'], 401);
        }

        // Si l'email existe mais le mot de passe est incorrect
        throw ValidationException::withMessages([
            'email' => ['Adresse email ou mot de passe incorrect'],
        ]);
    }


    public function getUser(Request $request)
    {
        // Récupérer l'utilisateur authentifié
        $user = $request->user();

        // Retourne les informations de l'utilisateur sous forme de réponse JSON
        return response()->json(['user' => $user], 200);
    }

    public function updateUsername(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ], [
            'name.required' => 'Le nom est requis.',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        return response()->json(['message' => 'Nom d\'utilisateur mis à jour avec succès', 'user' => $user], 200);
    }

}
