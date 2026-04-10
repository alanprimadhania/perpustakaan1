<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Models\Siswa;
use App\Models\User;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

// FORM
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

// TABLE
use Filament\Tables\Columns\TextColumn;

// ACTIONS
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;


class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $pluralModelLabel = 'Siswa';

    public static function form(Form $form): Form
    {
        return $form->schema([

            // 🔥 DATA USER
            TextInput::make('name')
                ->required()
                ->label('Nama'),

            TextInput::make('email')
                ->email()
                ->required()
                ->unique(table: User::class, column: 'email'),

            TextInput::make('password')
                ->password()
                ->required()
                ->minLength(6)
                ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                ->dehydrated(fn ($state) => filled($state)),

            // 🔥 DATA SISWA
            TextInput::make('nis')
                ->required()
                ->unique(ignoreRecord: true)
                ->label('NIS'),

            TextInput::make('kelas')
                ->required(),

            TextInput::make('jurusan')
                ->nullable(),

            DatePicker::make('tanggal_lahir')
                ->nullable(),

            Select::make('status')
                ->options([
                    'aktif' => 'Aktif',
                    'lulus' => 'Lulus',
                    'keluar' => 'Keluar',
                ])
                ->default('aktif')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Nama'),
                TextColumn::make('nis')->searchable(),
                TextColumn::make('kelas'),
                TextColumn::make('jurusan'),
                TextColumn::make('status'),
                TextColumn::make('created_at')->dateTime()->label('Dibuat'),
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}