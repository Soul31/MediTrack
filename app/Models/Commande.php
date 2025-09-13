<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    //
    protected $fillable = [
        'statut',
        'total',
        'patient_id'
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneCommande::class);
    }

    protected static function booted()
    {
        static::saving(function ($commande) {
            $commande->total = $commande->lignes()->sum('montant');
        });
        static::created(function ($commande) {
            \App\Models\order::create([
                'type'           => 'commande',
                'creation_time'  => $commande->created_at,
                'status'         => $commande->statut,
                'total'          => $commande->total,
                'payment_method' => 'Paiment online',
                'patient_name'   => $commande->patient && $commande->patient->user
                                    ? $commande->patient->user->nom . ' ' . $commande->patient->user->prenom
                                    : 'N/A',
                'patient_id'     => $commande->patient_id,
                'raw_id'         => $commande->id,
            ]);
        });
    }
}
