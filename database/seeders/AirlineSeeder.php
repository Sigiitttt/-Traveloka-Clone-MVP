<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airline;

class AirlineSeeder extends Seeder
{
    public function run()
    {
        Airline::insert([
            ['code' => 'GA', 'name' => 'Garuda Indonesia'],
            ['code' => 'JT', 'name' => 'Lion Air'],
            ['code' => 'SJ', 'name' => 'Sriwijaya Air'],
        ]);
    }
}

