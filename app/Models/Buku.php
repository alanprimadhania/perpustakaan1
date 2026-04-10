<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = [
    'kode_buku',
    'judul',
    'kategori_id',
    'penulis',
    'penerbit',
    'tahun_terbit',
    'stok',
    'cover_image',
];
    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    // Relasi ke Peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
