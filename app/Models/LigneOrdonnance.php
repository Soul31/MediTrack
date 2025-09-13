<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneOrdonnance extends Model
{
    protected $fillable = [
        'quantite',
        'montant',
        'medicament_id',
        'ordonnance_id',
    ];

    public function ordonnance()
    {
        return $this->belongsTo(Ordonnance::class);
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }

    public static function booted() {
        static::saving(function ($ligne) {
            $ligne->montant = $ligne->quantite * $ligne->medicament->prix;
        });

        static::saved(function ($ligne) {
            $ligne->ordonnance->save();
        });

    }
}
