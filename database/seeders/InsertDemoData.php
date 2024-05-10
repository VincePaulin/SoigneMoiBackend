<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

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
                'specialty' => 'Cardiologue',
                'medicalSections' => '["Cardiologie"]',
                'matricule' => '123456',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Marie Dupont',
                'specialty' => 'Chirurgien plastique',
                'medicalSections' => '["Chirurgie"]',
                'matricule' => '234567',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Paul Lefevre',
                'specialty' => 'Dermatologue',
                'medicalSections' => '["Dermatologie"]',
                'matricule' => '345678',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Sophie Dubois',
                'specialty' => 'Gynécologue',
                'medicalSections' => '["Gynécologie"]',
                'matricule' => '456789',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Pierre Lambert',
                'specialty' => 'Neurologue',
                'medicalSections' => '["Neurologie"]',
                'matricule' => '567890',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Anne Renault',
                'specialty' => 'Orthopédiste',
                'medicalSections' => '["Orthopédie"]',
                'matricule' => '678901',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Jacques Petit',
                'specialty' => 'Cardiologue',
                'medicalSections' => '["Cardiologie"]',
                'matricule' => '789012',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Isabelle Rousseau',
                'specialty' => 'Chirurgien abdominal',
                'medicalSections' => '["Chirurgie"]',
                'matricule' => '890123',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Étienne Moreau',
                'specialty' => 'Dermatologue',
                'medicalSections' => '["Dermatologie"]',
                'matricule' => '901234',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Sandrine Berger',
                'specialty' => 'Gynécologue obstétricien',
                'medicalSections' => '["Gynécologie"]',
                'matricule' => '012345',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Lucie Michel',
                'specialty' => 'Neurologue',
                'medicalSections' => '["Neurologie"]',
                'matricule' => '122456',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Marc Leroy',
                'specialty' => 'Orthopédiste traumatologue',
                'medicalSections' => '["Orthopédie"]',
                'matricule' => '2345678',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Nathalie Roussel',
                'specialty' => 'Cardiologue pédiatrique',
                'medicalSections' => '["Cardiologie"]',
                'matricule' => '3456789',
                'sex' => 'femme',
            ],
            [
                'fullName' => 'Dr. Thomas Durand',
                'specialty' => 'Chirurgien cardiaque',
                'medicalSections' => '["Chirurgie"]',
                'matricule' => '4567890',
                'sex' => 'homme',
            ],
            [
                'fullName' => 'Dr. Camille Morel',
                'specialty' => 'Dermatologue pédiatrique',
                'medicalSections' => '["Dermatologie"]',
                'matricule' => '5678901',
                'sex' => 'femme',
            ],
        ];

        foreach ($doctors as $doctorData) {
            Doctor::create($doctorData);
        }
    }
}
