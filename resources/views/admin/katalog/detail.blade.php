@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] px-6 py-10">

    {{-- TOMBOL KEMBALI --}}
    <a href="{{ route('admin.katalog') }}"
       class="inline-block mb-6 px-4 py-2 bg-white text-gray-800 rounded-lg shadow hover:bg-gray-200">
        ← Kembali
    </a>

    {{-- ============================ --}}
    {{-- GRID UTAMA --}}
    {{-- ============================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ======================= --}}
        {{-- KOLOM KIRI – GALERI --}}
        {{-- ======================= --}}
        <div class="lg:col-span-2">

            {{-- FOTO UTAMA --}}
            <div class="bg-white rounded-xl shadow p-4 flex items-center justify-center h-[430px]">
                @if ($product->photos && count($product->photos))
                    <img id="mainImage"
                        src="{{ asset('storage/' . $product->photos[0]) }}"
                        class="object-contain h-full">
                @else
                    <div class="text-gray-500">No Image</div>
                @endif
            </div>

            {{-- THUMBNAILS --}}
            <div class="flex gap-3 mt-4 overflow-x-auto pb-2">
                @foreach ($product->photos as $photo)
                    <img src="{{ asset('storage/' . $photo) }}"
                        class="w-24 h-20 object-cover cursor-pointer rounded border 
                               border-gray-300 hover:border-blue-500"
                        onclick="document.getElementById('mainImage').src=this.src;">
                @endforeach
            </div>

        </div>

        {{-- ================================= --}}
        {{-- KOLOM KANAN – INFO PRODUK & BELI --}}
        {{-- ================================= --}}
        <div class="bg-white rounded-xl shadow p-6">

            {{-- NAMA PRODUK --}}
            <p class="text-sm text-gray-600 mb-1">Barang</p>
            <h1 class="font-semibold text-xl leading-6 mb-3 text-gray-900">{{ $product->name }}</h1>

            {{-- HARGA --}}
            <p class="text-green-600 font-bold text-3xl mb-3">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            <hr class="border-gray-200 my-4">

            {{-- JUMLAH --}}
            <label class="font-semibold text-sm text-gray-700">Jumlah</label>
            <div class="flex items-center gap-3 mt-2 mb-5">
                <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded">-</button>
                <input type="number" value="1"
                       class="border border-gray-300 bg-white text-gray-800 px-3 py-1 w-20 rounded text-center">
                <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded">+</button>
            </div>

            {{-- TOMBOL --}}
            <button class="w-full bg-orange-600 text-white py-2 rounded-lg font-semibold mb-3">
                + Tambah Keranjang
            </button>

            <button class="w-full bg-red-600 text-white py-2 rounded-lg font-semibold">
                Beli Langsung
            </button>

        </div>

    </div>

{{-- ========================= --}}
{{-- IDENTITAS VENDOR --}}
{{-- ========================= --}}
<div class="bg-white rounded-xl shadow p-5 mt-8 flex items-center justify-between">

    {{-- Kiri: Badge + Identitas Vendor --}}
    <div class="flex items-start gap-4">

        {{-- Badge UMK --}}
        <div class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-md border border-blue-300">
            {{ $product->is_umk ? 'UMK' : 'Non UMK' }}
        </div>

        {{-- Info Vendor --}}
        <div class="leading-tight text-sm">

            {{-- Nama Vendor --}}
            <p class="font-semibold text-gray-900">
                {{ $product->vendor->vendorProfile->company_name ?? $product->vendor->name ?? 'Nama Vendor Tidak Ada' }}
            </p>

            {{-- Alamat --}}
            <p class="text-gray-600 mt-1">
                {{ $product->vendor->vendorProfile->alamat ?? 'Alamat tidak tersedia' }}
            </p>

            {{-- Bidang Usaha --}}
            @if ($product->vendor->vendorProfile->bidang_usaha ?? false)
            <p class="text-gray-600 text-xs mt-1">
                <span class="font-medium">Bidang Usaha:</span>
                {{ $product->vendor->vendorProfile->bidang_usaha }}
            </p>
            @endif

            {{-- PIC --}}
            @if ($product->vendor->vendorProfile->contact_person ?? false)
            <p class="text-gray-600 text-xs mt-1">
                <span class="font-medium">PIC:</span>
                {{ $product->vendor->vendorProfile->contact_person }}
            </p>
            @endif

            {{-- Phone --}}
            @if ($product->vendor->vendorProfile->phone ?? false)
            <p class="text-gray-600 text-xs">
                <span class="font-medium">Telp:</span>
                {{ $product->vendor->vendorProfile->phone }}
            </p>
            @endif

        </div>

    </div>

    {{-- Tombol Kunjungi Vendor --}}
    <a href="{{ route('admin.katalog.show', $product->vendor->id) }}"
        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm rounded-lg border border-gray-300">
        Kunjungi Vendor
    </a>

</div>



{{-- ===================================================== --}}
{{-- INFORMASI PRODUK DALAM NEGERI (TKDN) --}}
{{-- ===================================================== --}}
<div class="mt-10 bg-white p-6 rounded-xl shadow text-gray-900">

    <h2 class="text-lg font-semibold mb-4">Informasi Produk Dalam Negeri</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">

        <div>
            <p class="text-gray-500">Jenis Produk</p>
            <p class="font-semibold">{{ $product->is_dalam_negeri ? 'PDN' : 'Impor' }}</p>
        </div>

        <div>
            <p class="text-gray-500">Nilai TKDN(%)</p>
            <p class="font-semibold">{{ $product->tkdn }}%</p>
        </div>

        <div>
            <p class="text-gray-500">UMK</p>
            <p class="font-semibold">{{ $product->is_umk ? 'UMK' : 'Non UMK' }}</p>
        </div>

        <div>
            <p class="text-gray-500">Kategori</p>
            <p class="font-semibold">{{ ucfirst($product->kategori ?? '-') }}</p>
        </div>

    </div>

</div>



{{-- ================================== --}}
{{-- DETAIL PRODUK --}}
{{-- ================================== --}}
<div class="mt-10 bg-white p-6 rounded-xl shadow text-gray-900">

    {{-- Tab --}}
    <div class="flex gap-6 border-b border-gray-300 pb-2 mb-5">
        <span class="text-red-600 font-semibold border-b-2 border-red-600 pb-2 cursor-pointer">
            Detail Produk
        </span>
        <span class="text-gray-500 cursor-pointer">Info Pendukung</span>
    </div>

    <table class="w-full text-sm text-gray-700">
        <tr class="border-b border-gray-200">
            <td class="py-2 font-semibold w-40">Merek</td>
            <td class="py-2">{{ $product->brand ?? '-' }}</td>
        </tr>

        <tr class="border-b border-gray-200">
            <td class="py-2 font-semibold">Model</td>
            <td class="py-2">{{ $product->model ?? '-' }}</td>
        </tr>

        <tr class="border-b border-gray-200">
            <td class="py-2 font-semibold">SKU</td>
            <td class="py-2">{{ $product->sku ?? '-' }}</td>
        </tr>

        <tr class="border-b border-gray-200">
            <td class="py-2 font-semibold">Warna</td>
            <td class="py-2">{{ $product->warna ?? '-' }}</td>
        </tr>

        <tr>
            <td class="py-2 font-semibold">Deskripsi</td>
            <td class="py-2">{!! nl2br(e($product->description)) !!}</td>
        </tr>
    </table>

</div>

</div>
@endsection
