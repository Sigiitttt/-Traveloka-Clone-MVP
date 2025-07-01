<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Airport extends Model
{
    use HasFactory;

    /**
     * Properti yang diizinkan untuk diisi secara massal.
     */
    protected $fillable = [
        'code',
        'name',
        'city',
    ];

    /**
     * Relasi: Satu bandara memiliki banyak penerbangan yang berangkat dari sini.
     */
    public function departures(): HasMany
    {
        return $this->hasMany(Flight::class, 'origin_airport_id');
    }

    /**
     * Relasi: Satu bandara memiliki banyak penerbangan yang tiba di sini.
     */
    public function arrivals(): HasMany
    {
        return $this->hasMany(Flight::class, 'destination_airport_id');
    }
}