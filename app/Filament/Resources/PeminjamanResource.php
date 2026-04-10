<?php 

namespace App\Filament\Resources;

use App\Filament\Resources\PeminjamanResource\Pages;
use App\Models\Peminjaman;
use App\Models\Buku;

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
use Filament\Tables\Actions\Action;

// NOTIF
use Filament\Notifications\Notification;

class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationLabel = 'Peminjaman';
    protected static ?string $pluralModelLabel = 'Peminjaman';

    public static function form(Form $form): Form
    {
        return $form->schema([

            TextInput::make('kode_peminjaman')
                ->required()
                ->unique(ignoreRecord: true),

            Select::make('siswa_id')
                ->relationship('siswa', 'nis')
                ->searchable()
                ->required(),

            Select::make('buku_id')
                ->relationship('buku', 'judul')
                ->searchable()
                ->preload()
                ->required(),

            DatePicker::make('tanggal_pinjam')
                ->required(),

            DatePicker::make('batas_pengembalian')
                ->required(),

            Select::make('status')
                ->options([
                    'menunggu' => 'Menunggu',
                    'dipinjam' => 'Dipinjam',
                    'menunggu_kembali' => 'Menunggu Pengembalian',
                    'dikembalikan' => 'Dikembalikan',
                    'ditolak' => 'Ditolak',
                ])
                ->default('menunggu')
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_peminjaman')->searchable(),

                TextColumn::make('siswa.nis')->label('NIS'),

                TextColumn::make('buku.judul')->label('Buku'),

                TextColumn::make('admin.name')
                    ->label('Petugas')
                    ->default('-'),

                // 🔥 STATUS BADGE
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'secondary' => 'menunggu',
                        'info' => 'menunggu_kembali',
                        'success' => 'dipinjam',
                        'danger' => 'ditolak',
                        'primary' => 'dikembalikan',
                    ]),

                TextColumn::make('tanggal_pinjam')->date(),
            ])

            ->actions([

                EditAction::make(),
                DeleteAction::make(),

                // ✅ ACC PINJAM
                Action::make('acc')
                    ->label('ACC Pinjam')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'menunggu')
                    ->action(function ($record) {

                        $buku = $record->buku;

                        if ($buku->stok <= 0) {
                            Notification::make()
                                ->title('Stok buku habis!')
                                ->danger()
                                ->send();
                            return;
                        }

                        $record->update([
                            'status' => 'dipinjam',
                            'admin_id' => auth()->id(), // 🔥 admin pemberi
                        ]);

                        $buku->decrement('stok');

                        Notification::make()
                            ->title('Peminjaman disetujui')
                            ->success()
                            ->send();
                    }),

                // ❌ TOLAK PINJAM
                Action::make('tolak')
                    ->label('Tolak')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'menunggu')
                    ->action(function ($record) {

                        $record->update([
                            'status' => 'ditolak'
                        ]);

                        Notification::make()
                            ->title('Peminjaman ditolak')
                            ->danger()
                            ->send();
                    }),

                // 🔄 TERIMA PENGEMBALIAN
                Action::make('terima_kembali')
                    ->label('Terima Pengembalian')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'menunggu_kembali')
                    ->action(function ($record) {

                        $buku = $record->buku;

                        $record->update([
                            'status' => 'dikembalikan',
                            'admin_id' => auth()->id(), // 🔥 admin penerima
                        ]);

                        $buku->increment('stok');

                        Notification::make()
                            ->title('Buku berhasil dikembalikan')
                            ->success()
                            ->send();
                    }),
            ])

            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjamen::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'edit' => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }
}