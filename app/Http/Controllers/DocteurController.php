<?php

namespace App\Http\Controllers;

use App\Models\Medicament;
use App\Models\Ordonnance;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DocteurController extends Controller
{

    public function medicaments() {

        $meds = Medicament::all();

        return view('docteur.medicaments')->with('medicaments', $meds);
    }

    public function ordonnances() {
        $docteur = Auth::user()->docteur;
        $ords = $docteur->ordonnances;
        return view('docteur.ordonnances')->with('ordonnances', $ords);
    }

    public function ordonnance(Ordonnance $ordonnance) {

        return view('docteur.ordonnance')->with('ordonnance', $ordonnance);
    }
}
