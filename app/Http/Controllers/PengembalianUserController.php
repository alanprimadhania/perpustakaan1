<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;

class PengembalianUserController extends Controller
{
    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // ❌ hanya yang dipinjam
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Tidak bisa dikembalikan');
        }

        // ❌ jangan dobel
        if (Pengembalian::where('peminjaman_id', $id)->exists()) {
            return back()->with('error', 'Sudah diajukan');
        }

        // 🔥 HITUNG TELAT
        $batas = Carbon::parse($peminjaman->batas_pengembalian);
        $sekarang = Carbon::now();

        $telat = $batas->diffInDays($sekarang, false);
        $keterlambatan = $telat > 0 ? $telat : 0;

        // ✅ SIMPAN KE DATABASE (INI YANG KAMU BELUM ADA)
        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'admin_id' => 1, // sementara (WAJIB ADA)
            'tanggal_kembali_aktual' => now(),
            'keterlambatan' => $keterlambatan,
            'denda_dibayar' => $keterlambatan * 1000,
            'kondisi_buku' => 'baik',
        ]);

        // ✅ update status peminjaman
        $peminjaman->update([
            'status' => 'menunggu_kembali'
        ]);

        return back()->with('success', 'Menunggu konfirmasi admin');
    }
}