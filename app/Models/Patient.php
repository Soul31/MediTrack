<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //
    protected $fillable = [
        'adresse', 'user_id',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }
}
