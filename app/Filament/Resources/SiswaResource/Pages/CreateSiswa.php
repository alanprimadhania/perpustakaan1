<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Resources\Pages\CreateRecord;

use App\Models\User;
use App\Models\Siswa;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

    protected function handleRecordCreation(array $data): Siswa
    {
        // 🔥 Buat user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'siswa',
        ]);

        // 🔥 Buat siswa
        return Siswa::create([
            'user_id' => $user->id,
            'nis' => $data['nis'],
            'kelas' => $data['kelas'],
            'jurusan' => $data['jurusan'],
            'tanggal_lahir' => $data['tanggal_lahir'],
            'status' => $data['status'],
        ]);
    }
}