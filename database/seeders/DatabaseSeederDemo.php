<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Stay;
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
        ]);

        // Create Secretary account
        $secretary = User::create([
            'name' => 'Secretary',
            'email' => 'secretary@soignemoi.com',
            'password' => Hash::make('19962305Vp'),
            'role' => User::ROLE_SECRETARY,
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
            ]);

            $this->command->info("Doctor account created:");
            $this->command->info("Email: $email");
            $this->command->info("Password: $password");
        }

        // Create User accounts
        $users = User::factory()->count(5)->create(['role' => User::ROLE_USER]);

        // Create Stays for Users
        foreach ($users as $user) {
            // 3 stays starting today
            for ($i = 0; $i < 3; $i++) {
                $doctor = Doctor::inRandomOrder()->first();
                $medicalSection = $doctor->specialty;

                Stay::create([
                    'user_id' => $user->id,
                    'doctor_id' => $doctor->matricule, // Use matricule instead of id
                    'type' => $medicalSection,
                    'motif' => 'Routine check-up',
                    'start_date' => Carbon::today(),
                    'end_date' => Carbon::today()->addDays(rand(1, 10)),
                ]);
            }

            // 2 stays ending today
            for ($i = 0; $i < 2; $i++) {
                $doctor = Doctor::inRandomOrder()->first();
                $medicalSection = $doctor->specialty;

                Stay::create([
                    'user_id' => $user->id,
                    'doctor_id' => $doctor->matricule, // Use matricule instead of id
                    'type' => $medicalSection,
                    'motif' => 'Routine check-up',
                    'start_date' => Carbon::today()->subDays(rand(1, 10)),
                    'end_date' => Carbon::today(),
                ]);
            }

            // 5 additional stays: 3 starting today, 2 ending today
            for ($i = 0; $i < 3; $i++) {
                $doctor = Doctor::inRandomOrder()->first();
                $medicalSection = $doctor->specialty;

                Stay::create([
                    'user_id' => $user->id,
                    'doctor_id' => $doctor->matricule, // Use matricule instead of id
                    'type' => $medicalSection,
                    'motif' => 'Routine check-up',
                    'start_date' => Carbon::today(),
                    'end_date' => Carbon::today()->addDays(rand(1, 10)),
                ]);
            }

            for ($i = 0; $i < 2; $i++) {
                $doctor = Doctor::inRandomOrder()->first();
                $medicalSection = $doctor->specialty;

                Stay::create([
                    'user_id' => $user->id,
                    'doctor_id' => $doctor->matricule, // Use matricule instead of id
                    'type' => $medicalSection,
                    'motif' => 'Routine check-up',
                    'start_date' => Carbon::today()->subDays(rand(1, 10)),
                    'end_date' => Carbon::today(),
                ]);
            }
        }
    }
}
