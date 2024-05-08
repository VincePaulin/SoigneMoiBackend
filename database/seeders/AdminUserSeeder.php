<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Générer un mot de passe aléatoire
        $password = bin2hex(random_bytes(8));

        // Créer un nouvel utilisateur avec le rôle admin
        User::create([
            'first_name' => 'Dr',
            'name' => 'House',
            'email' => 'dr.house@example.com',
            'password' => Hash::make($password),
            'role' => User::ROLE_ADMIN,
            'address' => '123 Rue du Docteur',
        ]);

        $this->command->info("Utilisateur administrateur créé avec succès:");
        $this->command->info("Email: dr.house@example.com");
        $this->command->info("Mot de passe: $password");
    }
}
