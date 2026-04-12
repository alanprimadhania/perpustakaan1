<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- COVER --}}
            <div class="p-4 flex justify-center items-start bg-gray-50">
                <img 
                    src="{{ $buku->cover_image ? asset('storage/' . $buku->cover_image) : 'https://via.placeholder.com/300x400?text=No+Cover' }}"
                    class="rounded-xl shadow-md w-64 object-cover"
                    alt="Cover Buku"
                >
            </div>

            {{-- DETAIL --}}
            <div class="md:col-span-2 p-6">

                {{-- JUDUL --}}
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $buku->judul }}
                </h1>

                {{-- BADGE --}}
                <div class="flex flex-wrap gap-2 mt-3">

                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                        {{ $buku->kategori->nama_kategori ?? '-' }}
                    </span>

                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                        Stok: {{ $buku->stok }}
                    </span>

                    @if($buku->stok > 0)
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            Tersedia
                        </span>
                    @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                            Habis
                        </span>
                    @endif

                </div>

                {{-- DETAIL INFO --}}
                <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-3 text-gray-700">

                    <p><b>Penulis:</b> {{ $buku->penulis }}</p>

                    <p><b>Penerbit:</b> {{ $buku->penerbit }}</p>

                    <p><b>Jumlah Halaman:</b> {{ $buku->jumlah_halaman ?? '-' }}</p>

                    <p><b>Tahun Terbit:</b> {{ $buku->tahun_terbit ?? '-' }}</p>

                    <p><b>ISBN:</b> {{ $buku->isbn ?? '-' }}</p>

                    <p><b>Lokasi Rak:</b> {{ $buku->lokasi_rak ?? '-' }}</p>

                </div>

                {{-- DESKRIPSI --}}
                <div class="mt-6">
                    <h3 class="font-semibold text-lg mb-2">Deskripsi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $buku->deskripsi ?? 'Tidak ada deskripsi buku ini.' }}
                    </p>
                </div>

                {{-- PINJAM BUTTON --}}
                <div class="mt-6">

                    @if($buku->stok > 0)
                        <form method="POST" action="{{ route('pinjam.store') }}">
                            @csrf
                            <input type="hidden" name="buku_id" value="{{ $buku->id }}">

                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                                Pinjam Buku
                            </button>
                        </form>
                    @else
                        <button disabled class="bg-gray-400 text-white px-6 py-2 rounded-lg">
                            Stok Habis
                        </button>
                    @endif

                </div>

            </div>
        </div>

    </div>
</x-app-layout>