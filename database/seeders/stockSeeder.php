<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class stockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all medicament IDs
        $medicamentIds = DB::table('medicaments')->pluck('id');

        $stocks = [];

        foreach ($medicamentIds as $medicamentId) {
            $stocks[] = [
                'medicament_id' => $medicamentId,
                'seuilMinimum' => rand(5, 20),
                'quantité' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('stocks')->insert($stocks);
    }
}
