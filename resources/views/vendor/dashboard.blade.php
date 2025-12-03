@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white pb-24 px-4 pt-6">

  <!-- Header -->
  <div class="text-center mb-6">
    <h1 class="text-2xl font-bold text-white drop-shadow-md">📦 Dashboard Vendor</h1>
    <p class="text-sm text-blue-200">Akses cepat ke semua fitur vendor</p>
  </div>

  <!-- Profil Vendor -->
  <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-xl p-5 mb-6 max-w-md mx-auto">
    <h2 class="font-semibold text-lg text-blue-100">Halo, {{ Auth::user()->name }}</h2>
    @php
  $vendorProfile = Auth::user()->vendorProfile;
@endphp

<p class="text-sm mt-1">
  Status:
  <span class="font-medium 
    {{ ($vendorProfile->verification_status ?? '') === 'verified' ? 'text-green-400' : (($vendorProfile->verification_status ?? '') === 'rejected' ? 'text-red-400' : 'text-yellow-300') }}">
    {{ ucfirst($vendorProfile->verification_status ?? 'Belum diverifikasi') }}
  </span>
</p>

  </div>

  <!-- Menu Grid -->
  <div class="grid grid-cols-2 gap-4 max-w-md mx-auto mb-20">
    <a href="{{ url('/vendor/pengadaan') }}" 
       class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-md p-5 text-center hover:bg-white/20 transition-all duration-300">
      <div class="text-3xl mb-2">🧾</div>
      <p class="text-sm font-semibold text-blue-100">Pengadaan Aktif</p>
    </a>

    @php
    $vendorProfile = Auth::user()->vendorProfile;
    $hasProfile = $vendorProfile && $vendorProfile->company_name && $vendorProfile->nib;
@endphp

<a href="{{ $hasProfile ? route('vendor.profile.show') : route('vendor.profile.edit') }}" 
   class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-md p-5 text-center hover:bg-white/20 transition-all duration-300">
    <div class="text-3xl mb-2">🏢</div>
    <p class="text-sm font-semibold text-blue-100">Profil Perusahaan</p>
</a>


    <a href="{{ url('/vendor/riwayat') }}" 
       class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-md p-5 text-center hover:bg-white/20 transition-all duration-300">
      <div class="text-3xl mb-2">📜</div>
      <p class="text-sm font-semibold text-blue-100">Riwayat</p>
    </a>

    <a href="{{ route('vendor.documents.create') }}" 
       class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-md p-5 text-center hover:bg-white/20 transition-all duration-300">
      <div class="text-3xl mb-2">📤</div>
      <p class="text-sm font-semibold text-blue-100">Upload Dokumen</p>
    </a>

    <a href="{{ route('settings.index') }}" 
       class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-md p-5 text-center hover:bg-white/20 transition-all duration-300 col-span-2">
      <div class="text-3xl mb-2">⚙️</div>
      <p class="text-sm font-semibold text-blue-100">Pengaturan</p>
    </a>
  </div>

</div>
<script>
  lucide.createIcons();
</script>

@endsection
