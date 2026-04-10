<?php

namespace App\Http\Controllers;

class RiwayatController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;

        $riwayat = $siswa->peminjaman()
            ->with('buku')
            ->latest()
            ->get();

        return view('riwayat.index', compact('riwayat'));
    }
}
