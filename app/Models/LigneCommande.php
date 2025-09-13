<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model
{
    //
    protected $fillable = [
        'quantite',
        'montant',
        'medicament_id',
        'commande_id',
    ];
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }

    protected static function booted()
    {
        static::saving(function ($ligne) {
            $ligne->montant = $ligne->quantite * $ligne->medicament->prix;
        });

        static::saved(function ($ligne) {
            $ligne->commande->save();
        });


        static::created(function ($ligne) {
            $order = \App\Models\order::where('type', 'commande')->where('raw_id', $ligne->commande_id)->first();
            \App\Models\ligneOrder::create([
                'order_id'      => $order ? $order->id : null,
                'type'          => 'commande',
                'raw_id'        => $ligne->id,
                'medicament_id' => $ligne->medicament_id,
                'quantite'      => $ligne->quantite,
                'montant'       => $ligne->montant,
                'posologie'     => $ligne->posologie ?? null,
            ]);
        });

        static::updated(function ($ligne) {
            $ligneOrder = \App\Models\ligneOrder::where('type', 'commande')->where('raw_id', $ligne->id)->first();
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
            \App\Models\ligneOrder::where('type', 'commande')->where('raw_id', $ligne->id)->delete();
        });
    }
}
