@extends('layouts.vendor-app')

@section('title', 'Daftar Pengadaan')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

    {{-- 🏷️ Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold text-white flex items-center gap-2">
            <i data-lucide="briefcase" class="w-5 h-5"></i> Daftar Tender & Kompetisi
        </h1>
        <a href="{{ route('vendor.pengadaan') }}"
           class="bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium text-xs px-3 py-1.5 rounded-full transition-all backdrop-blur-lg shadow">
            🔄 Refresh
        </a>
    </div>

    {{-- 📦 Daftar Pengadaan --}}
    @if($pengadaans->count() > 0)
        <div class="space-y-4">
            @foreach($pengadaans as $item)

            @php
                // 🔑 Penawaran vendor login
                $penawaran = $item->penawarans->first();
            @endphp

            <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-md p-4 hover:bg-white/20 transition-all duration-300">

                {{-- HEADER --}}
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h2 class="text-base font-semibold text-white">
                            {{ $item->nama_pengadaan }}
                        </h2>

                        <p class="text-xs text-blue-200">
                            Kode:
                            {{ $item->kode_tender
                                ?? 'TDR-' . date('Y') . '-' . str_pad($item->id, 5, '0', STR_PAD_LEFT)
                            }}
                        </p>
                    </div>

                    {{-- STATUS GLOBAL (INFORMASI SAJA) --}}
                    @php
    $isMenang = $penawaran && $penawaran->status === 'menang';
@endphp

<span class="text-xs px-2 py-1 rounded-full font-semibold
    @if($isMenang)
        bg-green-400/20 text-green-300
    @elseif($item->status === 'selesai')
        bg-green-400/20 text-green-300
    @else
        bg-yellow-400/20 text-yellow-300
    @endif">
    
    @if($isMenang)
        Selesai
    @else
        {{ ucfirst($item->status) }}
    @endif
</span>

                </div>

                {{-- INFO --}}
                <div class="text-sm text-blue-100 space-y-1">
                    <p>
                        📅 Batas Penawaran:
                        <span class="font-semibold text-white">
                            {{ \Carbon\Carbon::parse($item->batas_penawaran)->translatedFormat('d M Y H:i') }}
                        </span>
                    </p>

                    <p>
                        💰 Estimasi Anggaran:
                        <span class="font-semibold text-white">
                            Rp {{ number_format($item->estimasi_anggaran, 0, ',', '.') }}
                        </span>
                    </p>
                </div>

                {{-- STATUS PENAWARAN VENDOR --}}
                <div class="mt-4 flex justify-between items-center">

                    @if(!$penawaran)
                        <span class="text-xs px-3 py-1 rounded-full bg-gray-400/20 text-gray-300 italic">
                            Belum Mengirim Penawaran
                        </span>

                    @elseif($penawaran->status === 'pending')
                        <span class="text-xs px-3 py-1 rounded-full bg-yellow-400/20 text-yellow-300 font-semibold">
                            ⏳ Pending
                        </span>

                    @elseif($penawaran->status === 'menang')
                        <span class="text-xs px-3 py-1 rounded-full bg-green-400/20 text-green-300 font-semibold">
                            🏆 Menang
                        </span>

                    @else
                        <span class="text-xs px-3 py-1 rounded-full bg-red-400/20 text-red-300 font-semibold">
                            ❌ Kalah
                        </span>
                    @endif

                    {{-- AKSI --}}
                    <a href="{{ route('vendor.pengadaan.show', $item->id) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-4 py-2 rounded-full font-semibold shadow transition">
                        {{ $penawaran ? 'Lihat Detail' : 'Kirim Penawaran' }}
                    </a>

                </div>
            </div>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        <div class="mt-6">
            {{ $pengadaans->links() }}
        </div>

    @else
        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-xl p-6 text-center">
            <p class="text-blue-100 text-sm">
                📭 Belum ada tender atau kompetisi yang tersedia.
            </p>
        </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection
