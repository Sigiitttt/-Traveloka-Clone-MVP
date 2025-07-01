<?php

namespace App\Filament\Resources\ItineraryResource\Pages;

use App\Filament\Resources\ItineraryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditItinerary extends EditRecord
{
    protected static string $resource = ItineraryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // Method untuk mengisi form dengan data yang sudah ada
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ambil relasi flights dan format agar sesuai dengan struktur Repeater
        $data['flights'] = $this->record->flights->map(fn($flight) => ['flight_id' => $flight->id])->toArray();
        return $data;
    }

    // Method untuk menyimpan data setelah diedit
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $flightData = $data['flights'];
        unset($data['flights']);

        // Update record Itinerary utama (jika ada perubahan)
        $record->update($data);

        // Siapkan data untuk tabel pivot
        $syncData = [];
        foreach ($flightData as $index => $flight) {
            $syncData[$flight['flight_id']] = ['segment_order' => $index + 1];
        }

        // Gunakan sync untuk update relasi
        $record->flights()->sync($syncData);

        return $record;
    }
}