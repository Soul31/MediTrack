<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Medicament;
use App\Models\Ordonnance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Command\Command;

class PatientController extends Controller
{
    //
    public function medicaments() {
        $meds = Medicament::all();
        return view('patient.medicaments')->with('medicaments', $meds);
    }
    public function commandes() {
        $patient = Auth::user()->patient;
        $coms = $patient->commandes;
        return view('patient.commandes')->with('commandes', $coms);
    }

    public function ordonnances() {
        $patient = Auth::user()->patient;
        $ords = $patient->ordonnances;
        return view('patient.ordonnances')->with('ordonnances', $ords);
    }

    public function point_de_vente() {
        $meds = Medicament::all()->where('necessiteOrdonnance', false);
        return view('patient.point_de_vente')->with('medicaments', $meds);
    }
}
