<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItineraryResource\Pages;
use App\Models\Flight;
use App\Models\Itinerary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ItineraryResource extends Resource
{
    protected static ?string $model = Itinerary::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rangkaian Penerbangan (Segments)')
                    ->description('Pilih dan urutkan segmen penerbangan untuk membuat satu rute perjalanan.')
                    ->schema([
                        // Repeater ini sekarang hanya mengelola array di frontend
                        Forms\Components\Repeater::make('flights')
                            ->label('Segmen Penerbangan')
                            ->schema([
                                // Nama field ini bebas, karena hanya untuk data sementara
                                Forms\Components\Select::make('flight_id') 
                                    ->label('Pilih Penerbangan')
                                    ->options(Flight::all()->pluck('flight_number_with_route', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                            ])
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Segmen (untuk Transit)')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        // Bagian table tidak perlu diubah, sudah benar
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('full_route')
                    ->label('Rute Perjalanan')
                    ->getStateUsing(function (Itinerary $record) {
                        $flights = $record->flights;
                        if ($flights->isEmpty()) return 'Tidak ada segmen';
                        
                        $routeCodes = $flights->pluck('originAirport.code')->push($flights->last()->destinationAirport->code);
                        return $routeCodes->implode(' ➔ ');
                    }),
                Tables\Columns\TextColumn::make('flights_count')->counts('flights')->label('Total Segmen')->badge(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getPages(): array
    {
        // Pastikan path ke halaman sudah benar
        return [
            'index' => Pages\ListItineraries::route('/'),
            'create' => Pages\CreateItinerary::route('/create'),
            'edit' => Pages\EditItinerary::route('/{record}/edit'),
        ];
    }    
}