<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AirportController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ItineraryController;


// Rute untuk mendapatkan data master (tidak perlu login)
Route::get('/airports', [AirportController::class, 'index']);

// Rute yang berhubungan dengan Penerbangan
Route::get('/itineraries/search', [ItineraryController::class, 'search']);
Route::get('/flights/{flight}', [FlightController::class, 'show']); // Endpoint untuk detail 1 penerbangan

// Rute untuk membuat pemesanan baru
Route::post('/bookings', [BookingController::class, 'store']);

// Contoh rute yang membutuhkan login (jika nanti Anda implementasi)
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });