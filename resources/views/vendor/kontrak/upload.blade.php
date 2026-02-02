@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-5 pt-6 pb-24">

  {{-- Header --}}
  <h1 class="text-xl font-semibold mb-5 flex items-center gap-2">
      <i data-lucide="upload" class="w-5 h-5"></i>
      Upload Dokumen Pembayaran
  </h1>

  {{-- Info Kontrak --}}
  <div class="bg-white/10 border border-white/20 rounded-2xl p-4 mb-5">
      <p class="text-sm text-blue-200 mb-1">
          Nomor Kontrak: <span class="text-white font-semibold">{{ $kontrak->nomor_kontrak }}</span>
      </p>
      <p class="text-sm text-blue-200">
          Pengadaan: <span class="text-white">{{ $kontrak->pengadaan->nama_pengadaan ?? '-' }}</span>
      </p>
  </div>

  {{-- Form Upload --}}
  <form action="{{ route('vendor.kontrak.upload', $kontrak->id) }}" method="POST" enctype="multipart/form-data"
        class="bg-white/10 border border-white/20 rounded-2xl p-5 space-y-4">
      @csrf

      @php
      $fields = [
        'po_signed' => 'PO (Ditandatangani)',
        'bast_signed' => 'BAST (Ditandatangani)',
        'invoice' => 'Invoice',
        'faktur_pajak' => 'Faktur Pajak',
        'surat_permohonan' => 'Surat Permohonan Pembayaran',
      ];
      @endphp

      @foreach($fields as $key => $label)
<div class="space-y-1">

    <label class="text-sm text-blue-200 font-medium">{{ $label }}</label>

    <div class="bg-white/5 border border-white/20 rounded-xl p-3">

        {{-- Jika dokumen SUDAH ADA --}}
        @if($kontrak->$key)

            <div class="flex justify-between items-center">
                <div class="flex flex-col">
                    <span class="text-green-300 text-xs flex items-center gap-1">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Sudah diunggah
                    </span>

                    <span class="text-white text-sm font-semibold">
                        {{ basename($kontrak->$key) }}
                    </span>
                </div>

                <div class="flex items-center gap-2">

                    {{-- Tombol lihat --}}
                    <a href="{{ asset('storage/'.$kontrak->bast_signed) }}" target="_blank"

                       class="flex items-center gap-1 bg-blue-600 hover:bg-blue-700 
                              px-3 py-1 rounded-lg text-xs font-medium transition">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                        Lihat
                    </a>

                    {{-- Tombol ganti dokumen --}}
                    <button type="button"
        onclick="document.getElementById('replace_{{ $key }}').click();"
        class="flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600
               px-3 py-1 rounded-lg text-xs font-medium transition">
    <i data-lucide="file-up" class="w-4 h-4"></i>
    Ganti
</button>


                </div>
            </div>

            {{-- Input file tersembunyi untuk ganti --}}
            <input type="file" id="replace_{{ $key }}" name="{{ $key }}"
                   class="hidden">

        @else
        {{-- Jika dokumen BELUM ADA --}}
            <div class="flex justify-between items-center">

                <div class="flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-300"></i>
                    <span class="text-red-300 text-sm">Belum diunggah</span>
                </div>

                <input type="file"
                       name="{{ $key }}"
                       class="w-40 bg-white/10 border border-white/20 rounded-lg p-2 text-xs">
            </div>
        @endif

    </div>

</div>
@endforeach


      <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm font-semibold">
          Upload Dokumen
      </button>
  </form>

  {{-- Tombol kembali --}}
  <div class="fixed bottom-20 left-0 right-0 flex justify-center">
      <a href="{{ route('vendor.kontrak') }}"
         class="bg-white/20 border border-white/30 px-6 py-3 rounded-full flex items-center gap-2 
                text-white font-semibold shadow-lg hover:bg-white/30 transition">
         <i data-lucide="arrow-left" class="w-5 h-5"></i> Kembali
      </a>
  </div>

</div>

<script>
  lucide.createIcons();
</script>
@endsection
