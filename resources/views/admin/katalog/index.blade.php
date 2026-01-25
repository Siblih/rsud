@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-6 py-10">

    <h1 class="text-xl font-semibold mb-6">Katalog Produk Vendor</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        {{-- ======================= --}}
        {{-- SIDEBAR FILTER --}}
        {{-- ======================= --}}
        <div class="col-span-1 bg-[#1C2252] p-5 rounded-xl shadow-lg h-fit sticky top-4">
            <h2 class="text-lg font-semibold mb-4">Filter Produk</h2>

            <form action="{{ route('admin.katalog') }}" method="GET" class="space-y-4">

                {{-- TIPE PRODUK --}}
<div>
    <label class="font-semibold block mb-1">Tipe Produk</label>
    <select name="tipe_produk" class="w-full rounded-lg text-black p-2">
        <option value="">Semua</option>
        <option value="barang" {{ request('tipe_produk')=='barang' ? 'selected' : '' }}>Barang</option>
        <option value="jasa" {{ request('tipe_produk')=='jasa' ? 'selected' : '' }}>Jasa</option>
        <option value="digital" {{ request('tipe_produk')=='digital' ? 'selected' : '' }}>Digital</option>
    </select>
</div>

{{-- KATEGORI --}}
<div>
    <label class="font-semibold block mb-1">Kategori</label>
    <select name="kategori" class="w-full rounded-lg text-black p-2">
        <option value="">Semua</option>
        <option value="obat" {{ request('kategori')=='obat' ? 'selected' : '' }}>Obat</option>
        <option value="alkes" {{ request('kategori')=='alkes' ? 'selected' : '' }}>Alkes</option>
        <option value="umum" {{ request('kategori')=='umum' ? 'selected' : '' }}>Umum</option>
    </select>
</div>

{{-- TKDN --}}
<div>
    <label class="font-semibold block mb-1">Sertifikat TKDN</label>
    <select name="tkdn_sertif" class="w-full rounded-lg text-black p-2">
        <option value="">Semua</option>
        <option value="1" {{ request('tkdn_sertif')=='1' ? 'selected' : '' }}>Ya</option>
        <option value="0" {{ request('tkdn_sertif')=='0' ? 'selected' : '' }}>Tidak</option>
    </select>
</div>

{{-- ASAL PRODUK --}}
<div>
    <label class="font-semibold block mb-1">Asal Produk</label>
    <select name="asal" class="w-full rounded-lg text-black p-2">
        <option value="">Semua</option>
        <option value="dalam" {{ request('asal')=='dalam' ? 'selected' : '' }}>Dalam Negeri</option>
        <option value="impor" {{ request('asal')=='impor' ? 'selected' : '' }}>Impor</option>
    </select>
</div>

{{-- UMK --}}
<div>
    <label class="font-semibold block mb-1">UMK</label>
    <select name="umk" class="w-full rounded-lg text-black p-2">
        <option value="">Semua</option>
        <option value="1" {{ request('umk')=='1' ? 'selected' : '' }}>UMK</option>
        <option value="0" {{ request('umk')=='0' ? 'selected' : '' }}>Non UMK</option>
    </select>
</div>

{{-- KONSOLIDASI --}}
<div>
    <label class="font-semibold block mb-1">Konsolidasi</label>
    <select name="konsolidasi" class="w-full rounded-lg text-black p-2">
        <option value="">Semua</option>
        <option value="1" {{ request('konsolidasi')=='1' ? 'selected' : '' }}>Ya</option>
        <option value="0" {{ request('konsolidasi')=='0' ? 'selected' : '' }}>Tidak</option>
    </select>
</div>

{{-- HARGA MIN --}}
<div>
    <label class="font-semibold block mb-1">Harga Minimum</label>
    <input type="number" name="min" class="w-full text-black p-2 rounded-lg"
           value="{{ request('min') }}" placeholder="0">
</div>

{{-- HARGA MAX --}}
<div>
    <label class="font-semibold block mb-1">Harga Maksimum</label>
    <input type="number" name="max" class="w-full text-black p-2 rounded-lg"
           value="{{ request('max') }}" placeholder="000000">
</div>


                {{-- SUBMIT --}}
                <button class="bg-blue-500 hover:bg-blue-600 w-full py-2 rounded-lg font-semibold">
                    Terapkan Filter
                </button>

            </form>
        </div>

        {{-- ======================= --}}
        {{-- LIST PRODUK --}}
        {{-- ======================= --}}
        <div class="col-span-1 md:col-span-3">

            @if ($products->count() == 0)
                <p class="text-center text-gray-300 mt-10">Tidak ada produk ditemukan.</p>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($products as $p)
               <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition p-3 cursor-pointer"
     onclick="window.location.href='{{ route('admin.katalog.detail', [
        'id' => $p->id,
        'pengadaan' => request('pengadaan')
     ]) }}'">

    {{-- FOTO PRODUK --}}
    <div class="relative w-full h-40 rounded-lg overflow-hidden bg-gray-100">
        @if ($p->photos && count($p->photos) > 0)
            <img src="{{ asset('storage/'.$p->photos[0]) }}"
                 class="w-full h-full object-cover">
        @else
            <div class="flex items-center justify-center h-full text-gray-400 text-sm">
                No Image
            </div>
        @endif

        {{-- BADGE TIPE PRODUK --}}
        <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
            {{ ucfirst($p->tipe_produk ?? 'Barang') }}
        </span>
    </div>

    {{-- NAMA PRODUK --}}
    <h3 class="mt-3 font-semibold text-gray-900 text-sm line-clamp-2 min-h-[40px]">
        {{ $p->name }}
    </h3>

    {{-- HARGA --}}
    <p class="text-green-600 font-bold text-base mt-1">
        Rp {{ number_format($p->price, 0, ',', '.') }}
    </p>

    {{-- BADGE TKDN - UMK - KONSOLIDASI --}}
    <div class="flex gap-2 mt-2 flex-wrap">

        @if ($p->tkdn)
        <span class="bg-green-100 text-green-700 text-[10px] px-2 py-[2px] rounded font-semibold">
            TKDN {{ $p->tkdn }}%
        </span>
        @endif

        @if ($p->is_umk)
        <span class="bg-yellow-100 text-yellow-700 text-[10px] px-2 py-[2px] rounded font-semibold">
            UMK
        </span>
        @endif

        @if ($p->is_dalam_negeri)
        <span class="bg-blue-100 text-blue-700 text-[10px] px-2 py-[2px] rounded font-semibold">
            PDN
        </span>
        @else
        <span class="bg-red-100 text-red-700 text-[10px] px-2 py-[2px] rounded font-semibold">
            Impor
        </span>
        @endif
    </div>

    {{-- LOKASI VENDOR --}}
    <p class="text-gray-500 text-xs mt-2">
        {{ $p->vendor->vendorProfile->alamat ?? 'Lokasi tidak tersedia' }}
    </p>

</div>

                @endforeach

            </div>

        </div>

    </div>
</div>
@endsection
