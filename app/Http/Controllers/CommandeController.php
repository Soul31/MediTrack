<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Medicament;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommandeController extends Controller
{
    //

    public function storeCommande(Request $request)
    {
        $validated = $request->validate([
            'medicaments' => 'required|array|min:1',
            'medicaments.*.id' => 'required|integer|exists:medicaments,id',
            'medicaments.*.quantite' => 'required|integer|min:1'
        ]);

        // Get authenticated user's patient ID FIRST
        $patient = Auth::user()->patient;

        if (!$patient) {
            return back()->with('error', 'Patient profile not found');
        }

        return DB::transaction(function () use ($validated, $patient) {
            try {
                // Create commande WITH patient_id
                $commande = new Commande([
                    'statut' => 'en attente',
                    'total' => 0
                ]);
                $commande->patient()->associate($patient);
                $commande->save();
                $total = 0.0;
                // Rest of your code...
                foreach ($validated['medicaments'] as $med) {
                    $medicament = Medicament::findOrFail($med['id']);
                    $ligneCom = new LigneCommande([
                        'quantite' => $med['quantite'],
                        'montant' => $med['quantite'] * $medicament->prix
                    ]);
                    $total += $ligneCom->montant;
                    $ligneCom->commande()->associate($commande);
                    $ligneCom->medicament()->associate($medicament);
                    $ligneCom->save();
                }

                // Update total
                $commande->update([
                    'total' => $total
                ]);
                return back()->with('success', 'Commande créée!');

            } catch (\Exception $e) {
                return back()->withErrors('Erreur lors de la création de la commande ! Réessayez.');
            }
        });
    }

    public function index(Commande $commande) {
        $lignes = $commande->lignes;
        return view('patient.commande')->with(['commande' => $commande, 'lignes' => $lignes]);
    }

    public function delete(Commande $commande) {
        $commande->delete();
        return redirect()->route('patient-commandes')->with('success', 'Commande annulée!');
    }
}
