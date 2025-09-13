<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nom' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->call([
            UsersTableSeeder::class,
            MedicamentSeeder::class,
            CommandesSeeder::class,
            VentesSeeder::class,
            stockSeeder::class,
            orderSeeder::class,
            // Add other seeders here if needed
        ]);
    }
}
