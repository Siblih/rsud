@extends('layouts.admin-app')

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

          {{-- Vendor --}}
          <p class="text-xs text-blue-200">Vendor: {{ $product->vendor->name }}</p>

          <p class="text-blue-200 mt-1 text-sm">{{ $product->description }}</p>
          
          <div class="mt-3 text-xl font-semibold">
            Rp{{ number_format($product->price,0,',','.') }}
          </div>

          {{-- Badge kategori --}}
          <div class="mt-2 inline-block px-3 py-1 text-xs rounded-full 
              @if($product->kategori=='obat') bg-red-500/20 text-red-300 border border-red-400/30
              @elseif($product->kategori=='alkes') bg-green-500/20 text-green-300 border border-green-400/30
              @else bg-blue-500/20 text-blue-300 border border-blue-400/30 @endif">
            {{ strtoupper($product->kategori) }}
          </div>

          {{-- Status --}}
          <div class="mt-2">
            <span class="px-3 py-1 rounded-full text-xs 
              @if($product->status=='verified') bg-green-500/20 text-green-300 border border-green-400/30
              @elseif($product->status=='rejected') bg-red-500/20 text-red-300 border border-red-400/30
              @else bg-yellow-500/20 text-yellow-300 border border-yellow-400/30 @endif">
              Status: {{ ucfirst($product->status) }}
            </span>
          </div>
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


      {{-- DOKUMEN PERSYARATAN --}}
      <h3 class="text-lg font-semibold mb-3">Dokumen Persyaratan</h3>

      <div class="space-y-2 text-sm">

        {{-- ========== OBAT ========== --}}
        @if($product->kategori=='obat')
          <div class="text-blue-300 font-semibold mb-2">Dokumen Produk Obat</div>

          <x-pdf-field label="Nomor BPOM" :value="$product->izin_bpom" />

          <x-pdf-link file="{{ $product->sertifikat_cpob }}" text="Sertifikat CPOB" />
          <x-pdf-link file="{{ $product->surat_distributor }}" text="Surat Penunjukan Distributor" />

        @endif

        {{-- ========== ALKES ========== --}}
        @if($product->kategori=='alkes')
          <div class="text-blue-300 font-semibold mb-2">Dokumen Alat Kesehatan</div>

          <x-pdf-field label="Nomor AKD" :value="$product->no_akd" />
          <x-pdf-field label="Nomor AKL" :value="$product->no_akl" />
          <x-pdf-field label="Nomor PKRT" :value="$product->no_pkrt" />

          <x-pdf-link file="{{ $product->dokumen_tkdn }}" text="Dokumen TKDN" />
          <x-pdf-link file="{{ $product->dokumen_garansi }}" text="Dokumen Garansi" />
          <x-pdf-link file="{{ $product->dokumen_uji_coba }}" text="Dokumen Uji Coba RSUD" />
        @endif

        {{-- ========== UMUM ========== --}}
        @if($product->kategori=='umum')
          <div class="text-blue-300 font-semibold mb-2">Dokumen Produk Umum</div>

          <x-pdf-link file="{{ $product->surat_penunjukan }}" text="Surat Penunjukan Distributor" />
        @endif

      </div>

      {{-- GARIS PEMBATAS --}}
      <div class="my-6 border-b border-white/10"></div>

      {{-- FORM VERIFIKASI --}}
<div class="space-y-4">

    {{-- SETUJUI --}}
    <form action="{{ route('admin.verifikasi.produk.setujui', $product->id) }}" method="POST">
        @csrf
        <button class="w-full bg-green-600 hover:bg-green-700 py-3 rounded-xl font-semibold">
            ✔ Setujui Produk
        </button>
    </form>

    {{-- TOLAK (muncul form setelah tombol ditekan) --}}
    <div x-data="{ showForm: false }" class="space-y-2">

        {{-- Tombol "Tolak" utama --}}
        <button 
            type="button"
            @click="showForm = true"
            class="w-full bg-red-600 hover:bg-red-700 py-3 rounded-xl font-semibold">
            ✖ Tolak Produk
        </button>

        {{-- FORM Alasan Penolakan --}}
        <form 
            x-show="showForm"
            x-transition
            action="{{ route('admin.verifikasi.produk.tolak', $product->id) }}" 
            method="POST" 
            class="space-y-2 bg-white/5 p-3 rounded-xl border border-white/10">

            @csrf

            <textarea 
                name="reason" 
                placeholder="Alasan penolakan (wajib diisi)"
                class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white"
                required></textarea>

            <button 
                class="w-full bg-red-700 hover:bg-red-800 py-3 rounded-xl font-semibold">
                ✔ Konfirmasi Penolakan
            </button>

            <button 
                type="button"
                @click="showForm = false"
                class="w-full bg-white/10 hover:bg-white/20 py-2 rounded-xl text-sm">
                Batal
            </button>
        </form>
    </div>

    {{-- KEMBALI --}}
    <a href="{{ route('admin.verifikasi', ['tab' => 'produk']) }}"
        class="block bg-white/10 hover:bg-white/20 border border-white/20 py-3 rounded-xl text-center font-semibold">
        Kembali
    </a>

</div>


    </div>

  </div>
</div>
@endsection
