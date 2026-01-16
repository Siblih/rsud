@extends('layouts.vendor-app')

@section('title', 'Daftar Pengadaan')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

    {{-- 🏷️ Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold text-white flex items-center gap-2">
            <i data-lucide="briefcase" class="w-5 h-5"></i> Daftar Tender & Kompetisi
        </h1>
        <button class="bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium text-xs px-3 py-1.5 rounded-full transition-all backdrop-blur-lg shadow">
            🔄 Refresh
        </button>
    </div>

    {{-- 📦 Daftar Pengadaan --}}
    @if($pengadaans->count() > 0)
        <div class="space-y-4">
            @foreach($pengadaans as $item)
            <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-md p-4 hover:bg-white/20 transition-all duration-300">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h2 class="text-base font-semibold text-white">{{ $item->nama_paket }}</h2>
                        <p class="text-xs text-blue-200">
    Kode: {{ $item->kode_tender 
        ?? 'TDR-' . date('Y') . '-' . str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
</p>

                    </div>
                    <span class="text-xs px-2 py-1 rounded-full font-semibold 
                        @if($item->status === 'berjalan') bg-yellow-400/20 text-yellow-300
                        @elseif($item->status === 'selesai') bg-green-400/20 text-green-300
                        @else bg-gray-400/20 text-gray-300 @endif">
                        {{ ucfirst($item->status) }}
                    </span>
                </div>

                <div class="text-sm text-blue-100">
                    <p>📅 Batas Penawaran: <span class="font-semibold text-white">
                        {{ $pengadaan->batas_penawaran?->translatedFormat('d M Y H:i') ?? '-' }}

                    </span></p>
                   <p>
💰 Estimasi Anggaran:
<span class="font-semibold text-white">
    Rp {{ number_format($item->estimasi_anggaran, 0, ',', '.') }}

</span>
</p>

                </div>

                {{-- Status Penawaran --}}
                @php
                    $penawaran = $item->penawarans->where('vendor_id', Auth::id())->first();
                @endphp

                <div class="mt-4 flex justify-between items-center">
                    @if($penawaran)
                        <span class="text-xs px-3 py-1 rounded-full font-semibold
                            @if($penawaran->status === 'menang') bg-green-400/20 text-green-300
                            @elseif($penawaran->status === 'pending') bg-yellow-400/20 text-yellow-300
                            @else bg-red-400/20 text-red-300 @endif">
                            Status: {{ ucfirst($penawaran->status) }}
                        </span>
                    @else
                        <span class="text-xs text-blue-200 italic">Belum mengirim penawaran</span>
                    @endif

                    <a href="{{ route('vendor.pengadaan.show', $item->id) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-2 rounded-full font-semibold shadow transition">
                        {{ $penawaran ? 'Lihat Detail' : 'Kirim Penawaran' }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-xl p-6 text-center">
            <p class="text-blue-100 text-sm">📭 Belum ada tender atau kompetisi yang tersedia saat ini.</p>
        </div>
    @endif
</div>

<script>
  lucide.createIcons();
</script>
@endsection
