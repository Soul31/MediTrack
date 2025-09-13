<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $fillable = [
        'type',
        'creation_time',
        'status',
        'total',
        'payment_method',
        'patient_name',
        'patient_id',
        'raw_id',
    ];

    // If this order is a commande, link to the patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // If you want to link back to the original commande
    public function commande()
    {
        return $this->belongsTo(Commande::class, 'raw_id');
    }

    // If you want to link back to the original vente
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'raw_id');
    }
}
