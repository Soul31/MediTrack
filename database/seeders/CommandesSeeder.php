<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Medicament;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class CommandesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example patient IDs (make sure these exist in your DB)
        $patientIds = [1, 2, 3, 4, 5];
        $statuses = ['en attente', 'valide', 'livre', 'refus'];
        $medicamentIds = Medicament::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            // Shuffle medicament IDs to avoid repeats
            $shuffledMedicamentIds = $medicamentIds;
            shuffle($shuffledMedicamentIds);

            $numLignes = min(rand(5, 7), count($shuffledMedicamentIds)); // Ensure we don't exceed available medicaments

            // Create commande with temporary total (will update after lignes)
            $commande = Commande::create([
                'statut'     => Arr::random($statuses),
                'total'      => 0, // Will update after lignes
                'patient_id' => Arr::random($patientIds),
                'dateCreation' => Carbon::create(2025, rand(1, 12), rand(1, 28), rand(0, 23), rand(0, 59)),
            ]);

            $total = 0;
            for ($j = 0; $j < $numLignes; $j++) {
                $montant = rand(10, 100) + rand(0, 99) / 100;
                $ligne = LigneCommande::create([
                    'commande_id'   => $commande->id,
                    'medicament_id' => $shuffledMedicamentIds[$j],
                    'quantite'      => rand(1, 5),
                    'montant'       => $montant,
                    'posologie'     => rand(1, 3),
                ]);
                $total += $montant;
            }

            // Update the commande total to the sum of its lignes (DB sum for accuracy)
            $sum = LigneCommande::where('commande_id', $commande->id)->sum('montant');
            $commande->update(['total' => $sum]);
        }
    }
}
