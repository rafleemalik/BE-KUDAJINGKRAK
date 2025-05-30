<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'type',
        'specifications'
    ];

    protected $casts = [
        'price' => 'float'
    ];

    public function likedBy()
    {
        return $this->hasMany(LikedCar::class);
    }
}
