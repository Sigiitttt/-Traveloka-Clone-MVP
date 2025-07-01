<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Flight;
use App\Models\Itinerary;
use App\Models\Airport; // 1. Impor model Airport

class ItinerarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2. Hapus data lama untuk memastikan kebersihan data setiap kali seeder dijalankan
        Itinerary::query()->delete();

        // 3. Cari model Airport berdasarkan kode uniknya, bukan ID
        $cgk = Airport::where('code', 'CGK')->first();
        $dps = Airport::where('code', 'DPS')->first();
        $sub = Airport::where('code', 'SUB')->first();
        // Anda bisa tambahkan bandara lain di sini jika perlu

        // Pastikan semua bandara yang dibutuhkan ada sebelum melanjutkan
        if (!($cgk && $dps && $sub)) {
            $this->command->error('SEEDER FAILED: Pastikan bandara dengan kode CGK, DPS, dan SUB sudah ada di database.');
            return; // Hentikan seeder jika bandara tidak ditemukan
        }

        // --- Skenario 1: Membuat Itinerary untuk Penerbangan Langsung (CGK -> DPS) ---
        // Gunakan variabel model airport yang sudah kita temukan
        $directFlight = Flight::where('origin_airport_id', $cgk->id)
                              ->where('destination_airport_id', $dps->id)
                              ->first();

        if ($directFlight) {
            $itineraryDirect = Itinerary::create();
            $itineraryDirect->flights()->attach($directFlight->id, ['segment_order' => 1]);
            $this->command->info('SUCCESS: Itinerary untuk penerbangan langsung (CGK-DPS) berhasil dibuat.');
        } else {
            $this->command->error('FAILED: Penerbangan langsung (CGK-DPS) tidak ditemukan di tabel flights. Itinerary tidak dibuat.');
        }

        // --- Skenario 2: Membuat Itinerary untuk Penerbangan Transit (CGK -> SUB -> DPS) ---
        $transitSegment1 = Flight::where('origin_airport_id', $cgk->id)
                                 ->where('destination_airport_id', $sub->id)
                                 ->first();

        $transitSegment2 = Flight::where('origin_airport_id', $sub->id)
                                 ->where('destination_airport_id', $dps->id)
                                 ->first();

        if ($transitSegment1 && $transitSegment2) {
            $itineraryTransit = Itinerary::create();
            $itineraryTransit->flights()->attach([
                $transitSegment1->id => ['segment_order' => 1],
                $transitSegment2->id => ['segment_order' => 2],
            ]);
            $this->command->info('SUCCESS: Itinerary untuk penerbangan transit (CGK-SUB-DPS) berhasil dibuat.');
        } else {
            $this->command->error('FAILED: Salah satu atau kedua segmen transit (CGK-SUB atau SUB-DPS) tidak ditemukan. Itinerary transit tidak dibuat.');
        }
    }
}