<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // 1. Impor library Carbon untuk penanganan tanggal

class ItineraryController extends Controller
{
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required|string|max:3',
            'to' => 'required|string|max:3',
            'date' => 'required|date',
            'class' => 'required|string|in:economy,business',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $itineraries = Itinerary::query()
            // Bagian 1: Filter awal di database untuk mempersempit hasil
            ->whereHas('flights', function($flightQuery) use ($request){
                // Parsing tanggal dari request menggunakan Carbon
                $date = Carbon::parse($request->date);

                $flightQuery->where('segment_order', 1) // Cek segmen pertama
                            ->whereHas('originAirport', fn($q) => $q->where('code', $request->from)) // Cocokkan bandara asal
                            
                            // 2. PERBAIKAN UTAMA: Gunakan whereBetween untuk filter tanggal yang andal
                            ->whereBetween('departure_time', [$date->startOfDay(), $date->endOfDay()]);
            })
            // Ambil semua data relasi yang dibutuhkan oleh frontend
            ->with(['flights.airline', 'flights.originAirport', 'flights.destinationAirport'])
            ->get()

            // Bagian 2: Filter hasil dari database menggunakan logika PHP
            ->filter(function ($itinerary) use ($request) {
                // Pastikan itinerary punya segmen penerbangan
                if ($itinerary->flights->isEmpty()) {
                    return false;
                }
                
                // Ambil segmen terakhir dari rute perjalanan
                $lastFlight = $itinerary->flights->last();

                // Cocokkan bandara tujuan dari segmen terakhir
                return $lastFlight->destinationAirport && $lastFlight->destinationAirport->code === $request->to;
            });

        return response()->json([
            'message' => 'Search results',
            // values() untuk mereset index array setelah proses filter
            'data' => $itineraries->values(), 
        ]);
    }
}