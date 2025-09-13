<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    protected $fillable = [
        'statut',
        'total',
        'patient_id',
        'docteur_id'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function docteur()
    {
        return $this->belongsTo(Docteur::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneOrdonnance::class);
    }

    public static function booted() {
        static::saving(function ($ordonnance) {
            $ordonnance->total = $ordonnance->lignes()->sum('montant');
        });
    }
}
