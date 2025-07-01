<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Transactions';
    
    // Mencegah admin membuat booking baru dari panel ini
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form ini hanya untuk melihat detail, jadi kita buat `disabled`
                Forms\Components\TextInput::make('booking_code')->disabled(),
                Forms\Components\Select::make('flight_id')
                    ->relationship('flight', 'flight_number')
                    ->disabled(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->disabled(),
                Forms\Components\TextInput::make('flight_class')->disabled(),
                Forms\Components\TextInput::make('total_passengers')->disabled(),
                Forms\Components\TextInput::make('total_price')->money('IDR')->disabled(),
                // Status bisa diubah oleh admin
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('flight.flight_number')->label('Flight'),
                Tables\Columns\TextColumn::make('user.name')->label('User')->default('Guest'),
                Tables\Columns\TextColumn::make('flight_class')
                    ->badge() // Tampilkan sebagai badge agar menonjol
                    ->color(fn (string $state): string => match ($state) {
                        'business' => 'success',
                        'economy' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_price')->money('IDR')->sortable(),
                // Kolom status ini bisa diedit langsung dari tabel!
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            // Jika Anda ingin melihat daftar penumpang di halaman detail booking
            // RelationManagers\PassengersRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            // 'create' => Pages\CreateBooking::route('/create'), // Dihapus karena canCreate() false
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }    
}