<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];
    // relasi ke buku
    public function buku()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}
