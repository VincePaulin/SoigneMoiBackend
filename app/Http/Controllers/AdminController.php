<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Models\User;
use App\Models\Agenda;
use App\Models\Stay;
use App\Models\Appointment;

class AdminController extends Controller
{
    function getUserFullName(Request $request)
    {
        // Validate query data
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            // Retrieve the user based on the ID provided in the request
            $user = User::findOrFail($request->user_id);

            // Return user's full name
            return response()->json(['full_name' => $user->first_name . ' ' . $user->name], 200);
        } catch (\Exception $e) {
            // In case of error, return an error response
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération du nom de l\'utilisateur.'], 500);
        }
    }

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

            // Delete the agenda associated with the doctor, if any
            if ($doctor->agenda) {
                $doctor->agenda->delete();
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

    public function getAgendaByDoctorMatricule(Request $request)
    {
        // Validate the doctor's number in the query
        $request->validate([
            'matricule' => 'required|string', // To ensure that the personnel number is present and is a character string
        ], [
            'matricule.required' => 'Le matricule du médecin est requis.',
        ]);

        // Search for the doctor corresponding to the registration number
        $doctor = Doctor::where('matricule', $request->matricule)->first();

        // Check if the doctor exists
        if (!$doctor) {
            return response()->json(['error' => 'Médecin non trouvé.'], 404);
        }

        try {
            // Retrieve the agenda associated with the doctor
            $agenda = Agenda::where('doctor_matricule', $doctor->matricule)->first();

            // Incorporate doctor's information into the Agenda object
            $agenda->doctor = $doctor;

            // Return the diary successfully
            return response()->json(['agenda' => $agenda], 200);
        } catch (\Exception $e) {
            // In case of error, return an error response
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération de l\'agenda.'], 500);
        }
    }

    public function getStayNotProgrammedByDoctor(Request $request)
    {
        try {
            // Retrieve the doctor's personnel number from the query
            $matricule = $request->matricule;

            // Find the doctor corresponding to your personnel number
            $doctor = Doctor::where('matricule', $matricule)->first();

            // Check if the doctor exists
            if (!$doctor) {
                return response()->json(['error' => 'Médecin introuvable.'], 404);
            }

            // Retrieve the doctor's specialty
            $specialty = $doctor->specialty;

            // Check if the specialty exists
            if (!$specialty) {
                return response()->json(['error' => 'Spécialité introuvable.'], 404);
            }

            // Find all unscheduled stays that have the same type as the physician's specialty
            $stays = Stay::whereDoesntHave('appointments')
                ->where('type', $specialty)
                ->get();

            return response()->json(['stays' => $stays], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération des séjours non programmés.'], 500);
        }
    }


    public function getAllStayNotProgramed()
    {
        try {
            // Retrieves all stays without scheduled appointments
            $stays = Stay::whereDoesntHave('appointments')->get();

            // Return stays successfully
            return response()->json(['stays' => $stays], 200);
        } catch (\Exception $e) {
            // On error, returns an error response
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération des séjours non programmés.'], 500);
        }
    }

    public function hasOverlappingAppointments($startDate, $endDate, $doctorMatricule)
    {
        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);

        // Counting the number of overlapping appointments
        $overlappingCount = 0;

        // Loop through each day between startDate and endDate
        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            // Counting the number of appointments for the selected day
            $appointmentsForDay = Appointment::where('doctor_matricule', $doctorMatricule)
                ->where(function ($query) use ($currentDate) {
                    $query->whereDate('start_date', $currentDate)
                        ->orWhereDate('end_date', $currentDate)
                        ->orWhere(function ($query) use ($currentDate) {
                            $query->where('start_date', '<', $currentDate)
                                ->where('end_date', '>', $currentDate);
                        });
                })
                ->get();

            // If the number of appointments for this day is already 5, increment the overlap counter
            if ($appointmentsForDay->count() >= 5) {
                $overlappingCount++;
            }

            // Move to the next day
            $currentDate = $currentDate->modify('+1 day');
        }

        // If the overlap counter is greater than zero, there is at least one day with 5 appointments
        return $overlappingCount > 0;
    }

    public function createAppointment(Request $request)
    {
        // Validate query data
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'patient_id' => 'required|exists:users,id',
            'doctor_matricule' => 'required|exists:doctors,matricule',
            'stay_id' => 'required|exists:stays,id',
            'motif' => 'required|string',
        ]);

        try {
            // Check if there is an appointment with the same stay_id
            $existingStayAppointment = Appointment::where('stay_id', $request->stay_id)->first();

            if ($existingStayAppointment) {
                // If an appointment with the same stay_id exists, return a conflict response
                return response()->json(['error' => 'Un rendez-vous pour ce séjour existe déjà. Veuillez sélectionner un autre séjour.'], 409);
            }

            // Check if there are any overlapping appointments
            if ($this->hasOverlappingAppointments($request->start_date, $request->end_date, $request->doctor_matricule)) {
                // If there are overlapping appointments, return a conflict response
                return response()->json(['error' => 'Le rendez-vous chevauche déjà un ou plusieurs jours complets. Veuillez sélectionner d\'autres dates.'], 409);
            }

            // Create a new Appointment
            $appointment = Appointment::create([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'patient_id' => $request->patient_id,
                'doctor_matricule' => $request->doctor_matricule,
                'stay_id' => $request->stay_id,
                'motif' => $request->motif,
            ]);

            // Return a JSON response with the newly created appointment
            return response()->json(['appointment' => $appointment], 201);
        } catch (\Exception $e) {
            // In case of error, return an error response
            return response()->json(['error' => 'Une erreur est survenue lors de la création de la réservation. Veuillez réessayer.'], 500);
        }
    }

    public function getAppointmentsStartingToday()
    {
        try {
            // Retrieves all appointments with a start date greater than or equal to today's date
            $appointments = Appointment::whereDate('start_date', '>=', now()->toDateString())->get();

            // Returns appointments successfully
            return response()->json(['appointments' => $appointments], 200);
        } catch (\Exception $e) {
            // On error, returns an error response
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération des rendez-vous.'], 500);
        }
    }

    public function getAppointmentsByDoctorMatricule(Request $request)
    {
        // Validate query data
        $request->validate([
            'doctor_matricule' => 'required|string|exists:doctors,matricule',
        ], [
            'doctor_matricule.required' => 'Le matricule du médecin est requis.',
            'doctor_matricule.exists' => 'Le médecin avec le matricule spécifié n\'existe pas.',
        ]);

        try {
            // Calculate the date one month ago
            $oneMonthAgo = now()->subMonth()->toDateString();

            // Retrieve all appointments for the specified doctor_matricule where end_date is within the last month or in the future
            $appointments = Appointment::where('doctor_matricule', $request->doctor_matricule)
                ->whereDate('end_date', '>=', $oneMonthAgo)->get();

            // Return appointments successfully
            return response()->json(['appointments' => $appointments], 200);
        } catch (\Exception $e) {
            // On error, returns an error response
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération des rendez-vous.'], 500);
        }
    }


    public function getStayCountWithNoAppointmentForEachDoctor()
    {
        try {
            // Use a query grouped by doctor_id to obtain the number of stays for each doctor that have no associated appointments
            $stayCounts = Stay::leftJoin('doctors', 'stays.doctor_id', '=', 'doctors.matricule')
                ->select('doctors.matricule', 'doctors.fullName', DB::raw('COUNT(stays.id) as stay_count'))
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('appointments')
                        ->whereRaw('appointments.stay_id = stays.id');
                })
                ->groupBy('doctors.matricule', 'doctors.fullName')
                ->get();

            // Returns the number of stays for each successful doctor
            return response()->json(['stay_counts' => $stayCounts], 200);
        } catch (\Exception $e) {
            // On error, returns an error response
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération des nombres de séjours pour chaque médecin.'], 500);
        }
    }
}
