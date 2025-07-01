<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    public function run()
    {
        Airport::insert([
            ['code' => 'CGK', 'name' => 'Soekarno-Hatta International Airport', 'city' => 'Jakarta'],
            ['code' => 'DPS', 'name' => 'Ngurah Rai International Airport', 'city' => 'Bali'],
            ['code' => 'SUB', 'name' => 'Juanda International Airport', 'city' => 'Surabaya'],
            ['code' => 'UPG', 'name' => 'Sultan Hasanuddin International Airport', 'city' => 'Makassar'],
        ]);
    }
}
