<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Str;

class InsertDemoData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
            Doctor::create($doctorData);

            // Remove spaces from the full name and convert to lower case
            $username = strtolower(str_replace(' ', '', $doctorData['fullName']));

            // E-mail address generation using username and domain
            $email = $username . '@soignemoi.com';

            // Random password generation
            $password = Str::random(7);

            // Creation of a user with the “doctor” role and association with the corresponding doctor
            $user = User::create([
                'name' => $doctorData['fullName'],
                'email' => $email,
                'password' => bcrypt($password),
                'role' => User::ROLE_DOCTOR,
                'matricule' => $doctorData['matricule'],
            ]);

            // Print user details
            $this->command->info("Utilisateur doctor créé avec succès:");
            $this->command->info("Email: $email");
            $this->command->info("Mot de passe: $password");
        }
    }
}
