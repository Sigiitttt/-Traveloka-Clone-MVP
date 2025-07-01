<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Flight extends Model
{
    use HasFactory;

    /**
     * Properti yang diizinkan untuk diisi secara massal.
     *
     */
     protected function flightNumberWithRoute(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->flight_number} ({$this->originAirport->code} -> {$this->destinationAirport->code})",
        );
    }

    protected $fillable = [
        'flight_number',
        'airline_id',
        'origin_airport_id',
        'destination_airport_id',
        'departure_time',
        'arrival_time',
        'available_seats',
        'price_economy',
        'price_business',
        'seats_economy',
        'seats_business',
    ];

    /**
     * Mengubah tipe data default untuk beberapa kolom.
     */
    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'price_economy' => 'float',
        'price_business' => 'float',
    ];

    /**
     * Relasi: Satu penerbangan dimiliki oleh satu maskapai.
     */
    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    /**
     * Relasi: Satu penerbangan dimiliki oleh satu bandara asal.
     */
    public function originAirport(): BelongsTo
    {
        // 'origin_airport_id' adalah foreign key di tabel flights
        return $this->belongsTo(Airport::class, 'origin_airport_id');
    }

    /**
     * Relasi: Satu penerbangan dimiliki oleh satu bandara tujuan.
     */
    public function destinationAirport(): BelongsTo
    {
        // 'destination_airport_id' adalah foreign key di tabel flights
        return $this->belongsTo(Airport::class, 'destination_airport_id');
    }
    
    /**
     * Relasi: Satu penerbangan memiliki banyak pemesanan.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

   // app/Models/Flight.php
    public function itineraries(): BelongsToMany {
        return $this->belongsToMany(Itinerary::class, 'itinerary_flight');
    }
}