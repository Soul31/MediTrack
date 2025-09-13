<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Commande;
use App\Models\Vente;
use App\Models\LigneCommande;
use App\Models\LigneVente;

class orderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed commandes
        $commandes = Commande::with('patient.user')->get();
        foreach ($commandes as $commande) {
            $orderId = DB::table('orders')->insertGetId([
                'type'           => 'commande',
                'creation_time'  => $commande->dateCreation,
                'status'         => $commande->statut,
                'total'          => $commande->total,
                'payment_method' => 'Paiment online',
                'patient_name'   => ($commande->patient && $commande->patient->user) ? $commande->patient->user->nom . ' ' . $commande->patient->user->prenom : 'N/A',
                'patient_id'     => $commande->patient_id,
                'raw_id'         => $commande->id,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Pull lignecommandes and insert into ligne_orders
            $lignes = LigneCommande::where('commande_id', $commande->id)->get();
            foreach ($lignes as $ligne) {
                DB::table('ligne_orders')->insert([
                    'order_id'      => $orderId,
                    'type'          => 'commande',
                    'raw_id'        => $ligne->id,
                    'medicament_id' => $ligne->medicament_id,
                    'quantite'      => $ligne->quantite,
                    'montant'       => $ligne->montant,
                    'posologie'     => $ligne->posologie ?? null,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }

            // --- FIX: Update order total to sum of its ligne_orders ---
            $orderTotal = DB::table('ligne_orders')
                ->where('order_id', $orderId)
                ->sum('montant');
            DB::table('orders')
                ->where('id', $orderId)
                ->update(['total' => $orderTotal]);
        }

        // Seed ventes
        $ventes = Vente::all();
        foreach ($ventes as $vente) {
            $orderId = DB::table('orders')->insertGetId([
                'type'           => 'vente',
                'creation_time'  => $vente->dateCreation,
                'status'         => $vente->statut ?? 'N/A',
                'total'          => $vente->total ?? 0,
                'payment_method' => 'Paiment sur place',
                'patient_name'   => $vente->patient_name ?? 'N/A',
                'patient_id'     => null,
                'raw_id'         => $vente->id,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Pull ligneventes and insert into ligne_orders
            $lignes = LigneVente::where('vente_id', $vente->id)->get();
            foreach ($lignes as $ligne) {
                DB::table('ligne_orders')->insert([
                    'order_id'      => $orderId,
                    'type'          => 'vente',
                    'raw_id'        => $ligne->id,
                    'medicament_id' => $ligne->medicament_id,
                    'quantite'      => $ligne->quantite,
                    'montant'       => $ligne->montant,
                    'posologie'     => $ligne->posologie ?? null,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }

            // --- FIX: Update order total to sum of its ligne_orders ---
            $orderTotal = DB::table('ligne_orders')
                ->where('order_id', $orderId)
                ->sum('montant');
            DB::table('orders')
                ->where('id', $orderId)
                ->update(['total' => $orderTotal]);
        }
    }
}
