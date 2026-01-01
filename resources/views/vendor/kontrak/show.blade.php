@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] 
     text-white px-5 pt-6 pb-28">

  {{-- 🔙 Header --}}
  <div class="flex items-center justify-between mb-5">
    <h1 class="text-lg font-semibold flex items-center gap-2">
      <i data-lucide="file-text" class="w-5 h-5 text-blue-300"></i>
      Detail Kontrak
    </h1>

    <a href="{{ route('vendor.kontrak') }}"
       class="text-blue-300 text-sm hover:underline flex items-center gap-1">
      <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
    </a>
  </div>

  {{-- 📄 Kartu Kontrak --}}
  <div class="bg-white/10 backdrop-blur-lg border border-white/20 
       rounded-2xl shadow-xl p-5 space-y-4">

    <div class="flex justify-between items-center">
      <span class="text-blue-200 text-sm">Nomor Kontrak</span>
      <span class="font-semibold text-white">{{ $kontrak->nomor_kontrak ?? '-' }}</span>
    </div>

    <div class="flex justify-between items-center">
      <span class="text-blue-200 text-sm">Tanggal Kontrak</span>
      <span class="font-semibold text-white">
        {{ $kontrak->tanggal_kontrak 
          ? \Carbon\Carbon::parse($kontrak->tanggal_kontrak)->translatedFormat('d M Y') 
          : '-' }}
      </span>
    </div>

    <div class="flex justify-between items-center">
      <span class="text-blue-200 text-sm">Nilai Kontrak</span>
      <span class="font-semibold text-white">
        Rp{{ number_format($kontrak->nilai_kontrak ?? 0, 0, ',', '.') }}
      </span>
    </div>

    <div class="flex justify-between items-center">
      <span class="text-blue-200 text-sm">Status</span>

      <span class="px-2 py-1 text-xs rounded-full
        @if($kontrak->status == 'selesai') bg-green-400/20 text-green-300
        @elseif($kontrak->status == 'dibatalkan') bg-red-400/20 text-red-300
        @else bg-yellow-400/20 text-yellow-300 @endif">
        {{ ucfirst($kontrak->status ?? 'aktif') }}
      </span>
    </div>

    {{-- 📎 File Kontrak --}}
    <div class="border-t border-white/20 pt-4">
      <p class="text-blue-200 text-sm mb-1">📎 File Kontrak</p>

      @if($kontrak->file_kontrak)
        <a href="{{ asset('storage/'.$kontrak->file_kontrak) }}" 
           target="_blank"
           class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm font-medium inline-block">
          Lihat Dokumen
        </a>
      @else
        <p class="text-blue-300 text-sm italic">Belum ada file kontrak.</p>
      @endif
    </div>

  </div>

  {{-- 📦 Info Pengadaan --}}
  @if($kontrak->pengadaan)
  <div class="mt-6 bg-white/10 backdrop-blur-lg border border-white/20 
       rounded-2xl shadow-xl p-5">

    <h2 class="text-blue-200 font-semibold mb-3 flex items-center gap-2">
      <i data-lucide="package" class="w-4 h-4"></i> Informasi Pengadaan
    </h2>

    <p class="text-sm font-medium text-white">
      {{ $kontrak->pengadaan->nama_pengadaan }}
    </p>

    <p class="text-xs text-blue-200 mt-1">
      {{ $kontrak->pengadaan->deskripsi ?? '-' }}
    </p>

  </div>
  @endif

  {{-- 📥 Dokumen Pembayaran --}}
  <div class="mt-6 bg-white/10 backdrop-blur-lg border border-white/20 
       rounded-2xl shadow-xl p-5">

    <h2 class="text-blue-200 font-semibold mb-3 flex items-center gap-2">
      <i data-lucide="folder-open" class="w-4 h-4"></i> Dokumen Pembayaran
    </h2>

    @php
      $docs = [
        'po_signed' => 'PO (Ditandatangani)',
        'bast_signed' => 'BAST (Ditandatangani)',
        'invoice' => 'Invoice',
        'faktur_pajak' => 'Faktur Pajak',
        'surat_permohonan' => 'Surat Permohonan Pembayaran'
      ];
    @endphp

    <ul class="divide-y divide-white/10 text-sm">
      @foreach($docs as $field => $label)
        <li class="py-3 flex justify-between items-center">
          <span class="text-blue-200">{{ $label }}</span>

          @if($kontrak->$field)
            <a href="{{ asset('storage/'.$kontrak->$field) }}" 
   target="_blank"
   class="flex items-center gap-1 bg-blue-600 hover:bg-blue-700 
          px-3 py-1 rounded-lg text-xs font-medium transition">
    <i data-lucide="eye" class="w-4 h-4"></i>
    Lihat dokumen
</a>

          @else
            <span class="text-blue-300 italic text-xs">Belum ada</span>
          @endif
        </li>
      @endforeach
    </ul>

  </div>

  {{-- ========================= --}}
{{-- 💰 STATUS PEMBAYARAN --}}
{{-- ========================= --}}
<div id="pembayaran"
     class="mt-10 bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-6 shadow-xl">

    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
        💳 Status Pembayaran
    </h3>

    @if ($kontrak->status_pembayaran === 'paid')
        {{-- ✅ SUDAH DIBAYAR --}}
        <div class="flex flex-col gap-3">
            <span class="inline-block w-fit px-4 py-1 rounded-full text-sm font-semibold
                         bg-green-400/20 text-green-300">
                ✅ Sudah Dibayar
            </span>

            @if ($kontrak->bukti_pembayaran)
                <a href="{{ asset('storage/' . $kontrak->bukti_pembayaran) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 w-fit
                          bg-blue-600 hover:bg-blue-700
                          text-white px-4 py-2 rounded-lg text-sm font-semibold shadow">
                    📄 Download Bukti Pembayaran
                </a>
            @else
                <p class="text-yellow-300 text-sm">
                    ⚠️ Bukti pembayaran belum diunggah.
                </p>
            @endif
        </div>

    @elseif ($kontrak->status_pembayaran === 'process')
        {{-- ⏳ DALAM PROSES --}}
        <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold
                     bg-yellow-400/20 text-yellow-300">
            ⏳ Dalam Proses Pembayaran
        </span>

    @else
        {{-- ❌ BELUM DIBAYAR --}}
        <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold
                     bg-red-400/20 text-red-300">
            ❌ Belum Dibayar
        </span>

        <p class="text-blue-200 text-sm mt-3">
            Pembayaran akan diproses setelah seluruh dokumen dan kontrak selesai diverifikasi.
        </p>
    @endif

</div>

{{-- 🔘 Tombol Kembali (Floating) --}}
<div class="fixed bottom-20 left-0 right-0 flex justify-center z-50">
  <a href="{{ route('vendor.kontrak') }}"
     class="bg-white/20 backdrop-blur-md border border-white/30 px-6 py-3 
            rounded-full flex items-center gap-2 text-white font-semibold 
            shadow-lg hover:bg-white/30 transition">
     <i data-lucide="arrow-left" class="w-5 h-5"></i> Kembali ke Daftar
  </a>
</div>

<script> lucide.createIcons(); </script>
@endsection
