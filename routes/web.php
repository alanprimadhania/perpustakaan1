<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaDashboardController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\PeminjamanUserController;
use App\Http\Controllers\PengembalianUserController;

Route::get('/', function () {
    return view('welcome');
});

// ✅ DASHBOARD SISWA (PAKAI CONTROLLER)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
        ->name('dashboard');

    // 📚 Buku
    Route::get('/buku', [BukuController::class, 'index'])
        ->name('buku.index');

    Route::get('/buku/{id}', [BukuController::class, 'show'])
        ->name('buku.show');

    Route::post('/pinjam', [PeminjamanUserController::class, 'store'])->name('pinjam.store'); 
    
    // Pengembalian
    Route::post('/kembalikan/{id}', [PengembalianUserController::class, 'kembalikan'])
        ->name('kembalikan');
        

    // 📖 Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'index'])
        ->name('riwayat.index');

    // 👤 Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
