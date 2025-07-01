<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_itinerary_flight_table.php
public function up(): void {
    Schema::create('itinerary_flight', function (Blueprint $table) {
        $table->id();
        $table->foreignId('itinerary_id')->constrained()->onDelete('cascade');
        $table->foreignId('flight_id')->constrained()->onDelete('cascade');
        $table->integer('segment_order'); // Untuk menentukan urutan penerbangan (1, 2, dst.)
        $table->timestamps();
    });
}
public function down(): void {
    Schema::dropIfExists('itinerary_flight');
}
};
