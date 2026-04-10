<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'admin_id',
        'tanggal_kembali_aktual',
        'keterlambatan',
        'denda_dibayar',
        'kondisi_buku',
        'catatan',
    ];

    
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}