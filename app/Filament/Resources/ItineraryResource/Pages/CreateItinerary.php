<?php

namespace App\Filament\Resources\ItineraryResource\Pages;

use App\Filament\Resources\ItineraryResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateItinerary extends CreateRecord
{
    protected static string $resource = ItineraryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // 1. Simpan data Repeater ke variabel sementara
        $flightData = $data['flights'];
        // 2. Hapus data 'flights' dari array utama agar tidak error saat membuat Itinerary
        unset($data['flights']);
        
        // 3. Buat record Itinerary utama dengan data yang tersisa
        $record = static::getModel()::create($data);

        // 4. Siapkan data untuk tabel pivot
        $syncData = [];
        foreach ($flightData as $index => $flight) {
            $syncData[$flight['flight_id']] = ['segment_order' => $index + 1];
        }

        // 5. Gunakan method sync() untuk menyimpan relasi dan data pivot
        $record->flights()->sync($syncData);

        return $record;
    }
}