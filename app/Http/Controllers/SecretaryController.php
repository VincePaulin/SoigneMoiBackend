<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Avis;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Models\Stay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SecretaryController extends Controller
{
    /**
     * Handle a login request to the application for a secretary.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Attempt to find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        // Check if the user is a secretary
        if (!$user->isSecretary()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        // Generate an API token for the user
        $token = $user->createToken('secretary-token')->plainTextToken;

        // Return a success response with the token
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    /**
     * Get all ongoing stays.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOngoingStays(Request $request)
    {
        // Get the current date
        $today = Carbon::today();

        // Retrieve all stays starting today and load the associated users
        $entries = Stay::with('user')->whereDate('start_date', '=', $today)->get();

        // Retrieve all stays ending today and load the associated users
        $outputs = Stay::with('user')->whereDate('end_date', '=', $today)->get();

        // Structure the response
        $response = [
            'entry' => $entries,
            'output' => $outputs,
        ];

        return response()->json($response);
    }

    /**
     * Get all stays, avis, and prescriptions linked to a user by user ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetails(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Retrieve user by ID
        $userId = $request->input('user_id');
        $user = User::find($userId);

        // Retrieve all stays associated with the user or return an empty collection
        $stays = Stay::where('user_id', $userId)->get() ?? collect();

        // Retrieve all avis associated with the user or return an empty collection
        $avis = Avis::where('patient_id', $userId)->get() ?? collect();

        // Retrieve all prescriptions associated with the user and load related drugs
        $prescriptions = Prescription::with('drugs')->where('patient_id', $userId)->get() ?? collect();

        // Retrieve all doctors associated with the avis and prescriptions
        $doctorIds = $avis->pluck('doctor_id')->merge($prescriptions->pluck('doctor_id'))->unique();
        $doctors = User::whereIn('id', $doctorIds)->get()->keyBy('id');

        // Format avis to include doctor's full name
        $formattedAvis = $avis->map(function ($item) use ($doctors) {
            return [
                'id' => $item->id,
                'libelle' => $item->libelle,
                'date' => $item->date,
                'description' => $item->description,
                'doctor_id' => $item->doctor_id,
                'patient_id' => $item->patient_id,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'doctor' => isset($doctors[$item->doctor_id]) ? $doctors[$item->doctor_id]->name : null, // Assuming 'name' field is the full name of the doctor
            ];
        });

        // Format prescriptions to include doctor's full name
        $formattedPrescriptions = $prescriptions->map(function ($item) use ($doctors) {
            return [
                'id' => $item->id,
                'patient_id' => $item->patient_id,
                'doctor_id' => $item->doctor_id,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'doctor' => isset($doctors[$item->doctor_id]) ? $doctors[$item->doctor_id]->name : null, // Assuming 'name' field is the full name of the doctor
                'drugs' => $item->drugs,
            ];
        });

        // Structure the response
        $response = [
            'user' => $user,
            'stays' => $stays->isEmpty() ? [] : $stays,
            'avis' => $formattedAvis->isEmpty() ? [] : $formattedAvis,
            'prescriptions' => $formattedPrescriptions->isEmpty() ? [] : $formattedPrescriptions,
        ];

        return response()->json($response);
    }

    /**
     * Get all doctors with appointments on today's date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorsWithAppointmentsToday(Request $request)
    {
        try {
            // Get today's date
            $today = Carbon::today();

            // Retrieve all appointments where today is between start_date and end_date
            $appointments = Appointment::whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->get();

            // Get the doctor matricules from these appointments
            $doctorMatricules = $appointments->pluck('doctor_matricule')->unique();

            // Retrieve the doctors based on these matricules
            $doctors = Doctor::whereIn('matricule', $doctorMatricules)->get();

            // Return the list of doctors
            return response()->json(['doctors' => $doctors], 200);
        } catch (\Exception $e) {
            // On error, returns an error response
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération des docteurs.'], 500);
        }
    }
}
