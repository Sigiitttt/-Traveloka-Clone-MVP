<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight; // Import model Flight
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Import Validator

class FlightController extends Controller
{
    // Fungsi untuk PENCARIAN PENERBANGAN
    public function search(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'from' => 'required|string|max:3', // e.g., CGK
            'to' => 'required|string|max:3',   // e.g., DPS
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Bangun Query ke Database
        $flights = Flight::query()
            // Filter berdasarkan kode bandara asal (origin)
            ->whereHas('originAirport', function ($query) use ($request) {
                $query->where('code', $request->from);
            })
            // Filter berdasarkan kode bandara tujuan (destination)
            ->whereHas('destinationAirport', function ($query) use ($request) {
                $query->where('code', $request->to);
            })
            // Filter berdasarkan tanggal keberangkatan
            ->whereDate('departure_time', $request->date)
            // Ambil juga relasi datanya agar tidak query berulang-ulang (Eager Loading)
            ->with(['airline', 'originAirport', 'destinationAirport'])
            ->orderBy('departure_time', 'asc')
            ->get();

        // 3. Kembalikan Hasil
        return response()->json([
            'message' => 'Search results',
            'data' => $flights
        ]);
    }

    // Fungsi untuk DETAIL SATU PENERBANGAN
    public function show(Flight $flight)
    {
        // Laravel otomatis akan mencari Flight berdasarkan ID di URL (Route Model Binding)
        // Load relasi agar datanya lengkap
        $flight->load(['airline', 'originAirport', 'destinationAirport']);

        return response()->json([
            'message' => 'Flight detail',
            'data' => $flight
        ]);
    }
}