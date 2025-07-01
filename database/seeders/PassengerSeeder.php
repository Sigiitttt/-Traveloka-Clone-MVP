<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Passenger;
use Faker\Factory as Faker;

class PassengerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $titles = ['Mr.', 'Mrs.', 'Ms.'];

        foreach (Booking::all() as $booking) {
            for ($i = 0; $i < $booking->total_passengers; $i++) {
                Passenger::create([
                    'booking_id' => $booking->id,
                    'title' => $faker->randomElement($titles),
                    'full_name' => $faker->name,
                    'date_of_birth' => $faker->date('Y-m-d', '2010-01-01'), // anak-anak atau dewasa
                ]);
            }
        }
    }
}
