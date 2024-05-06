<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stay;

class StayController extends Controller
{
    // Function to retrieve stays for a specific user
    public function getUserStays(Request $request)
    {
        $user = $request->user();
        // Recover user stays from his ID
        $stays = Stay::where('user_id', $user->id)->get();
        return response()->json(['stays' => $stays], 200);
    }

    public function createStay(Request $request)
    {
        $user = $request->user();
        
        // Validation des données de la requête
        $validatedData = $request->validate([
            'motif' => 'required|string',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'doctor_id' => 'exists:doctors,id',
        ], [
            'motif.required' => 'Le motif du séjour est requis.',
            'motif.string' => 'Le motif du séjour doit être une chaîne de caractères.',
            'type.required' => 'Le type de séjour est requis.',
            'type.string' => 'Le type de séjour doit être une chaîne de caractères.',
            'start_date.required' => 'La date de début du séjour est requise.',
            'start_date.date' => 'La date de début du séjour doit être une date valide.',
            'end_date.required' => 'La date de fin du séjour est requise.',
            'end_date.date' => 'La date de fin du séjour doit être une date valide.',
            'end_date.after' => 'La date de fin du séjour doit être postérieure à la date de début du séjour.',
            'doctor_id.exists' => 'Le médecin sélectionné n\'existe pas.',
        ]);

        // Création du séjour associé à l'utilisateur
        $stayData = [
            'user_id' => $user->id,
            'motif' => $validatedData['motif'],
            'type' => $validatedData['type'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'precision' => $request->precision,
        ];

        // Ajouter le doctor_id si présent dans les données validées
        if (isset($validatedData['doctor_id'])) {
            $stayData['doctor_id'] = $validatedData['doctor_id'];
        }

        $stay = Stay::create($stayData);

        return response()->json(['stay' => $stay], 201);
    }

}
