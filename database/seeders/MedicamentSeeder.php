<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MedicamentSeeder extends Seeder
{
    public function run()
    {
        $medicaments = [];

        for ($i = 1; $i <= 20; $i++) {
            $medicaments[] = [
                'nom' => 'Médicament ' . $i,
                'detailles' => 'Description du médicament ' . $i,
                'prix' => rand(10, 200) + (rand(0, 99) / 100), // Ex: 55.67
                'dosage' => rand(100, 1000), // en mg
                'necessiteOrdonnance' => rand(0, 1),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('medicaments')->insert($medicaments);
    }
}
