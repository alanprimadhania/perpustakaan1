<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Koleksi Buku
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

            @foreach ($buku as $item)
                <div class="bg-white rounded-xl shadow overflow-hidden">

                    <!-- Cover -->
                    @if ($item->cover_image)
                        <img src="{{ asset('storage/' . $item->cover_image) }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    @endif

                    <!-- Info -->
                    <div class="p-4">
                        <h3 class="font-bold text-lg">
                            {{ $item->judul }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            {{ $item->kategori->nama }}
                        </p>

                        <p class="text-sm mt-2">
                            Penulis: {{ $item->penulis }}
                        </p>

                        <!-- Stok -->
                        <p class="mt-2">
                            Stok:
                            <span class="
                                px-2 py-1 rounded text-white text-xs
                                {{ $item->stok > 0 ? 'bg-green-500' : 'bg-red-500' }}
                            ">
                                {{ $item->stok > 0 ? 'Tersedia' : 'Habis' }}
                            </span>
                        </p>
                        <a href="{{ route('buku.show', $item) }}"
                           class="inline-block mt-3 text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            Lihat Detail
                        </a>
                    </div>

                </div>
            @endforeach

        </div>

    </div>
</x-app-layout>