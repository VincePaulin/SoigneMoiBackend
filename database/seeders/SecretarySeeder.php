<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SecretarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Secretary',
            'name' => 'Secretary',
            'email' => 'secretary@soignemoi.com',
            'password' => Hash::make('19962305Vp'),
            'address' => '123 Secretary St',
            'role' => User::ROLE_SECRETARY, // Assuming you have defined this constant in your User model
        ]);
    }
}
