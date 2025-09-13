<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacien extends Model
{
    protected $fillable = [
        'licence', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
