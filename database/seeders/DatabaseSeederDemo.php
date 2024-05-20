<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Stay;
use App\Models\Avis;
use App\Models\Prescription;
use App\Models\PrescriptionDrug;
use App\Models\Appointment;
use App\Models\Agenda;
use Carbon\Carbon;

class DatabaseSeederDemo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin account
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'dr.house@soignemoi.com',
            'password' => Hash::make('19962305Vp'),
            'role' => User::ROLE_ADMIN,
            'address' => '123 Admin St, Admin City',
        ]);

        // Create Secretary account
        $secretary = User::create([
            'name' => 'Secretary',
            'email' => 'secretary@soignemoi.com',
            'password' => Hash::make('19962305Vp'),
            'role' => User::ROLE_SECRETARY,
            'address' => '456 Secretary St, Secretary City',
        ]);

        // Create Doctor accounts
        $doctors = [
            [
                'fullName' => 'Dr. Jean Martin',
                'specialty' => 'Cardiologie',
                'medicalSections' => '["Cardiologue"]',
                'matricule' => '123456',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Marie Dupont',
                'specialty' => 'Chirurgie',
                'medicalSections' => '["Chirurgien plastique"]',
                'matricule' => '234567',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Paul Lefevre',
                'specialty' => 'Dermatologie',
                'medicalSections' => '["Dermatologue"]',
                'matricule' => '345678',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Sophie Dubois',
                'specialty' => 'Gynécologie',
                'medicalSections' => '["Gynécologue"]',
                'matricule' => '456789',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Pierre Lambert',
                'specialty' => 'Neurologie',
                'medicalSections' => '["Neurologue"]',
                'matricule' => '567890',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Anne Renault',
                'specialty' => 'Orthopédie',
                'medicalSections' => '["Orthopédiste"]',
                'matricule' => '678901',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Jacques Petit',
                'specialty' => 'Cardiologie',
                'medicalSections' => '["Cardiologue"]',
                'matricule' => '789012',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Isabelle Rousseau',
                'specialty' => 'Chirurgie',
                'medicalSections' => '["Chirurgien abdominal"]',
                'matricule' => '890123',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Étienne Moreau',
                'specialty' => 'Dermatologie',
                'medicalSections' => '["Dermatologue"]',
                'matricule' => '901234',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Sandrine Berger',
                'specialty' => 'Gynécologie',
                'medicalSections' => '["Gynécologue obstétricien"]',
                'matricule' => '012345',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Lucie Michel',
                'specialty' => 'Neurologie',
                'medicalSections' => '["Neurologue"]',
                'matricule' => '122456',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Marc Leroy',
                'specialty' => 'Orthopédie',
                'medicalSections' => '["Orthopédiste traumatologue"]',
                'matricule' => '2345678',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Nathalie Roussel',
                'specialty' => 'Cardiologie',
                'medicalSections' => '["Cardiologue pédiatrique"]',
                'matricule' => '3456789',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Thomas Durand',
                'specialty' => 'Chirurgie',
                'medicalSections' => '["Chirurgien cardiaque"]',
                'matricule' => '4567890',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Camille Morel',
                'specialty' => 'Dermatologie',
                'medicalSections' => '["Dermatologue pédiatrique"]',
                'matricule' => '5678901',
                'sex' => 'femme',
            ],
        ];

        foreach ($doctors as $doctorData) {
            $doctor = Doctor::create($doctorData);

            $username = strtolower(str_replace(' ', '', $doctorData['fullName']));
            $email = $username . '@soignemoi.com';
            $password = Str::random(7);

            $user = User::create([
                'name' => $doctorData['fullName'],
                'email' => $email,
                'password' => bcrypt($password),
                'role' => User::ROLE_DOCTOR,
                'matricule' => $doctorData['matricule'],
                'address' => 'Doctor Address ' . $doctorData['matricule'],
            ]);

            Agenda::create([
                'doctor_matricule' => $doctorData['matricule'],
            ]);

            $this->command->info("Doctor account created:");
            $this->command->info("Email: $email");
            $this->command->info("Password: $password");
        }

        // Create 15 User accounts
        for ($i = 1; $i <= 15; $i++) {
            $user = User::factory()->create([
                'role' => User::ROLE_USER,
                'address' => 'User Address ' . $i,
            ]);
            $this->command->info("User account created with ID: {$user->id}");

            $doctor = Doctor::inRandomOrder()->first();
            $medicalSection = $doctor->specialty;
            
            $start_date = Carbon::today();
            $end_date = Carbon::today();

            if ($i <= 5) {
                // 5 stays starting today
                $start_date = Carbon::today();
                $end_date = Carbon::today()->addDays(rand(1, 10));
            } elseif ($i <= 10) {
                // 5 stays ending today
                $start_date = Carbon::today()->subDays(rand(1, 10));
                $end_date = Carbon::today();
            } else {
                // 5 stays with random start and end dates
                $start_date = Carbon::today()->subDays(rand(1, 10));
                $end_date = Carbon::today()->addDays(rand(1, 10));
            }

            $stay = Stay::create([
                'user_id' => $user->id,
                'doctor_id' => $doctor->matricule, // Use matricule instead of id
                'type' => $medicalSection,
                'motif' => 'Routine check-up',
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);

            // Create Avis for the user
            Avis::create([
                'patient_id' => $user->id, // Use patient_id instead of user_id
                'doctor_id' => $doctor->id, // Use id instead of matricule
                'libelle' => 'Avis du docteur',
                'description' => 'Avis médical sur l\'état de santé général du patient.',
                'date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Create Prescription for the user
            $prescription = Prescription::create([
                'patient_id' => $user->id, // Use patient_id instead of user_id
                'doctor_id' => $doctor->id, // Use id instead of matricule
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now(),
            ]);

            // Add drugs to the prescription
            $drugs = [
                'Paracetamol 500mg',
                'Ibuprofen 200mg',
                'Amoxicillin 500mg',
            ];

            foreach ($drugs as $drug) {
                PrescriptionDrug::create([
                    'prescription_id' => $prescription->id,
                    'drug' => $drug,
                    'dosage' => '2 times a day',
                ]);
            }

            if ($i <= 8) {
                // Create appointments for 8 stays
                Appointment::create([
                    'start_date' => $stay->start_date,
                    'end_date' => $stay->end_date,
                    'patient_id' => $user->id,
                    'doctor_matricule' => $doctor->matricule,
                    'stay_id' => $stay->id,
                    'motif' => 'Routine check-up appointment',
                ]);
            }
        }
    }
}
