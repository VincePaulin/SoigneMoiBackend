<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DoctorController extends Controller
{
    public function getList()
    {
        $doctors = Doctor::all();
        return response()->json(['doctors' => $doctors], 200);
    }

    public function getDoctorsByMatricules(Request $request)
    {
        // Retrieve physicians' personnel numbers from the query
        $matricules = $request->input('matricules');

        // $matricules is an array
        if (!is_array($matricules)) {
            // Si $matricules n'est pas un tableau, créez-en un à partir de la valeur unique
            $matricules = [$matricules];
        }

        // Retrieve the doctors associated with the personnel numbers supplied
        $doctors = Doctor::whereIn('matricule', $matricules)->get();

        return response()->json(['doctors' => $doctors], 200);
    }

    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
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

            // Check if the user is a doctor
            if (!$user->isDoctor()) {
                return response()->json(['message' => 'Application réservée aux docteurs'], 401);
            }

            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user,], 200);
        }

        // Checks if the email exists in the database
        $userExists = User::where('email', $request->email)->exists();

        if (!$userExists) {
            return response()->json(['message' => 'Adresse email ou mot de passe incorrect'], 401);
        }

        // If the email exists but the password is incorrect
        throw ValidationException::withMessages([
            'email' => ['Adresse email ou mot de passe incorrect'],
        ]);
    }
}
