<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    /**
     * Properti yang diizinkan untuk diisi secara massal.
     */
    protected $fillable = [
        'flight_id',
        'booking_code',
        'flight_class', 
        'price_per_ticket',
        'total_passengers',
        'total_price',
        'status',
        'user_id',
    ];

    /**
     * Relasi: Satu pemesanan dimiliki oleh satu penerbangan.
     */
    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    /**
     * Relasi: Satu pemesanan dimiliki oleh satu pengguna (user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu pemesanan memiliki banyak penumpang.
     */
    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }
}