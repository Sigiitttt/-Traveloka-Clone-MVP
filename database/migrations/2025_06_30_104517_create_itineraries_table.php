<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('itineraries', function (Blueprint $table) {
        $table->id();
        // Untuk saat ini, tabel ini sederhana. Bisa ditambahkan info lain nanti.
        $table->timestamps();
    });
}
public function down(): void {
    Schema::dropIfExists('itineraries');
}
};
