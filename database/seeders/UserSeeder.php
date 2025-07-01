<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // <-- ini penting

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@traveloka.com',
            'password' => bcrypt('password123'),
            'role' => 'admin', // pakai 'role' jika kamu sudah menambahkan kolom role
            // 'is_admin' => true, // jika kamu pakai kolom ini juga
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@traveloka.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);
    }
}
