<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get();

        return view('buku.index', compact('buku'));
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
    
        return view('buku.show', compact('buku'));
    }
}
