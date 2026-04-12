<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BukuResource\Pages;
use App\Models\Buku;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

// ✅ IMPORT FORM
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

// ✅ IMPORT TABLE
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

// ✅ ACTIONS
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

class BukuResource extends Resource
{
    protected static ?string $model = Buku::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Buku';
    protected static ?string $pluralModelLabel = 'Buku';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('kode_buku')
                ->required()
                ->unique(ignoreRecord: true),
        
            TextInput::make('judul')->required(),
        
            Select::make('kategori_id')
                ->relationship('kategori', 'nama_kategori')
                ->searchable()
                ->preload()
                ->required(),
        
            TextInput::make('penulis')->required(),
            TextInput::make('penerbit')->required(),
        
            TextInput::make('tahun_terbit')
                ->numeric()
                ->required(),
        
            TextInput::make('isbn')->nullable(),
        
            TextInput::make('jumlah_halaman')
                ->numeric()
                ->nullable(),
        
            TextInput::make('stok')
                ->numeric()
                ->required(),
        
            TextInput::make('lokasi_rak')
                ->nullable(),
        
            \Filament\Forms\Components\Textarea::make('deskripsi')
                ->rows(3)
                ->nullable(),
        
            FileUpload::make('cover_image')
                ->image()
                ->directory('cover-buku')
                ->disk('public')
                ->visibility('public')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->getStateUsing(fn ($record) => asset('storage/' . $record->cover_image))
                    ->circular()
                    ->label('Cover'),
            
                TextColumn::make('kode_buku')->searchable(),
                TextColumn::make('judul')->searchable(),
                TextColumn::make('kategori.nama_kategori')->label('Kategori'),
            
                TextColumn::make('penulis')->toggleable(),
                // TextColumn::make('penerbit')->toggleable(),
                TextColumn::make('tahun_terbit'),
            
                TextColumn::make('isbn')->toggleable(),
                TextColumn::make('jumlah_halaman')->label('Halaman')
                    ->toggleable(),
            
                TextColumn::make('stok')
                    ->badge()
                    ->color(fn ($state) => $state == 0 ? 'danger' : 'success'),
                TextColumn::make('lokasi_rak')->label('Rak')
                    ->toggleable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBukus::route('/'),
            'create' => Pages\CreateBuku::route('/create'),
            'edit' => Pages\EditBuku::route('/{record}/edit'),
        ];
    }
}
