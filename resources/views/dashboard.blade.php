<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Siswa
        </h2>
    </x-slot>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Total Peminjaman -->
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-gray-500 text-sm">Total Peminjaman</h3>
                <p class="text-3xl font-bold text-blue-600">
                    {{ $totalPinjam }}
                </p>
            </div>

            <!-- Masih Dipinjam -->
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-gray-500 text-sm">Masih Dipinjam</h3>
                <p class="text-3xl font-bold text-red-500">
                    {{ $masihDipinjam }}
                </p>
            </div>

        </div>

        <!-- Menu Navigasi -->
        <div class="mt-6 flex gap-4">
            <a href="{{ route('buku.index') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded-lg">
               📚 Lihat Buku
            </a>

            <a href="{{ route('riwayat.index') }}"
               class="bg-green-500 text-white px-4 py-2 rounded-lg">
               📖 Riwayat
            </a>
        </div>
    </div>
</x-app-layout>