<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('siswa_id')->constrained();
            $table->foreignId('buku_id')->constrained();
            $table->foreignId('admin_id')->constrained('users');
            $table->date('tanggal_pinjam');
            $table->date('batas_pengembalian');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status',['menunggu','menunggu_kembali','dipinjam','dikembalikan','terlambat','hilang']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
