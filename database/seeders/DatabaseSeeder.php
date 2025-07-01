<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

    $user = User::firstOrCreate(
        ['email' => 'test@example.com'],
        [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]
    );

    $this->call([
        AirportSeeder::class,
        AirlineSeeder::class,
        FlightSeeder::class,
        BookingSeeder::class,
        PassengerSeeder::class,
        FlightSeeder::class,
        UserSeeder::class    ]);

        }
}
