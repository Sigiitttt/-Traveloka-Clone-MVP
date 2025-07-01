<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// app/Models/Itinerary.php
class Itinerary extends Model
{
    use HasFactory;

    /**
     * Relasi Many-to-Many ke Flight
     */
    public function flights(): BelongsToMany
    {
        return $this->belongsToMany(Flight::class, 'itinerary_flight')
                    ->withPivot('segment_order') // Ambil juga data dari kolom pivot
                    ->orderBy('itinerary_flight.segment_order', 'asc');
    }
}