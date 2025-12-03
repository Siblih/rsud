@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white pb-24 px-4 pt-6">

  <div class="max-w-md mx-auto bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-xl p-6">

    <h2 class="text-xl font-bold mb-4 text-center">⚙️ Pengaturan Akun</h2>

    {{-- Pesan Sukses atau Error --}}
    @if(session('success'))
      <div class="bg-green-500/20 text-green-200 px-4 py-3 rounded-xl mb-4 border border-green-400/30">
        {{ session('success') }}
      </div>
    @elseif(session('error'))
      <div class="bg-red-500/20 text-red-200 px-4 py-3 rounded-xl mb-4 border border-red-400/30">
        {{ session('error') }}
      </div>
    @endif

    {{-- Form Ubah Password --}}
    <form action="{{ route('vendor.update-password') }}" method="POST" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm text-blue-200 mb-1">Password Lama</label>
        <input type="password" name="current_password"
               class="w-full rounded-xl border border-white/30 bg-white/10 text-white px-3 py-2 focus:outline-none focus:ring focus:ring-blue-400"
               placeholder="Masukkan password lama">
        @error('current_password')
          <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm text-blue-200 mb-1">Password Baru</label>
        <input type="password" name="new_password"
               class="w-full rounded-xl border border-white/30 bg-white/10 text-white px-3 py-2 focus:outline-none focus:ring focus:ring-blue-400"
               placeholder="Minimal 6 karakter">
        @error('new_password')
          <p class="text-red-300 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm text-blue-200 mb-1">Konfirmasi Password Baru</label>
        <input type="password" name="new_password_confirmation"
               class="w-full rounded-xl border border-white/30 bg-white/10 text-white px-3 py-2 focus:outline-none focus:ring focus:ring-blue-400"
               placeholder="Ulangi password baru">
      </div>

      <button type="submit"
              class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-xl transition">
        🔐 Ubah Password
      </button>
    </form>

    <hr class="my-6 border-white/30">

    <div class="text-center">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
                class="text-red-300 hover:text-red-400 transition font-medium">
          🚪 Keluar dari Akun
        </button>
      </form>
    </div>

  </div>
</div>
@endsection
