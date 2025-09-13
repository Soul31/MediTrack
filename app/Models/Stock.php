<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    // Each stock belongs to a medicament
    protected $fillable = [
        'medicament_id',
        'quantité',
        'seuilMinimum',
        'date_peremption',
        'prix_achat',
        'prix_vente',
    ];
    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }
}
