<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Peminjaman
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-xl overflow-x-auto">

            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Buku</th>
                <th class="px-4 py-2">Tanggal Pinjam</th>
                <th class="px-4 py-2">Status Peminjaman</th>
                <th class="px-4 py-2">Status Pengembalian</th>
            </tr>
        </thead>

                <tbody>
                    @foreach ($riwayat as $item)
                        <tr class="border-t">

                            <td class="px-4 py-2">
                                {{ $item->buku->judul }}
                            </td>

                            <td class="px-4 py-2">
                                {{ $item->tanggal_pinjam }}
                            </td>

                            <td class="px-4 py-2">

                              @if ($item->status == 'menunggu')
                                <span class="bg-gray-500 text-white px-2 py-1 rounded text-xs">
                                    Menunggu
                                </span>
                            
                            @elseif ($item->status == 'dipinjam')
                                <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs">
                                    Dipinjam
                                </span>
                            
                            @elseif ($item->status == 'menunggu_kembali') {{-- ✅ TAMBAHKAN INI --}}
                                <span class="bg-gray-500 text-white px-2 py-1 rounded text-xs">
                                    Menunggu Pengembalian
                                </span>
                            
                            @elseif ($item->status == 'ditolak')
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">
                                    Ditolak
                                </span>
                            
                            @elseif ($item->status == 'dikembalikan')
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">
                                    Dikembalikan
                                </span>
                            @endif

                            </td>

                            <td class="px-4 py-2">

                            {{-- STATUS --}}
                           @if ($item->status == 'dipinjam')
                                <form action="{{ route('kembalikan', $item->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">
                                        Ajukan Pengembalian
                                    </button>
                                </form>
                            @endif
                            
                            @if ($item->status == 'menunggu_kembali')
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">
                                    Menunggu Konfirmasi Admin
                                </span>
                            @endif
                            
                            @if ($item->status == 'dikembalikan')
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">
                                    Selesai
                                </span>
                            @endif 
                        </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>
</x-app-layout>