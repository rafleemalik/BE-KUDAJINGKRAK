<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Kolom dan properti lainnya
    protected $fillable = [
        'username',
        'name_web',
        'password',
        'role',
        'profile_photo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Pastikan tidak ada method yang hilang di sini!

    public function likedCars()
    {
        return $this->hasMany(LikedCar::class);
    }
}
