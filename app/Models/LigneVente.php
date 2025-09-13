<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class LigneVente extends Model
{

    protected $fillable = [
        'montant',
        'quantite',
        'medicament_id',
        'vente_id',
        'posologie'
    ];

    public function vente() {
        return $this->belongsTo(Vente::class);
    }

    public function medicament() {
        return $this->belongsTo(Medicament::class);
    }

    protected static function booted()
    {
        static::created(function ($ligne) {
            $order = \App\Models\order::where('type', 'vente')->where('raw_id', $ligne->vente_id)->first();
            \App\Models\ligneOrder::create([
                'order_id'      => $order ? $order->id : null,
                'type'          => 'vente',
                'raw_id'        => $ligne->id,
                'medicament_id' => $ligne->medicament_id,
                'quantite'      => $ligne->quantite,
                'montant'       => $ligne->montant,
                'posologie'     => $ligne->posologie ?? null,
            ]);
        });

        static::updated(function ($ligne) {
            $ligneOrder = \App\Models\ligneOrder::where('type', 'vente')->where('raw_id', $ligne->id)->first();
            if ($ligneOrder) {
                $ligneOrder->update([
                    'medicament_id' => $ligne->medicament_id,
                    'quantite'      => $ligne->quantite,
                    'montant'       => $ligne->montant,
                    'posologie'     => $ligne->posologie ?? null,
                ]);
            }
        });

        static::deleted(function ($ligne) {
            \App\Models\ligneOrder::where('type', 'vente')->where('raw_id', $ligne->id)->delete();
        });

        static::saving(function ($ligne) {
            $ligne->montant = $ligne->quantite * $ligne->medicament->prix;
        });

        static::saved(function ($ligne) {
            $ligne->vente->save();
        });
    }
}
