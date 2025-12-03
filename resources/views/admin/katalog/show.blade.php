@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white px-6 py-10">

    {{-- Tombol Kembali --}}
    <a href="{{ route('admin.katalog') }}"
       class="inline-flex items-center mb-6 px-4 py-2 bg-gray-100 hover:bg-gray-200 
              text-gray-800 text-sm rounded-lg border border-gray-300 transition">
        ← Kembali
    </a>

    <h1 class="text-2xl font-bold mb-6">🏢 Detail Vendor</h1>

    {{-- Profil Vendor --}}
    <div class="bg-[#1C315E] rounded-xl p-6 shadow-lg mb-8">

        <h2 class="text-xl font-bold">{{ $vendor->vendorProfile->company_name ?? $vendor->name }}</h2>

        <p class="text-gray-300 text-sm mt-1">
            PIC: {{ $vendor->name }}
        </p>

        <p class="text-gray-300 text-sm">
            Email: {{ $vendor->email }}
        </p>

        <p class="text-gray-400 text-sm mt-2">
            📍 Alamat: {{ $vendor->vendorProfile->alamat ?? 'Alamat tidak tersedia' }}
        </p>

        <span class="inline-block mt-4 px-3 py-1 text-xs rounded-full font-semibold bg-green-600/40 text-green-200">
            Aktif & Terverifikasi
        </span>
    </div>


    {{-- Produk Vendor --}}
    <h2 class="text-xl font-bold mb-4">📦 Produk dari Vendor Ini</h2>

    @if ($products->count() == 0)
        <p class="text-gray-300">Vendor ini belum memiliki produk terverifikasi.</p>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach ($products as $p)
        <div class="bg-[#0B3A66] rounded-xl p-4 shadow hover:shadow-lg transition">

            <img src="{{ asset('storage/' . ($p->photos[0] ?? 'default.png')) }}"
                 class="rounded-lg mb-3 w-full h-40 object-cover">

            <h3 class="text-lg font-semibold">{{ $p->name }}</h3>

            <p class="text-gray-300 text-sm">
                Harga: Rp {{ number_format($p->price, 0, ',', '.') }}
            </p>

            <a href="{{ url('/admin/katalog/' . $p->id) }}"
               class="block mt-4 bg-blue-600 text-center py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Lihat Detail Produk
            </a>

        </div>
        @endforeach

    </div>

</div>
@endsection
