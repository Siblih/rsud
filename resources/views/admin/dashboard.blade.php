@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white p-6">
  <div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">🧑‍💼 Dashboard Admin</h1>
    <p class="text-gray-200">Selamat datang, {{ Auth::user()->name }}!</p>

    <div class="mt-6 grid grid-cols-2 gap-4">
      <a href="{{ route('admin.vendor.data_vendor') }}" class="bg-[#205295] p-4 rounded-xl shadow-md hover:bg-[#1E3E62] transition">
        <h3 class="font-semibold">📦 Data Vendor</h3>
      </a>
      <a href="{{ route('admin.pengadaan') }}" class="bg-[#205295] p-4 rounded-xl shadow-md hover:bg-[#1E3E62] transition">
        <h3 class="font-semibold">🧾 Pengadaan</h3>
      </a>
    </div>
  </div>
</div>
@endsection
