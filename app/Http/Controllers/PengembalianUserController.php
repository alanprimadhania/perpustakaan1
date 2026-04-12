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

        // ❌ cegah dobel request
        if ($peminjaman->status === 'menunggu_kembali') {
            return back()->with('error', 'Sudah diajukan');
        }

        // 🔥 HITUNG TELAT (untuk preview saja)
        $batas = Carbon::parse($peminjaman->batas_pengembalian);
        $telat = $batas->diffInDays(now(), false);
        $keterlambatan = $telat > 0 ? $telat : 0;

        // ❗ HANYA UPDATE STATUS (INI YANG BENAR)
        $peminjaman->update([
            'status' => 'menunggu_kembali'
        ]);

        return back()->with('success', 'Menunggu konfirmasi admin');
    }
}