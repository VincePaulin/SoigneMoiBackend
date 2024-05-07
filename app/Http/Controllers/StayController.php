<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stay;
use App\Models\Doctor;

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

        // Query data validation
        $validatedData = $request->validate([
            'motif' => 'required|string',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'doctor_matricule' => 'nullable|exists:doctors,matricule',
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
            'doctor_matricule.exists' => 'Le médecin sélectionné n\'existe pas.',
        ]);

        // Retrieve the doctor's personnel number from the query
        $doctorMatricule = $validatedData['doctor_matricule'];

        // Creation of the stay associated with the user
        $stayData = [
            'user_id' => $user->id,
            'motif' => $validatedData['motif'],
            'type' => $validatedData['type'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'precision' => $request->precision,
            'doctor_id' => $doctorMatricule, // Registration number in the doctor_id column
        ];

        $stay = Stay::create($stayData);

        // Return the answer with the stay
        return response()->json(['stay' => $stay], 201);
    }
}
