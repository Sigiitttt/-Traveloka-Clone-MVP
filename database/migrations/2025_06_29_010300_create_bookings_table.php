<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Method ini akan dijalankan saat Anda mengetik `php artisan migrate`
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Kunci Asing (Foreign Keys)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('flight_id')->constrained()->onDelete('cascade');

            // --- KOLOM BARU YANG DITAMBAHKAN ---
            $table->string('flight_class'); // Akan diisi 'economy' atau 'business'
            $table->decimal('price_per_ticket', 10, 2); // Harga satuan tiket saat booking
            // --- END OF KOLOM BARU ---

            // Detail Booking Utama
            $table->string('booking_code')->unique(); // e.g. XV3TRK
            $table->integer('total_passengers');
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Method ini akan dijalankan saat Anda mengetik `php artisan migrate:rollback`
     * Kebalikan dari membuat tabel adalah menghapus tabel.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};