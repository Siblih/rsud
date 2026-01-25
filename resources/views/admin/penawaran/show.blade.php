@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white px-6 py-8">

    {{-- Card utama --}}
    <div class="max-w-6xl mx-auto bg-white/10 backdrop-blur-lg border border-white/20 
                rounded-2xl shadow-xl p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold flex items-center gap-2">
                📦 {{ $pengadaan->nama_pengadaan }}
            </h2>

            <span class="text-xs px-3 py-1 rounded-full font-semibold
                @if($pengadaan->status === 'selesai') bg-green-400/20 text-green-300
                @else bg-yellow-400/20 text-yellow-300 @endif">
                {{ ucfirst($pengadaan->status) }}
            </span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-white/20">
                    <tr>
                        <th class="p-3 border border-white/10 text-left">Vendor</th>
                        <th class="p-3 border border-white/10 text-right">Harga</th>
                        <th class="p-3 border border-white/10 text-center">File</th>
                        <th class="p-3 border border-white/10 text-center">Status</th>
                        <th class="p-3 border border-white/10 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengadaan->penawarans as $p)
                        <tr class="hover:bg-white/10 transition">
                            <td class="p-3 border border-white/10">
                                {{ $p->vendor->name }}
                            </td>

                            <td class="p-3 border border-white/10 text-right">
                                Rp {{ number_format($p->harga,0,',','.') }}
                            </td>

                            <td class="p-3 border border-white/10 text-center">
                                <a href="{{ asset('storage/'.$p->file_penawaran) }}"
                                   target="_blank"
                                   class="text-blue-300 hover:underline text-xs">
                                    📄 Lihat File
                                </a>
                            </td>

                            <td class="p-3 border border-white/10 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($p->status === 'menang') bg-green-400/20 text-green-300
                                    @elseif($p->status === 'rejected') bg-red-400/20 text-red-300
                                    @else bg-yellow-400/20 text-yellow-300 @endif">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>

                            <td class="p-3 border border-white/10 text-center">
                                @if($p->status !== 'menang' && $pengadaan->status !== 'selesai')
                                    <form method="POST"
                                          action="{{ route('admin.penawaran.setPemenang', $p->id) }}"
                                          onsubmit="return confirm('Yakin pilih vendor ini sebagai pemenang?')">
                                        @csrf
                                        <button
                                            class="bg-green-600/80 hover:bg-green-700 
                                                   text-white text-xs px-4 py-2 
                                                   rounded-full font-semibold shadow transition">
                                            🏆 Pilih Pemenang
                                        </button>
                                    </form>
                                @elseif($p->status === 'menang')
    <div class="flex justify-center items-center gap-2">

        <span class="text-green-300 font-semibold">
            🏆 Pemenang
        </span>

        <a href="{{ route('admin.kontrak.create', $pengadaan->id) }}?tab=kontrak"
           class="bg-blue-600/80 hover:bg-blue-700
                  text-white text-xs px-3 py-1
                  rounded-full font-semibold shadow transition">
            📄 Ke Kontrak
        </a>

    </div>
@endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-blue-200 italic">
                                📭 Belum ada penawaran vendor
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
