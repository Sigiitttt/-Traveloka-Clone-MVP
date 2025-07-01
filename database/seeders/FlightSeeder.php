<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flight;
use App\Models\Airline;
use App\Models\Airport;
use Carbon\Carbon;

class FlightSeeder extends Seeder
{
    public function run()
    {
        $airline = Airline::where('code', 'GA')->first();
        $origin = Airport::where('code', 'CGK')->first();
        $destination = Airport::where('code', 'DPS')->first();

        for ($i = 0; $i < 5; $i++) {
            Flight::create([
                'flight_number' => 'GA' . rand(100, 999),
                'airline_id' => $airline->id,
                'origin_airport_id' => $origin->id,
                'destination_airport_id' => $destination->id,
                'departure_time' => Carbon::tomorrow()->setHour(9), // Berangkat besok jam 9 pagi
                'arrival_time' => Carbon::tomorrow()->setHour(11), 
                'price_economy' => 1200000,
                'price_business' => 3500000,
                'seats_economy' => 150,
                'seats_business' => 20,
            ]);
        }
    }
}
