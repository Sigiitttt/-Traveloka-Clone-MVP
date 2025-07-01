<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlightResource\Pages;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Flight;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FlightResource extends Resource
{
    protected static ?string $model = Flight::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane'; // Ganti ikon di sidebar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('flight_number')
                    ->required()
                    ->maxLength(255),
                
                // Ganti TextInput untuk ID menjadi Dropdown (Select) yang lebih user-friendly
                Forms\Components\Select::make('airline_id')
                    ->relationship('airline', 'name') // Ambil relasi 'airline' dan tampilkan kolom 'name'
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Forms\Components\Select::make('origin_airport_id')
                    ->label('Origin Airport') // Beri label yang jelas
                    ->relationship('originAirport', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('destination_airport_id')
                    ->label('Destination Airport')
                    ->relationship('destinationAirport', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Forms\Components\DateTimePicker::make('departure_time')
                    ->required(),
                
                Forms\Components\DateTimePicker::make('arrival_time')
                    ->required(),
                
                // Gunakan TextInput numeric untuk harga
                Forms\Components\TextInput::make('price_economy')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),
                
                Forms\Components\TextInput::make('price_business')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),

                Forms\Components\TextInput::make('seats_economy')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('seats_business')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('flight_number')->searchable()->sortable(),
                // Tampilkan nama maskapai, bukan ID-nya
                Tables\Columns\TextColumn::make('airline.name')->sortable(),
                Tables\Columns\TextColumn::make('originAirport.code')->label('Origin'),
                Tables\Columns\TextColumn::make('destinationAirport.code')->label('Destination'),
                // Format tanggal dan harga agar lebih rapi
                Tables\Columns\TextColumn::make('departure_time')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('price_economy')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('price_business')->money('IDR')->sortable(),
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
        return [
            'index' => Pages\ListFlights::route('/'),
            'create' => Pages\CreateFlight::route('/create'),
            'edit' => Pages\EditFlight::route('/{record}/edit'),
        ];
    }
}