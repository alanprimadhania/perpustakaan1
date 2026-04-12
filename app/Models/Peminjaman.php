<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
protected $fillable = [
    'kode_peminjaman',
    'siswa_id',
    'buku_id',
    'tanggal_pinjam',
    'batas_pengembalian',
    'tanggal_kembali',
    'status',
    'admin_id',
];
    // Relasi ke Admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    
    // Relasi ke Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    // Relasi ke Pengembalian
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }
}
