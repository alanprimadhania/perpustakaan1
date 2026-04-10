<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'user_id',
        'nis',
        'kelas',
        'jurusan',
        'tanggal_lahir',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peminjaman()
{
    return $this->hasMany(Peminjaman::class);
}
}