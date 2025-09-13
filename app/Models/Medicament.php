<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    //
    protected  $fillable = [
        'nom',
        'detailles',
        'prix',
        'dosage',
        'necessiteOrdonnance'
    ];

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    public function ligneOrdonnances()
    {
        return $this->hasMany(LigneOrdonnance::class);
    }

    public function stock()
    {
        return $this->hasOne(\App\Models\Stock::class, 'medicament_id');
    }
}
