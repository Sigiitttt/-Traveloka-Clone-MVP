<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Airline extends Model
{
    use HasFactory;

    /**
     * Properti yang diizinkan untuk diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Relasi: Satu maskapai memiliki banyak penerbangan.
     */
    public function flights(): HasMany
    {
        return $this->hasMany(Flight::class);
    }
}