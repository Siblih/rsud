@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white pb-24 px-4 pt-6">
  <div class="max-w-3xl mx-auto">

    <!-- Header -->
    <div class="text-center mb-6">
      <h2 class="text-xl font-bold text-white">🏢 Profil Perusahaan Vendor</h2>
      <p class="text-sm text-blue-200">Lengkapi informasi perusahaan Anda dengan benar</p>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
      <div class="bg-green-500/20 text-green-200 px-4 py-2 rounded-lg mb-4 text-center border border-green-400/30 backdrop-blur-md">
        ✅ {{ session('success') }}
      </div>
    @elseif (session('info'))
      <div class="bg-blue-500/20 text-blue-200 px-4 py-2 rounded-lg mb-4 text-center border border-blue-400/30 backdrop-blur-md">
        ℹ️ {{ session('info') }}
      </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('vendor.profile.update') }}"
          class="bg-white/10 backdrop-blur-lg p-6 rounded-2xl shadow-xl border border-white/20 grid grid-cols-1 md:grid-cols-2 gap-5">
      @csrf

      <!-- Kolom Kiri -->
      <div class="space-y-5">

        {{-- Nama Perusahaan --}}
        <div>
          <label class="block text-sm font-semibold mb-1 text-blue-200">🏢 Nama Perusahaan</label>
          <input type="text" name="company_name"
            placeholder="Masukkan nama perusahaan"
            value="{{ old('company_name', $profile->company_name) }}"
            required
            class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 outline-none transition">
        </div>

        {{-- Bidang Usaha --}}
        <div>
          <label class="block text-sm font-semibold mb-1 text-blue-200">💼 Bidang Usaha</label>
          <select id="bidang_usaha_select"
            class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 outline-none transition">
            <option value="" disabled selected class="text-gray-700">-- Pilih Bidang Usaha --</option>
            <option value="Alat Kesehatan" class="text-gray-700">Alat Kesehatan</option>
            <option value="Alat Kebersihan" class="text-gray-700">Alat Kebersihan</option>
            <option value="Jasa Konstruksi" class="text-gray-700">Jasa Konstruksi</option>
            <option value="IT dan Elektronik" class="text-gray-700">IT dan Elektronik</option>
            <option value="Obat dan Farmasi" class="text-gray-700">Obat dan Farmasi</option>
            <option value="Lainnya" class="text-gray-700">Lainnya (ketik manual)</option>
          </select>

          <input type="text" id="bidang_usaha_input" name="bidang_usaha"
            placeholder="Tulis bidang usaha Anda"
            value="{{ old('bidang_usaha', $profile->bidang_usaha) }}"
            class="hidden w-full rounded-xl p-3 mt-2 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 outline-none transition">
        </div>

        {{-- NIB --}}
        <div>
          <label class="block text-sm font-semibold mb-1 text-blue-200">🧾 NIB</label>
          <input type="text" name="nib"
            placeholder="Nomor Induk Berusaha"
            value="{{ old('nib', $profile->nib) }}"
            class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 outline-none transition">
        </div>

        {{-- SIUP --}}
        <div>
          <label class="block text-sm font-semibold mb-1 text-blue-200">📑 SIUP</label>
          <input type="text" name="siup"
            placeholder="Nomor SIUP"
            value="{{ old('siup', $profile->siup) }}"
            class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 outline-none transition">
        </div>
      </div>

      <!-- Kolom Kanan -->
      <div class="space-y-5">

        {{-- NPWP --}}
        <div>
          <label class="block text-sm font-semibold mb-1 text-blue-200">💳 NPWP</label>
          <input type="text" name="npwp"
            placeholder="Nomor NPWP"
            value="{{ old('npwp', $profile->npwp) }}"
            class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 outline-none transition">
        </div>

        {{-- Alamat --}}
        <div>
          <label class="block text-sm font-semibold mb-1 text-blue-200">📍 Alamat</label>
          <textarea name="alamat" rows="2"
            placeholder="Alamat lengkap perusahaan"
            class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 resize-none focus:ring-2 focus:ring-blue-400 outline-none transition">{{ old('alamat', $profile->alamat) }}</textarea>
        </div>

        {{-- Contact Person --}}
        <div>
          <label class="block text-sm font-semibold mb-1 text-blue-200">👤 Contact Person</label>
          <input type="text" name="contact_person"
            placeholder="Nama penanggung jawab"
            value="{{ old('contact_person', $profile->contact_person) }}"
            class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 outline-none transition">
        </div>

        {{-- Telepon --}}
        <div>
          <label class="block text-sm font-semibold mb-1 text-blue-200">📞 No. Telepon</label>
          <input type="text" name="phone"
            placeholder="Nomor yang dapat dihubungi"
            value="{{ old('phone', $profile->phone) }}"
            class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 outline-none transition">
        </div>
      </div>

      <!-- Deskripsi -->
      <div class="md:col-span-2">
        <label class="block text-sm font-semibold mb-1 text-blue-200">📝 Deskripsi / Riwayat Pengalaman</label>
        <textarea name="description" rows="3"
          placeholder="Ceritakan pengalaman, proyek, atau layanan Anda"
          class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 resize-none focus:ring-2 focus:ring-blue-400 outline-none transition">{{ old('description', $profile->description) }}</textarea>
      </div>

      <!-- Tombol Simpan -->
      <div class="md:col-span-2 pt-5 space-y-3">
        <button type="submit"
          class="w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold py-3 rounded-xl shadow-md hover:opacity-90 active:scale-95 transition-all">
          💾 Simpan Profil
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Script bidang usaha --}}
<script>
  const select = document.getElementById('bidang_usaha_select');
  const input = document.getElementById('bidang_usaha_input');
  select.addEventListener('change', function() {
    if (this.value === 'Lainnya') {
      input.classList.remove('hidden');
      input.focus();
      input.value = '';
    } else {
      input.classList.add('hidden');
      input.value = this.value;
    }
  });
  if (input.value && !['Alat Kesehatan','Alat Kebersihan','Jasa Konstruksi','IT dan Elektronik','Obat dan Farmasi'].includes(input.value)) {
    select.value = 'Lainnya';
    input.classList.remove('hidden');
  } else {
    select.value = input.value;
  }
</script>
@endsection
