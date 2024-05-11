<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Agenda;
use App\Models\Stay;

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

    public function deleteDoctor($matricule)
    {
        // Find the doctor by matricule
        $doctor = Doctor::where('matricule', $matricule)->first();

        // If doctor does not exist, return 404 Not Found response
        if (!$doctor) {
            return response()->json(['error' => 'Docteur non trouvé.'], 404);
        }

        try {
            // Delete doctor's photo from storage if it exists
            if ($doctor->avatarURL) {
                $avatarPath = str_replace(asset('storage/'), 'public/', $doctor->avatarURL);
                Storage::delete($avatarPath);
            }

            // Delete the doctor from database
            $doctor->delete();

            // Return success response
            return response()->json(['message' => 'Docteur supprimé avec succès.'], 200);
        } catch (\Exception $e) {
            // In case of error, return 500 Internal Server Error response
            return response()->json(['error' => 'Une erreur est survenue lors de la suppression du docteur. Veuillez réessayer.'], 500);
        }
    }

    public function getAllAgendas()
    {
        // Retrieve all doctors
        $doctors = Doctor::all();

        // Browse all doctors to check and create missing agendas
        foreach ($doctors as $doctor) {
            // Check whether the doctor has a diary
            if (!$doctor->agenda) {

                // Create a new agenda for this doctor
                Agenda::create([
                    'doctor_matricule' => $doctor->matricule,
                ]);
            }
        }

        // Retrieve all doctors with their diaries
        $agendas = Agenda::with('doctor')->get();

        // Return JSON response containing all agendas
        return response()->json(['agendas' => $agendas], 200);
    }

    public function getStaysByDoctorMatricule(Request $request)
    {
        // Validate the doctor's number in the query
        $request->validate([
            'matricule' => 'required|string', // To ensure that the personnel number is present and is a character string
        ], [
            'matricule.required' => 'Le matricule du médecin est requis.',
        ]);

        // Search for the doctor corresponding to the personnel number
        $doctor = Doctor::where('matricule', $request->matricule)->first();

        // Check if the doctor exists
        if (!$doctor) {
            return response()->json(['error' => 'Médecin non trouvé.'], 404);
        }

        try {
            // Retrieves all stays associated with the doctor found with the corresponding user
            $stays = Stay::with('user')
                ->where('doctor_id', $doctor->matricule)
                ->get();

            // Return stays successfully
            return response()->json(['stays' => $stays], 200);
        } catch (\Exception $e) {
            // On error, returns an error response
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération des séjours.'], 500);
        }
    }
}
