@extends('layouts.app')

@section('content')
<div class="flex flex-col min-h-screen bg-gray-50">

  <!-- Header di dalam konten -->
  <div class="text-center mt-4 mb-6">
    <h1 class="text-xl font-bold text-gray-800">Dashboard Vendor</h1>
    <p class="text-sm text-gray-500">Akses cepat ke semua fitur vendor</p>
  </div>

  <!-- Card Profil -->
  <div class="bg-white p-4 rounded-2xl shadow mb-4 mx-2">
    <h2 class="font-semibold text-gray-800">Halo, {{ Auth::user()->name }}</h2>
    <p class="text-sm text-gray-500 mt-1">
      Status:
      <span class="font-medium 
        {{ (Auth::user()->status ?? '') === 'verified' ? 'text-green-600' : 'text-yellow-600' }}">
        {{ ucfirst(Auth::user()->status ?? 'Belum diverifikasi') }}
      </span>
    </p>
  </div>

  <!-- Menu Grid -->
  <div class="grid grid-cols-2 gap-4 px-2 mb-20">
    <a href="{{ url('/vendor/pengadaan') }}" 
       class="bg-white p-5 rounded-2xl shadow text-center hover:bg-blue-50 transition">
      <div class="text-3xl mb-2">🧾</div>
      <p class="text-sm font-semibold text-gray-700">Pengadaan Aktif</p>
    </a>

    <a href="{{ url('/vendor/profile/edit') }}" 
       class="bg-white p-5 rounded-2xl shadow text-center hover:bg-blue-50 transition">
      <div class="text-3xl mb-2">🏢</div>
      <p class="text-sm font-semibold text-gray-700">Profil Perusahaan</p>
    </a>

    <a href="{{ url('/vendor/riwayat') }}" 
       class="bg-white p-5 rounded-2xl shadow text-center hover:bg-blue-50 transition">
      <div class="text-3xl mb-2">📜</div>
      <p class="text-sm font-semibold text-gray-700">Riwayat</p>
    </a>

    <a href="{{ url('/vendor/dokumen') }}" 
       class="bg-white p-5 rounded-2xl shadow text-center hover:bg-blue-50 transition">
      <div class="text-3xl mb-2">📤</div>
      <p class="text-sm font-semibold text-gray-700">Upload Dokumen</p>
    </a>

    <a href="{{ url('/vendor/settings') }}" 
       class="bg-white p-5 rounded-2xl shadow text-center hover:bg-blue-50 transition col-span-2">
      <div class="text-3xl mb-2">⚙️</div>
      <p class="text-sm font-semibold text-gray-700">Pengaturan</p>
    </a>
  </div>

</div>
@endsection
