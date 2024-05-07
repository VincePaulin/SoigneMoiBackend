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
            ],
            [
                'fullName' => 'Dr. Marie Dupont',
                'specialty' => 'Chirurgien plastique',
                'medicalSections' => '["Chirurgie"]',
                'matricule' => '234567',
            ],
            [
                'fullName' => 'Dr. Paul Lefevre',
                'specialty' => 'Dermatologue',
                'medicalSections' => '["Dermatologie"]',
                'matricule' => '345678',
            ],
            [
                'fullName' => 'Dr. Sophie Dubois',
                'specialty' => 'Gynécologue',
                'medicalSections' => '["Gynécologie"]',
                'matricule' => '456789',
            ],
            [
                'fullName' => 'Dr. Pierre Lambert',
                'specialty' => 'Neurologue',
                'medicalSections' => '["Neurologie"]',
                'matricule' => '567890',
            ],
            [
                'fullName' => 'Dr. Anne Renault',
                'specialty' => 'Orthopédiste',
                'medicalSections' => '["Orthopédie"]',
                'matricule' => '678901',
            ],
            [
                'fullName' => 'Dr. Jacques Petit',
                'specialty' => 'Cardiologue',
                'medicalSections' => '["Cardiologie"]',
                'matricule' => '789012',
            ],
            [
                'fullName' => 'Dr. Isabelle Rousseau',
                'specialty' => 'Chirurgien abdominal',
                'medicalSections' => '["Chirurgie"]',
                'matricule' => '890123',
            ],
            [
                'fullName' => 'Dr. Étienne Moreau',
                'specialty' => 'Dermatologue',
                'medicalSections' => '["Dermatologie"]',
                'matricule' => '901234',
            ],
            [
                'fullName' => 'Dr. Sandrine Berger',
                'specialty' => 'Gynécologue obstétricien',
                'medicalSections' => '["Gynécologie"]',
                'matricule' => '012345',
            ],
            [
                'fullName' => 'Dr. Lucie Michel',
                'specialty' => 'Neurologue',
                'medicalSections' => '["Neurologie"]',
                'matricule' => '122456',
            ],
            [
                'fullName' => 'Dr. Marc Leroy',
                'specialty' => 'Orthopédiste traumatologue',
                'medicalSections' => '["Orthopédie"]',
                'matricule' => '2345678',
            ],
            [
                'fullName' => 'Dr. Nathalie Roussel',
                'specialty' => 'Cardiologue pédiatrique',
                'medicalSections' => '["Cardiologie"]',
                'matricule' => '3456789',
            ],
            [
                'fullName' => 'Dr. Thomas Durand',
                'specialty' => 'Chirurgien cardiaque',
                'medicalSections' => '["Chirurgie"]',
                'matricule' => '4567890',
            ],
            [
                'fullName' => 'Dr. Camille Morel',
                'specialty' => 'Dermatologue pédiatrique',
                'medicalSections' => '["Dermatologie"]',
                'matricule' => '5678901',
            ],
        ];

        foreach ($doctors as $doctorData) {
            Doctor::create($doctorData);
        }
    }
}
