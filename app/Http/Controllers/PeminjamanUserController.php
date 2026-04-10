<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request; 

class PeminjamanUserController extends Controller
{
    public function store(Request $request)
    {
        $buku = Buku::findOrFail($request->buku_id);

        // ❌ cegah stok habis
        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok habis');
        }

        $siswa = auth()->user()->siswa;

        // Cek duluu
        $cek = Peminjaman::where('siswa_id', $siswa->id)
            ->where('buku_id', $buku->id)
            ->where('status', 'dipinjam')
            ->exists();

        if ($cek) {
            return back()->with('error', 'Buku sudah dipinjam');
        }

        // ✅ simpan peminjaman
        Peminjaman::create([
        'kode_peminjaman' => 'PMJ-' . time(),
        'siswa_id' => $siswa->id,
        'buku_id' => $buku->id,
        'tanggal_pinjam' => now(),
        'batas_pengembalian' => now()->addDays(7),
        'status' => 'menunggu',
        'admin_id' => null, // ✅ kosong dulu
    ]);
 
        return redirect()->route('riwayat.index')
            ->with('success', 'Buku berhasil dipinjam');
    } 
}
