<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docteur extends Model
{
    //
    protected $fillable = [
        'numeroLicence',
        'specialite'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }
}
