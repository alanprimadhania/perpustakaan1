<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengembalianResource\Pages;
use App\Models\Pengembalian;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

use Filament\Tables\Columns\TextColumn;

class PengembalianResource extends Resource
{
    protected static ?string $model = Pengembalian::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('peminjaman_id')
                ->relationship('peminjaman', 'kode_peminjaman')
                ->required(),

            DatePicker::make('tanggal_kembali_aktual')->required(),

            TextInput::make('keterlambatan')->disabled(),
            TextInput::make('denda_dibayar')->disabled(),

            Select::make('kondisi_buku')
                ->options([
                    'baik' => 'Baik',
                    'rusak' => 'Rusak',
                    'hilang' => 'Hilang',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('peminjaman.kode_peminjaman'),

                TextColumn::make('peminjaman.siswa.nis')
                    ->label('Siswa')
                    ->formatStateUsing(fn ($state, $record) => 
                        $state . ' - ' . ($record->peminjaman->siswa->user->name ?? '-')
                    ),

                TextColumn::make('peminjaman.buku.judul'),
                TextColumn::make('keterlambatan'),
                TextColumn::make('denda_dibayar'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengembalians::route('/'),
        ];
    }
}