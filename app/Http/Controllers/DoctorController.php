<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Avis;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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

    /**
     * Retrieves a doctor's diary and all related appointments with the user's matricule.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorAgendaAndAppointments()
    {
        // Recover currently authenticated user
        $user = Auth::user();

        // Recover user number
        $matricule = $user->matricule;

        // Retrieve doctor with specified personnel number
        $doctor = Doctor::where('matricule', $matricule)->first();

        // Check if the doctor exists
        if (!$doctor) {
            return response()->json(['message' => 'Aucun médecin trouvé avec le matricule spécifié'], 404);
        }

        // Retrieve the doctor's agenda
        $agenda = Agenda::where('doctor_matricule', $matricule)->first();;

        // Check if the agenda exists
        if (!$agenda) {
            return response()->json(['message' => 'Aucun agenda trouvé pour ce médecin'], 404);
        }

        // Retrieve appointments associated with the calendar
        $appointments = $agenda->appointments()->where('end_date', '>=', Carbon::now())->with('patient')->get();

        // Return information as a JSON response
        return response()->json([
            'doctor' => $doctor,
            'agenda' => $agenda,
            'appointments' => $appointments,
        ], 200);
    }

    /**
     * Create a medical opinion and link it to the patient and the doctor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAvis(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string',
            'description' => 'required|string',
            'patient' => 'required|string',
        ], [
            'libelle.required' => 'Le champ libellé est requis.',
            'libelle.string' => 'Le libellé doit être une chaîne de caractères.',
            'description.required' => 'Le champ description est requis.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'patient.required' => 'Le champ patient est requis.',
            'patient.string' => 'L\'identifiant du patient doit être une chaîne de caractères.',
        ]);

        // Recover the authenticated user (the doctor)
        $doctor = Auth::user();

        // Create a new medical opinion with the query data
        $avis = new Avis([
            'libelle' => $request->input('libelle'),
            'description' => $request->input('description'),
            'date' => now(),
            'doctor_id' => $doctor->id,
            'patient_id' => $request->input('patient'),
        ]);

        // Record medical opinion in database
        $avis->save();

        // Return a JSON response to indicate that the notification has been created successfully
        return response()->json(['message' => 'Avis médical créé avec succès'], 201);
    }
}
