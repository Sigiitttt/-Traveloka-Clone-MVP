<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Flight;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // ambil user pertama
        $flights = Flight::all();

        foreach ($flights as $flight) {
            $totalPassengers = rand(1, 3);
            $totalPrice = $flight->price * $totalPassengers;

            $booking = Booking::create([
                'user_id' => $user->id,
                'flight_id' => $flight->id,
                'flight_class' => 'economy', // Beri nilai default, misal 'economy'
                'price_per_ticket' => 1500000,
                'booking_code' => strtoupper(Str::random(6)),
                'total_passengers' => $totalPassengers,
                'total_price' => $totalPrice,
                'status' => 'confirmed',
            ]);

            // tambahkan penumpang (dalam PassengerSeeder)
        }
    }
}
