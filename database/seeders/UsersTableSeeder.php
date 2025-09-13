<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pharmacien
        User::updateOrCreate(
            ['email' => 'pharmacist@example.com'],
            [
                'nom' => 'John',
                'prenom' => 'Doe',
                'password' => Hash::make('password123'),
                'role' => 'pharmacien',
                'email_verified_at' => now(),
            ]
        );

        // Docteur
        User::create([
            'nom' => 'Driss',
            'prenom' => 'Med',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password123'),
            'role' => 'docteur',
        ]);
        
        // Admin
        User::create([
            'nom' => 'Admin',
            'prenom' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Patients
        $patients = [
            [
                'nom' => 'Jane',
                'prenom' => 'Smith',
                'email' => 'patient@example.com',
                'adresse' => '123 Main St, City',
            ],
            [
                'nom' => 'Ali',
                'prenom' => 'Benali',
                'email' => 'ali.benali@example.com',
                'adresse' => '45 Rue des Fleurs, Alger',
            ],
            [
                'nom' => 'Samira',
                'prenom' => 'Bouzid',
                'email' => 'samira.bouzid@example.com',
                'adresse' => '78 Avenue de la Liberté, Oran',
            ],
            [
                'nom' => 'Karim',
                'prenom' => 'Haddad',
                'email' => 'karim.haddad@example.com',
                'adresse' => '12 Boulevard Emir, Constantine',
            ],
            [
                'nom' => 'Leila',
                'prenom' => 'Mansouri',
                'email' => 'leila.mansouri@example.com',
                'adresse' => '99 Place des Martyrs, Annaba',
            ],
        ];

        foreach ($patients as $patient) {
            $user = User::create([
                'nom' => $patient['nom'],
                'prenom' => $patient['prenom'],
                'email' => $patient['email'],
                'password' => Hash::make('password123'),
                'role' => 'patient',
            ]);
            // Add adresse to the patient profile
            $user->patient()->update(['adresse' => $patient['adresse']]);
        }
    }
}
