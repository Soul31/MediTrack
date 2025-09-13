<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ligneOrder extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'raw_id',
        'medicament_id',
        'quantite',
        'montant',
        'posologie',
    ];

    public function order()
    {
        return $this->belongsTo(order::class);
    }

    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }

    // Optionally, link back to the original ligne_commande or ligne_vente
    public function ligneCommande()
    {
        return $this->belongsTo(LigneCommande::class, 'raw_id');
    }

    public function ligneVente()
    {
        return $this->belongsTo(LigneVente::class, 'raw_id');
    }
}
