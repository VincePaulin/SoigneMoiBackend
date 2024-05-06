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
                'medicalSections' => json_encode(['Cardiologie']),
                'matricule' => '123456',
            ],
            [
                'fullName' => 'Dr. Marie Dupont',
                'specialty' => 'Chirurgien plastique',
                'medicalSections' => json_encode(['Chirurgie']),
                'matricule' => '234567',
            ],
            [
                'fullName' => 'Dr. Paul Lefevre',
                'specialty' => 'Dermatologue',
                'medicalSections' => json_encode(['Dermatologie']),
                'matricule' => '345678',
            ],
            [
                'fullName' => 'Dr. Sophie Dubois',
                'specialty' => 'Gynécologue',
                'medicalSections' => json_encode(['Gynécologie']),
                'matricule' => '456789',
            ],
            [
                'fullName' => 'Dr. Pierre Lambert',
                'specialty' => 'Neurologue',
                'medicalSections' => json_encode(['Neurologie']),
                'matricule' => '567890',
            ],
            [
                'fullName' => 'Dr. Anne Renault',
                'specialty' => 'Orthopédiste',
                'medicalSections' => json_encode(['Orthopédie']),
                'matricule' => '678901',
            ],
            [
                'fullName' => 'Dr. Jacques Petit',
                'specialty' => 'Cardiologue',
                'medicalSections' => json_encode(['Cardiologie']),
                'matricule' => '789012',
            ],
            [
                'fullName' => 'Dr. Isabelle Rousseau',
                'specialty' => 'Chirurgien abdominal',
                'medicalSections' => json_encode(['Chirurgie']),
                'matricule' => '890123',
            ],
            [
                'fullName' => 'Dr. Étienne Moreau',
                'specialty' => 'Dermatologue',
                'medicalSections' => json_encode(['Dermatologie']),
                'matricule' => '901234',
            ],
            [
                'fullName' => 'Dr. Sandrine Berger',
                'specialty' => 'Gynécologue obstétricien',
                'medicalSections' => json_encode(['Gynécologie']),
                'matricule' => '012345',
            ],
            [
                'fullName' => 'Dr. Lucie Michel',
                'specialty' => 'Neurologue',
                'medicalSections' => json_encode(['Neurologie']),
                'matricule' => '123456',
            ],
            [
                'fullName' => 'Dr. Marc Leroy',
                'specialty' => 'Orthopédiste traumatologue',
                'medicalSections' => json_encode(['Orthopédie']),
                'matricule' => '234567',
            ],
            [
                'fullName' => 'Dr. Nathalie Roussel',
                'specialty' => 'Cardiologue pédiatrique',
                'medicalSections' => json_encode(['Cardiologie']),
                'matricule' => '345678',
            ],
            [
                'fullName' => 'Dr. Thomas Durand',
                'specialty' => 'Chirurgien cardiaque',
                'medicalSections' => json_encode(['Chirurgie']),
                'matricule' => '456789',
            ],
            [
                'fullName' => 'Dr. Camille Morel',
                'specialty' => 'Dermatologue pédiatrique',
                'medicalSections' => json_encode(['Dermatologie']),
                'matricule' => '567890',
            ],
        ];

        foreach ($doctors as $doctorData) {
            Doctor::create($doctorData);
        }
    }
}
