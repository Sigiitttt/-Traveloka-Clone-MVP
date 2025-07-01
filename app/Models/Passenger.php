<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passenger extends Model
{
    use HasFactory;

    /**
     * Properti yang diizinkan untuk diisi secara massal.
     */
    protected $fillable = [
        'booking_id',
        'title',
        'full_name',
        'date_of_birth',
    ];

    /**
     * Relasi: Satu penumpang dimiliki oleh satu pemesanan.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}