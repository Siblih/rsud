@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-5 pt-6 pb-24">

  {{-- Header --}}
  <h1 class="text-xl font-semibold mb-5 flex items-center gap-2">
      <i data-lucide="file-check" class="w-5 h-5"></i>
      Dokumen Pembayaran (BAST)
  </h1>

  {{-- Info Kontrak --}}
  <div class="bg-white/10 border border-white/20 rounded-2xl p-4 mb-5">
      <p class="text-sm text-blue-200 mb-1">
          Nomor Kontrak:
          <span class="text-white font-semibold">{{ $kontrak->nomor_kontrak }}</span>
      </p>
      <p class="text-sm text-blue-200">
          Pengadaan:
          <span class="text-white">{{ $kontrak->pengadaan->nama_pengadaan ?? '-' }}</span>
      </p>
  </div>

  @php
  $fields = [
    'po_signed' => 'PO (Ditandatangani)',
    'bast_signed' => 'BAST (Ditandatangani)',
    'invoice' => 'Invoice',
    'faktur_pajak' => 'Faktur Pajak',
    'surat_permohonan' => 'Surat Permohonan Pembayaran',
  ];
  @endphp

  {{-- LIST DOKUMEN --}}
  <div class="space-y-4">
    @foreach($fields as $key => $label)
      <div class="bg-white/5 border border-white/20 rounded-xl p-4">

        <p class="text-sm text-blue-200 font-medium mb-2">
            {{ $label }}
        </p>

        @if($kontrak->$key)
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

  {{-- Tombol kembali --}}
<div class="fixed bottom-28 left-0 right-0 flex justify-center">
    <a href="{{ route('admin.pengadaan', ['tab' => 'BAST']) }}"
       class="bg-white/20 border border-white/30 px-6 py-3 rounded-full flex items-center gap-2
              text-white font-semibold shadow-lg hover:bg-white/30 transition">
       <i data-lucide="arrow-left" class="w-5 h-5"></i>
       Kembali
    </a>
</div>


</div>

<script>
  lucide.createIcons();
</script>
@endsection
