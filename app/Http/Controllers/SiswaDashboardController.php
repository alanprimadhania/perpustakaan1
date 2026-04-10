<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Peminjaman;

class SiswaDashboardController extends Controller
{
    public function index()
{
    $siswa = auth()->user()->siswa;

    // 🔒 antisipasi null
    if (!$siswa) {
        abort(403, 'Data siswa tidak ditemukan');
    }

    $totalPinjam = Peminjaman::where('siswa_id', $siswa->id)->count();

    $masihDipinjam = Peminjaman::where('siswa_id', $siswa->id)
        ->where('status', 'dipinjam')
        ->count();

    return view('dashboard', compact('totalPinjam', 'masihDipinjam'));
}
}