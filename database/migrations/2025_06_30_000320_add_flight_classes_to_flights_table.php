<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            // Tambahkan kolom baru untuk setiap kelas
            $table->decimal('price_economy', 10, 2)->after('price');
            $table->decimal('price_business', 10, 2)->after('price_economy');
            $table->integer('seats_economy')->after('available_seats');
            $table->integer('seats_business')->after('seats_economy');

            // Hapus kolom lama
            $table->dropColumn('price');
            $table->dropColumn('available_seats');
        });
    }

    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            // Logika untuk rollback jika diperlukan
            $table->decimal('price', 10, 2);
            $table->integer('available_seats');

            $table->dropColumn('price_economy');
            $table->dropColumn('price_business');
            $table->dropColumn('seats_economy');
            $table->dropColumn('seats_business');
        });
    }
};