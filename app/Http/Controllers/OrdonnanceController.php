<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\LigneOrdonnance;
use App\Models\Medicament;
use App\Models\Ordonnance;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdonnanceController extends Controller
{

    public function index(Ordonnance $ordonnance) {

        $lignes = $ordonnance->lignes;

        return view('patient.ordonnance')->with([
            'ordonnance' => $ordonnance,
            'lignes' => $lignes
        ]);
    }

    public function delete(Ordonnance $ordonnance) {
        $ordonnance->delete();
        if (Auth::user()->role === 'docteur') {
            return redirect()->route('docteur-ordonnances')->with('success', 'Ordonnance annulée!');
        } else if (Auth::user()->role === 'patient') {
            return redirect()->route('patient-ordonnances')->with('success', 'Ordonnance annulée!');
        } else {
            return redirect()->route('welcome')->with('success', 'Ordonnance annulée!');
        }
    }

    public function newOrdonnance() {
        $patients = Patient::all();
        $meds = Medicament::all();
        return view('docteur.new_ordonnance')->with([
            'patients' => $patients,
            'medicaments' => $meds
        ]);
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'medicaments' => 'required|array|min:1',
            'medicaments.*.id' => 'required|integer|exists:medicaments,id',
            'medicaments.*.quantite' => 'required|integer|min:1',
            'patient_id' => 'required|int|min:1'
        ]);
        // Get Patient By ID
        $patient = Patient::find($validated['patient_id']);
        // Get authenticated docteur
        $docteur = Auth::user()->docteur;
        if (!$patient) {
            return back()->with('error', 'Patient n\'existe pas!');
        }
        if (!$docteur) {
            return back()->with('error', 'Docteur n\'existe pas!');
        }

        return DB::transaction(function () use ($validated, $patient, $docteur) {
            try {
                // Create Ordonnance WITH patient_id
                $ordonnance = new Ordonnance([
                    'statut' => 'en attente',
                    'total' => 0
                ]);
                $ordonnance->dateCreation = today();
                $ordonnance->patient()->associate($patient);
                $ordonnance->docteur()->associate($docteur);
                $ordonnance->save();
                // Rest of your code...
                $total = 0.0;
                foreach ($validated['medicaments'] as $med) {
                    $medicament = Medicament::findOrFail($med['id']);
                    $ligneOrdonnance = new LigneOrdonnance([
                        'quantite' => $med['quantite'],
                        'montant' => $med['quantite'] * $medicament->prix
                    ]);
                    $total += $ligneOrdonnance->montant;
                    $ligneOrdonnance->ordonnance()->associate($ordonnance);
                    $ligneOrdonnance->medicament()->associate($medicament);
                    $ligneOrdonnance->save();
                }

                // Update total
                $ordonnance->update([
                    'total' => $total
                ]);
                return back()->with('success', 'Ordonnance créée!');

            } catch (\Exception $e) {
                return back()->withErrors('Erreur lors de la création de l\'ordonnance ! Réessayez.');
            }
        });

    }
}
