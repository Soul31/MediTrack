<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Pharmacien;
use App\Models\Patient;
use App\Models\Docteur;
use Illuminate\Contracts\Auth\MustVerifyEmail;
class User extends Authenticatable  implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function pharmacien() {
        return $this->hasOne(Pharmacien::class);
    }
    public function patient() {
        return $this->hasOne(Patient::class);
    }
    public function docteur() {
        return $this->hasOne(Docteur::class);
    }

    public function getNameAttribute() {
        return "{$this->nom} {$this->prenom}";
    }


    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->role === 'pharmacien') {
                Pharmacien::create([
                    'licence' => '',
                    'user_id' => $user->id,
                ]);
            } elseif ($user->role === 'patient') {
                Patient::create([
                    'user_id' => $user->id,
                ]);
            } elseif ($user->role === 'docteur') {
                Docteur::create([
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
