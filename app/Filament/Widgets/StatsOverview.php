<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Buku;
use App\Models\Siswa;
use App\Models\Peminjaman;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Buku', Buku::count()),
            Card::make('Total Siswa', Siswa::count()),
            Card::make('Dipinjam', Peminjaman::where('status', 'dipinjam')->count()),
            Card::make('Menunggu', Peminjaman::where('status', 'menunggu')->count()),
        ];
    }
}
