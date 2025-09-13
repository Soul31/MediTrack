<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Medicament;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VentesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicamentIds = Medicament::pluck('id')->toArray();

        $ventes = [
            [
                'dateCreation' => Carbon::now()->subDays(2),
                'statut'       => 'terminee',
                'total'        => 0, // Will update after lignes
                'patient_name' => 'Ali Benali',
            ],
            [
                'dateCreation' => Carbon::now()->subDay(),
                'statut'       => 'terminee',
                'total'        => 0, // Will update after lignes
                'patient_name' => 'Samira Bouzid',
            ],
            [
                'dateCreation' => Carbon::now(),
                'statut'       => 'en attente',
                'total'        => 0, // Will update after lignes
                'patient_name' => 'Karim Haddad',
            ],
        ];

        foreach ($ventes as $venteData) {
            $vente = Vente::create($venteData);

            // Shuffle medicament IDs to avoid repeats
            $shuffledMedicamentIds = $medicamentIds;
            shuffle($shuffledMedicamentIds);

            // Add 5-7 ligneventes for each vente, no medicament repeats
            $numLignes = min(rand(5, 7), count($shuffledMedicamentIds));
            $total = 0;
            for ($i = 0; $i < $numLignes; $i++) {
                $montant = rand(10, 100) + rand(0, 99) / 100;
                LigneVente::create([
                    'vente_id'      => $vente->id,
                    'medicament_id' => $shuffledMedicamentIds[$i],
                    'quantite'      => rand(1, 5),
                    'montant'       => $montant,
                    'posologie'     => rand(1, 3),
                ]);
                $total += $montant;
            }

            // Update the vente total to the sum of its lignes (DB sum for accuracy)
            $sum = LigneVente::where('vente_id', $vente->id)->sum('montant');
            $vente->update(['total' => $sum]);
;
        }
    }
}
