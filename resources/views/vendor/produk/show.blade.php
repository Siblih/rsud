@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white pb-24 px-4 pt-6">
  <div class="max-w-2xl mx-auto">

    {{-- CARD UTAMA --}}
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl p-6 shadow-xl">

      {{-- FOTO + NAMA + HARGA --}}
      <div class="flex gap-5 items-center">
        
        {{-- Foto --}}
        <div class="w-32 h-32 rounded-xl overflow-hidden bg-white/5 border border-white/10">
          @if(!empty($product->photos[0]))
            <img src="{{ asset('storage/'.$product->photos[0]) }}" class="w-full h-full object-cover">
          @else
            <div class="flex items-center justify-center h-full text-blue-200 text-sm">No Image</div>
          @endif
        </div>

        {{-- Info --}}
        <div class="flex-1">
          <h2 class="text-2xl font-bold">{{ $product->name }}</h2>
          <p class="text-blue-200 mt-1 text-sm">{{ $product->description }}</p>
          
          <div class="mt-3 text-xl font-semibold">
            Rp{{ number_format($product->price,0,',','.') }}
          </div>

          {{-- BADGE TIPE PRODUK --}}
          <div class="mt-2 inline-block px-3 py-1 text-xs rounded-full bg-purple-500/20 text-purple-300 border border-purple-400/30">
            {{ strtoupper($product->tipe_produk) }}
          </div>

          {{-- BADGE kategori (hanya jika barang) --}}
          @if($product->tipe_produk == 'barang')
            <div class="mt-1 inline-block px-3 py-1 text-xs rounded-full 
              @if($product->kategori=='obat') bg-red-500/20 text-red-300 border border-red-400/30
              @elseif($product->kategori=='alkes') bg-green-500/20 text-green-300 border border-green-400/30
              @else bg-blue-500/20 text-blue-300 border border-blue-400/30 @endif">
              {{ strtoupper($product->kategori) }}
            </div>
          @endif
        </div>

      </div>

      {{-- GARIS PEMBATAS --}}
      <div class="my-6 border-b border-white/10"></div>

      {{-- INFORMASI UTAMA --}}
      <h3 class="text-lg font-semibold mb-2">Informasi Utama</h3>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-blue-200">
        <div><span class="font-semibold text-white">SKU:</span> {{ $product->sku }}</div>
        <div><span class="font-semibold text-white">Stok:</span> {{ $product->stock ?? '-' }}</div>
        <div><span class="font-semibold text-white">Satuan:</span> {{ $product->unit ?? '-' }}</div>
        <div><span class="font-semibold text-white">TKDN:</span> {{ $product->tkdn ? $product->tkdn.'%' : '-' }}</div>
        <div><span class="font-semibold text-white">Izin Edar:</span> {{ $product->izin_edar ?? '-' }}</div>
        <div><span class="font-semibold text-white">Lead Time:</span> {{ $product->lead_time_days ?? '-' }} Hari</div>
      </div>

      {{-- GARIS PEMBATAS --}}
      <div class="my-6 border-b border-white/10"></div>

      {{-- =============== TIPE PRODUK KHUSUS ================= --}}
      @if($product->tipe_produk == 'barang')

        <h3 class="text-lg font-semibold mb-3">Informasi Barang</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-blue-200">
          <div><span class="font-semibold text-white">Dalam Negeri:</span> {{ $product->is_dalam_negeri ? 'Ya' : 'Tidak' }}</div>
          <div><span class="font-semibold text-white">UMK:</span> {{ $product->is_umk ? 'Ya' : 'Tidak' }}</div>
          <div><span class="font-semibold text-white">Konsolidasi:</span> {{ $product->is_konsolidasi ? 'Ya' : 'Tidak' }}</div>
          <div><span class="font-semibold text-white">Sertifikat TKDN:</span> {{ $product->is_tkdn_sertifikat ? 'Ya' : 'Tidak' }}</div>
        </div>

        {{-- dokumen barang (kategori) --}}
        <div class="mt-6 text-sm">

          {{-- OBAT --}}
          @if($product->kategori == 'obat')
            <h4 class="font-semibold text-blue-300 mb-2">Dokumen Khusus Obat</h4>

            <x-pdf-field label="Nomor BPOM" :value="$product->izin_bpom" />
            <x-pdf-link file="{{ $product->sertifikat_cpob }}" text="Sertifikat CPOB" />
            <x-pdf-link file="{{ $product->surat_distributor }}" text="Surat Penunjukan Distributor" />

          @endif

          {{-- ALKES --}}
          @if($product->kategori=='alkes')
            <h4 class="font-semibold text-blue-300 mb-2">Dokumen Khusus Alkes</h4>

            <x-pdf-field label="Nomor AKD" :value="$product->no_akd" />
            <x-pdf-field label="Nomor AKL" :value="$product->no_akl" />
            <x-pdf-field label="Nomor PKRT" :value="$product->no_pkrt" />

            <x-pdf-link file="{{ $product->dokumen_tkdn }}" text="Dokumen TKDN" />
            <x-pdf-link file="{{ $product->dokumen_garansi }}" text="Dokumen Garansi" />
            <x-pdf-link file="{{ $product->dokumen_uji_coba }}" text="Dokumen Uji Coba RSUD" />
          @endif

          {{-- UMUM --}}
          @if($product->kategori=='umum')
            <h4 class="font-semibold text-blue-300 mb-2">Dokumen Produk Umum</h4>
            <x-pdf-link file="{{ $product->surat_penunjukan }}" text="Surat Penunjukan Distributor" />
          @endif

        </div>

      @endif

      {{-- ================== JASA ================== --}}
      @if($product->tipe_produk == 'jasa')
        <h3 class="text-lg font-semibold mb-3">Informasi Jasa</h3>
        <div class="text-sm">
          <span class="font-semibold text-white">Jenis Jasa:</span> {{ $product->jenis_jasa ?? '-' }}
        </div>
      @endif

      {{-- ================== DIGITAL ================== --}}
      @if($product->tipe_produk == 'digital')
        <h3 class="text-lg font-semibold mb-3">Informasi Produk Digital</h3>
        <div class="text-sm">
          <span class="font-semibold text-white">Jenis Digital:</span> {{ $product->jenis_digital ?? '-' }}
        </div>
      @endif


      {{-- GARIS PEMBATAS --}}
      <div class="my-6 border-b border-white/10"></div>

      {{-- BUTTON --}}
      <div class="flex gap-3">
        <a href="{{ route('vendor.produk.edit', $product->id) }}"
           class="flex-1 bg-white/10 hover:bg-white/20 border border-white/20 py-3 rounded-xl text-center font-semibold">
          Ubah
        </a>

        <a href="{{ route('vendor.produk') }}"
           class="flex-1 bg-blue-600 hover:bg-blue-700 py-3 rounded-xl text-center font-semibold">
          Kembali
        </a>
      </div>

    </div>

  </div>
</div>
@endsection
