<?php

namespace App\Filament\Resources\BukuResource\Pages;

use App\Filament\Resources\BukuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBukus extends ListRecords
{
    protected static string $resource = BukuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Buku'),
        ];
    }

    protected function getEmptyStateHeading(): ?string
    {
        return 'Belum ada buku';
    }

    protected function getEmptyStateDescription(): ?string
    {
        return 'Klik tombol "Tambah Buku" untuk menambahkan data.';
    }
}