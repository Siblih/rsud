@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-5 pt-6 pb-24">

    {{-- HEADER --}}
    <h1 class="text-xl font-semibold mb-5 flex items-center gap-2">
        <i data-lucide="file-check" class="w-5 h-5"></i>
        Dokumen Pembayaran (BAST)
    </h1>

    {{-- INFO KONTRAK --}}
    <div class="bg-white/10 border border-white/20 rounded-2xl p-4 mb-5">
        <p class="text-sm text-blue-200 mb-1">
            Nomor Kontrak:
            <span class="text-white font-semibold">
                {{ $kontrak->nomor_kontrak ?? '-' }}
            </span>
        </p>

        <p class="text-sm text-blue-200">
            Pengadaan:
            <span class="text-white">
                {{ $kontrak->pengadaan?->nama_pengadaan ?? '-' }}
            </span>
        </p>
    </div>

    {{-- ===============================
         DATA RELASI (AMAN DARI NULL)
    =============================== --}}
    @php
        $pengadaan  = $kontrak->pengadaan;
        $po         = $kontrak->purchaseOrders?->first();
        $penawaran  = $pengadaan?->penawarans?->first();
        $vendor     = $penawaran?->vendor;
        $pembayaran = $po?->pembayaran;
    @endphp

    {{-- ===============================
         LIST DOKUMEN BAST
    =============================== --}}
    <div class="space-y-4">
        @foreach($fields as $key => $label)
            <div class="bg-white/5 border border-white/20 rounded-xl p-4">

                <p class="text-sm text-blue-200 font-medium mb-2">
                    {{ $label }}
                </p>

                @if(!empty($kontrak->$key))
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-green-300 text-xs flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                Sudah diunggah
                            </span>

                            <span class="text-white text-sm font-semibold">
                                {{ basename($kontrak->$key) }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- LIHAT --}}
                            <a href="{{ asset('storage/'.$kontrak->$key) }}"
                               target="_blank"
                               class="flex items-center gap-1 bg-blue-600 hover:bg-blue-700
                                      px-3 py-1 rounded-lg text-xs font-medium transition">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                Lihat
                            </a>

                            {{-- DOWNLOAD --}}
                            <a href="{{ asset('storage/'.$kontrak->$key) }}"
                               download
                               class="flex items-center gap-1 bg-green-600 hover:bg-green-700
                                      px-3 py-1 rounded-lg text-xs font-medium transition">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Download
                            </a>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-red-300 text-sm">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        Dokumen belum diunggah vendor
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- ===============================
         STATUS & BUKTI PEMBAYARAN
    =============================== --}}
    <div class="mt-6 bg-white/5 border border-white/20 rounded-xl p-4">
        <p class="text-sm text-blue-200 font-medium mb-2">
            Status Pembayaran
        </p>

        @if ($pembayaran && $pembayaran->status === 'lunas')
            <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold
                         bg-green-400/20 text-green-300">
                ✅ Pembayaran Selesai
            </span>

            @if ($pembayaran->bukti_bayar)
                <div class="mt-3">
                    <a href="{{ asset('storage/'.$pembayaran->bukti_bayar) }}"
                       target="_blank"
                       class="text-blue-400 underline text-sm">
                        Lihat Bukti Pembayaran
                    </a>
                </div>
            @endif

        @elseif ($pembayaran)
            <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold
                         bg-yellow-400/20 text-yellow-300">
                ⏳ Dalam Proses Pembayaran
            </span>
        @else
            <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold
                         bg-red-400/20 text-red-300">
                ❌ Belum Dibayar
            </span>

            <p class="text-blue-200 text-sm mt-3">
                Pembayaran akan diproses setelah seluruh dokumen dan kontrak selesai diverifikasi.
            </p>
        @endif
    </div>

    {{-- ===============================
         TOMBOL KEMBALI
    =============================== --}}
    <div class="fixed bottom-28 left-0 right-0 flex justify-center">
        <a href="{{ route('admin.pengadaan', ['tab' => 'BAST']) }}"
           class="bg-white/20 border border-white/30 px-6 py-3 rounded-full
                  flex items-center gap-2 text-white font-semibold
                  shadow-lg hover:bg-white/30 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Kembali
        </a>
    </div>

</div>

<script>
    lucide.createIcons();
</script>
@endsection
