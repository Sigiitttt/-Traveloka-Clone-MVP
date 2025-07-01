<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airport; // Jangan lupa import model
use Illuminate\Http\Request;

class AirportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data airport dari database
        $airports = Airport::orderBy('city', 'asc')->get();

        // Kembalikan data dalam format JSON
        return response()->json([
            'message' => 'Success',
            'data' => $airports
        ]);
    }
}