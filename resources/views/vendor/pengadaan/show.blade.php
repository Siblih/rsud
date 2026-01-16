@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

    {{-- HEADER --}}
    <h1 class="text-xl font-bold mb-6 text-center">📋 Detail Pengadaan</h1>

    {{-- INFO PENGADAAN --}}
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-5 shadow-xl mb-6">
        <div class="flex justify-between items-start mb-3">
            <div>
                <h2 class="text-lg font-semibold">{{ $pengadaan->nama_pengadaan }}</h2>
                <p class="text-xs text-blue-200">
                    Kode Tender: 
                    {{ $pengadaan->kode_tender ?? 'TDR-'.date('Y').'-'.str_pad($pengadaan->id,5,'0',STR_PAD_LEFT) }}
                </p>
            </div>

            <span class="px-3 py-1 rounded-full text-xs font-semibold
                @if($pengadaan->status === 'selesai') bg-green-400/20 text-green-300
                @else bg-yellow-400/20 text-yellow-300 @endif">
                {{ ucfirst($pengadaan->status) }}
            </span>
        </div>

        <div class="text-sm space-y-1 text-blue-100">
            <p>🏢 Unit Pengaju: <span class="text-white font-semibold">{{ $pengadaan->unit->name ?? '-' }}</span></p>
            <p>📅 Batas Penawaran:
                <span class="text-yellow-300 font-semibold">
                  {{ optional($pengadaan->batas_penawaran)->translatedFormat('d M Y H:i') ?? '-' }}



                </span>
            </p>
            <p>💰 Estimasi Anggaran:
                <span class="text-white font-semibold">
                    Rp {{ number_format($pengadaan->estimasi_anggaran ?? 0, 0, ',', '.') }}
                </span>
            </p>
        </div>
    </div>

    {{-- DETAIL PEKERJAAN --}}
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-5 shadow-lg mb-6">
        <h3 class="text-sm font-semibold text-blue-200 mb-2">📌 Detail Pekerjaan</h3>
        <div class="text-sm text-blue-100 space-y-2">
            <p>
                <span class="font-semibold text-white">Uraian Pekerjaan:</span><br>
                {{ $pengadaan->uraian_pekerjaan ?? '-' }}
            </p>
            <p>
                <span class="font-semibold text-white">Lokasi Pekerjaan:</span><br>
                {{ $pengadaan->lokasi_pekerjaan ?? '-' }}
            </p>
            <p>
                <span class="font-semibold text-white">Waktu Pelaksanaan:</span><br>
               {{ optional($pengadaan->waktu_pelaksanaan_fix)->translatedFormat('d M Y H:i') ?? '-' }}


            </p>
        </div>
    </div>

    {{-- ERROR VALIDASI --}}
    @if ($errors->any())
        <div class="bg-red-500/80 text-white p-4 rounded-xl mb-4">
            <ul class="text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>❌ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- STATUS / FORM PENAWARAN --}}
    @if(!$penawaran)
        <form method="POST"
              action="{{ route('vendor.pengadaan.penawaran', $pengadaan->id) }}"
              enctype="multipart/form-data"
              class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-5 shadow-lg">

            @csrf

            <h3 class="text-sm font-semibold text-blue-200 mb-4">📤 Kirim Penawaran</h3>

            <div class="mb-4">
                <label class="block text-xs mb-1 text-blue-200">💰 Harga Penawaran</label>
                <input type="number" name="harga" step="0.01" required
                    class="w-full p-2 rounded-lg bg-white/10 border border-white/20 text-white">
            </div>

            <div class="mb-4">
                <label class="block text-xs mb-1 text-blue-200">📄 File Penawaran (PDF/DOC)</label>
                <input type="file" name="file_penawaran" required
                    class="w-full text-xs text-blue-100">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
                🚀 Kirim Penawaran
            </button>
        </form>
    @else
        <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-5 text-center shadow-lg">
            <p class="text-blue-200 mb-2">
                📤 Kamu sudah mengirim penawaran untuk pengadaan ini
            </p>

            <p class="text-sm mb-2">
                💰 Harga: 
                <span class="text-white font-semibold">
                    Rp {{ number_format($penawaran->harga,0,',','.') }}
                </span>
            </p>

            <p class="text-sm mb-3">
                Status:
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    @if($penawaran->status === 'menang') bg-green-400/20 text-green-300
                    @elseif($penawaran->status === 'rejected') bg-red-400/20 text-red-300
                    @else bg-yellow-400/20 text-yellow-300 @endif">
                    {{ ucfirst($penawaran->status) }}
                </span>
            </p>

            <a href="{{ asset('storage/'.$penawaran->file_penawaran) }}"
               target="_blank"
               class="text-blue-300 text-xs underline">
                📄 Lihat File Penawaran
            </a>
        </div>
    @endif

</div>
@endsection
