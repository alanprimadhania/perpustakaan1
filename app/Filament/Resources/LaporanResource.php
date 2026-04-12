<?php

namespace App\Filament\Resources;

use App\Models\Peminjaman;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Form;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\Action;

use App\Filament\Resources\LaporanResource\Pages;

class LaporanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $modelLabel = 'Laporan';
    protected static ?string $pluralModelLabel = 'Laporan';
    protected static ?string $navigationGroup = 'Manajemen Perpustakaan';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table

            // ✅ FIX query (pakai ini, bukan ->query())
            ->modifyQueryUsing(function ($query) {
                return $query->with(['siswa.user', 'buku', 'pengembalian']);
            })

            ->columns([

                TextColumn::make('kode_peminjaman')
                    ->label('Kode')
                    ->searchable(),

                TextColumn::make('siswa.nis')
                    ->label('Siswa')
                    ->formatStateUsing(fn ($state, $record) =>
                        $state . ' - ' . ($record->siswa->user->name ?? '-')
                    ),

                TextColumn::make('buku.judul')
                    ->label('Buku'),

                TextColumn::make('tanggal_pinjam')->date(),

                TextColumn::make('batas_pengembalian')->date(),

                TextColumn::make('tanggal_kembali')->date(),

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

                TextColumn::make('pengembalian.keterlambatan')
                    ->label('Terlambat (hari)')
                    ->default('-'),

                TextColumn::make('pengembalian.denda_dibayar')
                    ->label('Denda')
                    ->money('IDR')
                    ->default('-'),
            ])

            // ✅ FILTER TANGGAL
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('dari')->label('Dari'),
                        DatePicker::make('sampai')->label('Sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari'], fn ($q) =>
                                $q->whereDate('tanggal_pinjam', '>=', $data['dari']))
                            ->when($data['sampai'], fn ($q) =>
                                $q->whereDate('tanggal_pinjam', '<=', $data['sampai']));
                    }),
            ])

            // 🔥 HEADER ACTION (GLOBAL PDF)
            ->headerActions([
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function ($livewire) {

                        $query = $livewire->getFilteredTableQuery();

                        $data = $query->with([
                            'siswa.user',
                            'buku',
                            'pengembalian'
                        ])->get();

                        $pdf = \PDF::loadView('laporan.pdf', compact('data'));

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'laporan-perpustakaan.pdf'
                        );
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
        ];
    }
}