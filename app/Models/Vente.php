<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{

    protected $fillable = [
        'statut',
        'total',
        'patient_id',
    ];

        public function pharmacien()
    {
        return $this->belongsTo(Pharmacien::class);
    }
    public function lignes() {
        return $this->hasMany(LigneVente::class);
    }

    protected static function booted()
{
    static::created(function ($vente) {
        \App\Models\order::create([
            'type'           => 'vente',
            'creation_time'  => $vente->created_at,
            'status'         => $vente->statut ?? 'N/A',
            'total'          => $vente->total ?? 0,
            'payment_method' => 'Paiment sur place',
            'patient_name'   => $vente->patient_name ?? 'N/A',
            'patient_id'     => null,
            'raw_id'         => $vente->id,
        ]);
    });

    static::saving(function ($vente) {
        $vente->total = $vente->lignes()->sum('montant');
    });
}
}
