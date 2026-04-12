<?php 

namespace App\Filament\Resources;

use App\Filament\Resources\PeminjamanResource\Pages;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Pengembalian;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

use Carbon\Carbon;

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

    public static function form(Form $form): Form
    {
        return $form->schema([

            TextInput::make('kode_peminjaman')
                ->required()
                ->unique(ignoreRecord: true),

            Select::make('siswa_id')
                ->relationship('siswa', 'nis')
                ->getOptionLabelFromRecordUsing(fn ($record) => 
                    $record->nis . ' - ' . ($record->user->name ?? '-')
                )
                ->searchable()
                ->preload()
                ->required()
                ->label('Siswa'),

            Select::make('buku_id')
                ->relationship('buku', 'judul')
                ->searchable()
                ->preload()
                ->required(),

            DatePicker::make('tanggal_pinjam')->required(),
            DatePicker::make('batas_pengembalian')->required(),

            DatePicker::make('tanggal_kembali')
                ->maxDate(now()),

            Select::make('status')
                ->options([
                    'menunggu' => 'Menunggu',
                    'dipinjam' => 'Dipinjam',
                    'menunggu_kembali' => 'Menunggu Pengembalian',
                    'dikembalikan' => 'Dikembalikan',
                    'terlambat' => 'Terlambat',
                    'ditolak' => 'Ditolak',
                ])
                ->default('dipinjam')
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_peminjaman')->searchable(),

                TextColumn::make('siswa.nis')
                    ->label('NIS')
                    ->formatStateUsing(fn ($state, $record) => 
                        $state . ' - ' . ($record->siswa->user->name ?? '-') 
                    ),

                TextColumn::make('buku.judul')->label('Buku'),

                TextColumn::make('admin.name')
                    ->label('Petugas')
                    ->default('-'),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'secondary' => 'menunggu',
                        'info' => 'menunggu_kembali',
                        'success' => 'dipinjam',
                        'danger' => 'ditolak',
                        'warning' => 'terlambat',
                        'primary' => 'dikembalikan',
                    ]),

                TextColumn::make('tanggal_pinjam')->date(),
                // ✅ TAMBAH kolom denda (opsional)
            TextColumn::make('pengembalian.denda_dibayar')
                ->label('Denda')
                ->money('IDR')
                ->default('-'),
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
                            'admin_id' => auth()->id(),
                        ]);

                        $buku->decrement('stok');

                        Notification::make()
                            ->title('Peminjaman disetujui')
                            ->success()
                            ->send();
                    }),

                // ❌ TOLAK
                Action::make('tolak')
                    ->label('Tolak')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'menunggu')
                    ->action(function ($record) {

                        $record->update([
                            'status' => 'ditolak',
                            'admin_id' => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('Peminjaman ditolak')
                            ->danger()
                            ->send();
                    }),

                  // 🔄 TERIMA PENGEMBALIAN (dari siswa)
            Action::make('terima_kembali')
                ->label('Terima Pengembalian')
                ->color('success')
                ->visible(fn ($record) => $record->status === 'menunggu_kembali')
                ->action(function ($record) {

                    if ($record->pengembalian()->exists()) {
                        Notification::make()
                            ->title('Sudah diproses!')
                            ->warning()
                            ->send();
                        return;
                    }

                    $buku = $record->buku;

                    // ✅ Hitung keterlambatan
                    $batasKembali = Carbon::parse($record->batas_pengembalian);
                    $terlambat = max(0, $batasKembali->diffInDays(now()));
                    $denda = $terlambat * 1000;

                    // ✅ Tentukan status berdasarkan keterlambatan
                    $statusBaru = $terlambat > 0 ? 'terlambat' : 'dikembalikan';

                    Pengembalian::create([
                        'peminjaman_id' => $record->id,
                        'tanggal_kembali_aktual' => now(),
                        'keterlambatan' => $terlambat,
                        'denda_dibayar' => $denda,
                        'kondisi_buku' => 'baik',
                    ]);

                    $record->update([
                        'status' => $statusBaru, // ✅ UBAH DI SINI
                        'admin_id' => auth()->id(),
                        'tanggal_kembali' => now(),
                    ]);

                    $buku->increment('stok');

                    $message = $terlambat > 0 
                        ? "Pengembalian diterima (TERLAMBAT {$terlambat} hari). Denda: Rp " . number_format($denda)
                        : "Pengembalian diterima tepat waktu";

                    Notification::make()
                        ->title($message)
                        ->success()
                        ->send();
                }),

            // 🔥 KEMBALIKAN LANGSUNG
            Action::make('kembalikan_langsung')
                ->label('Kembalikan Langsung')
                ->color('primary')
                ->visible(fn ($record) => $record->status === 'dipinjam')
                ->action(function ($record) {

                    if ($record->pengembalian()->exists()) {
                        Notification::make()
                            ->title('Sudah dikembalikan!')
                            ->warning()
                            ->send();
                        return;
                    }

                    $buku = $record->buku;

                    // ✅ Hitung keterlambatan
                    $batasKembali = Carbon::parse($record->batas_pengembalian);
                    $terlambat = max(0, $batasKembali->diffInDays(now()));
                    $denda = $terlambat * 1000;

                    // ✅ Tentukan status berdasarkan keterlambatan
                    $statusBaru = $terlambat > 0 ? 'terlambat' : 'dikembalikan';

                    Pengembalian::create([
                        'peminjaman_id' => $record->id,
                        'tanggal_kembali_aktual' => now(),
                        'keterlambatan' => $terlambat,
                        'denda_dibayar' => $denda,
                        'kondisi_buku' => 'baik',
                    ]);

                    $record->update([
                        'status' => $statusBaru, // ✅ UBAH DI SINI
                        'tanggal_kembali' => now(),
                        'admin_id' => auth()->id(),
                    ]);

                    $buku->increment('stok');

                    $message = $terlambat > 0 
                        ? "Dikembalikan dengan keterlambatan {$terlambat} hari. Denda: Rp " . number_format($denda)
                        : "Berhasil dikembalikan tepat waktu";

                    Notification::make()
                        ->title($message)
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