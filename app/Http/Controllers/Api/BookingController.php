<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data
        $validator = Validator::make($request->all(), [
            'flight_id' => 'required|exists:flights,id',
            'flight_class' => 'required|in:economy,business',
            'passengers' => 'required|array|min:1',
            'passengers.*.title' => 'required|string',
            'passengers.*.full_name' => 'required|string|max:255',
            'passengers.*.date_of_birth' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $flight = Flight::find($request->flight_id);
        $flightClass = $request->flight_class;
        $totalPassengers = count($request->passengers);
        $pricePerTicket = 0;

        // 2. Cek ketersediaan kursi dan tentukan harga berdasarkan kelas
        if ($flightClass === 'economy') {
            if ($flight->seats_economy < $totalPassengers) {
                return response()->json(['message' => 'Kursi kelas Ekonomi tidak cukup.'], 400);
            }
            $pricePerTicket = $flight->price_economy;
        } else {
            if ($flight->seats_business < $totalPassengers) {
                return response()->json(['message' => 'Kursi kelas Bisnis tidak cukup.'], 400);
            }
            $pricePerTicket = $flight->price_business;
        }

        // 3. Mulai transaksi
        try {
            DB::beginTransaction();

            // Simpan data booking utama
            $booking = Booking::create([
                'flight_id' => $flight->id,
                'flight_class' => $flightClass,
                'price_per_ticket' => $pricePerTicket,
                'booking_code' => 'BOOK-' . Str::upper(Str::random(6)),
                'total_passengers' => $totalPassengers,
                'total_price' => $pricePerTicket * $totalPassengers,
                'status' => 'CONFIRMED',
            ]);

            // Simpan data penumpang
            foreach ($request->passengers as $passengerData) {
                $booking->passengers()->create($passengerData);
            }

            // Kurangi kursi tersedia berdasarkan kelas
            if ($flightClass === 'economy') {
                $flight->decrement('seats_economy', $totalPassengers);
            } else {
                $flight->decrement('seats_business', $totalPassengers);
            }

            DB::commit();

            return response()->json([
                'message' => 'Booking created successfully!',
                'data' => $booking->load('passengers', 'flight'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Booking failed!', 'error' => $e->getMessage()], 500);
        }
    }
}
