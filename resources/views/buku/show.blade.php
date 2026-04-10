<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">

        <div class="bg-white rounded-xl shadow p-6">

            <h1 class="text-2xl font-bold mb-4">
                {{ $buku->judul }}
            </h1>

            <p>Kategori: {{ $buku->kategori->nama_kategori }}</p>
            <p>Penulis: {{ $buku->penulis }}</p>
            <p>Penerbit: {{ $buku->penerbit }}</p>

            <p class="mt-4">
                Stok tersedia:
                <span class="font-bold text-green-600">
                    {{ $buku->stok }}
                </span>
            </p>

            <!-- Tombol Pinjam -->
            @if($buku->stok > 0)
                <form method="POST" action="{{ route('pinjam.store') }}">
                    @csrf
                    <input type="hidden" name="buku_id" value="{{ $buku->id }}">

                    <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
                        Pinjam Buku
                    </button>
                </form>
            @else
                <p class="text-red-500 mt-4">Stok habis</p>
            @endif

        </div>

    </div>
</x-app-layout>