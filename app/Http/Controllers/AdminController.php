<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function getAllDoctors()
    {
        $doctors = Doctor::all();
        return response()->json(['doctors' => $doctors], 200);
    }

    // Function to create a new doctor
    public function createDoctor(Request $request)
    {
        // Checks whether all necessary data are present in the query
        $request->validate([
            'fullName' => 'required|string',
            'specialty' => 'required|string',
            'medicalSections' => 'required|array',
            'medicalSections.*' => 'string',
            'matricule' => 'required|string|unique:doctors',
            'avatarURL' => 'nullable|string',
            'sex' => 'required|string',
        ], [
            'fullName.required' => 'Le nom complet du docteur est requis.',
            'specialty.required' => 'La spécialité du docteur est requise.',
            'medicalSections.required' => 'Au moins une section médicale est requise.',
            'matricule.required' => 'Le matricule du docteur est requis.',
            'matricule.unique' => 'Ce matricule est déjà utilisé par un autre docteur.',
            'sex.required' => 'Le sexe du docteur est requis.',
        ]);


        // Process profile photo if present in query
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $photo = $request->file('photo');
            // Generate a unique name for the file
            $photoName = uniqid('avatar_') . '.' . $photo->extension();
            // Save file to storage
            $photo->storeAs('public/avatars', $photoName);
            // Update avatar URL in doctor data
            $avatarURL = asset('storage/avatars/' . $photoName);
        }

        // Converts medicalSections array to JSON string
        $medicalSectionsJson = json_encode($request->medicalSections);

        try {
            // Create a new doctor with the data supplied
            $doctor = Doctor::create([
                'fullName' => $request->fullName,
                'specialty' => $request->specialty,
                'medicalSections' => $medicalSectionsJson,
                'matricule' => $request->matricule,
                'avatarURL' => $avatarURL ?? null,
                'sex' => $request->sex,
            ]);

            // Returns a JSON response with the newly created doctor
            return response()->json(['doctor' => $doctor], 201);
        } catch (\Exception $e) {
            // In case of error, returns an error message in French
            return response()->json(['error' => 'Une erreur est survenue lors de la création du docteur. Veuillez réessayer.'], 500);
        }
    }
}
