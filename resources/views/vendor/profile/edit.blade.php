@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-5 mt-3 mb-8 border border-gray-100">

  <!-- Header -->
  <div class="text-center mb-6">
    <h2 class="text-xl font-bold text-gray-800">🏢 Profil Perusahaan Vendor</h2>
    <p class="text-sm text-gray-500">Lengkapi informasi perusahaan Anda dengan benar</p>
  </div>

  {{-- Notifikasi sukses --}}
  @if (session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-4 text-center shadow">
      ✅ {{ session('success') }}
    </div>
  @endif

  <!-- Form -->
  <form method="POST" action="{{ route('vendor.profile.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-5">
    @csrf

    <!-- Kolom Kiri -->
    <div class="space-y-5">

      {{-- Nama Perusahaan --}}
      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">🏢 Nama Perusahaan</label>
        <div class="bg-gray-50 p-3 rounded-xl shadow-sm flex items-center gap-3">
          <input type="text" name="company_name"
            placeholder="Masukkan nama perusahaan"
            value="{{ old('company_name', $profile->company_name) }}"
            required
            class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-400">
        </div>
      </div>

      {{-- Bidang Usaha --}}
      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">💼 Bidang Usaha</label>
        <div class="bg-gray-50 p-3 rounded-xl shadow-sm flex flex-col gap-3">
          <select id="bidang_usaha_select"
            class="bg-white border border-gray-200 rounded-lg p-2 outline-none text-gray-700">
           <option value="" disabled selected>-- Pilih Bidang Usaha --</option>

            <option value="Alat Kesehatan">Alat Kesehatan</option>
            <option value="Alat Kebersihan">Alat Kebersihan</option>
            <option value="Jasa Konstruksi">Jasa Konstruksi</option>
            <option value="IT dan Elektronik">IT dan Elektronik</option>
            <option value="Obat dan Farmasi">Obat dan Farmasi</option>
            <option value="Lainnya">Lainnya (ketik manual)</option>
          </select>

          <input type="text" id="bidang_usaha_input" name="bidang_usaha"
            placeholder="Tulis bidang usaha Anda"
            value="{{ old('bidang_usaha', $profile->bidang_usaha) }}"
            class="hidden w-full bg-transparent border border-gray-200 rounded-lg p-2 outline-none text-gray-800 placeholder-gray-400">
        </div>
      </div>

      {{-- NIB --}}
      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">🧾 NIB</label>
        <div class="bg-gray-50 p-3 rounded-xl shadow-sm flex items-center gap-3">
          <input type="text" name="nib"
            placeholder="Nomor Induk Berusaha"
            value="{{ old('nib', $profile->nib) }}"
            class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-400">
        </div>
      </div>

      {{-- SIUP --}}
      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">📑 SIUP</label>
        <div class="bg-gray-50 p-3 rounded-xl shadow-sm flex items-center gap-3">
          <input type="text" name="siup"
            placeholder="Nomor SIUP"
            value="{{ old('siup', $profile->siup) }}"
            class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-400">
        </div>
      </div>
    </div>

    <!-- Kolom Kanan -->
    <div class="space-y-5">

      {{-- NPWP --}}
      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">💳 NPWP</label>
        <div class="bg-gray-50 p-3 rounded-xl shadow-sm flex items-center gap-3">
          <input type="text" name="npwp"
            placeholder="Nomor NPWP"
            value="{{ old('npwp', $profile->npwp) }}"
            class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-400">
        </div>
      </div>

      {{-- Alamat --}}
      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">📍 Alamat</label>
        <div class="bg-gray-50 p-3 rounded-xl shadow-sm flex items-start gap-3">
          <textarea name="alamat" placeholder="Alamat lengkap perusahaan"
            rows="2"
            class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-400 resize-none">{{ old('alamat', $profile->alamat) }}</textarea>
        </div>
      </div>

      {{-- Contact Person --}}
      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">👤 Contact Person</label>
        <div class="bg-gray-50 p-3 rounded-xl shadow-sm flex items-center gap-3">
          <input type="text" name="contact_person"
            placeholder="Nama penanggung jawab"
            value="{{ old('contact_person', $profile->contact_person) }}"
            class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-400">
        </div>
      </div>

      {{-- Telepon --}}
      <div>
        <label class="text-sm font-semibold text-gray-700 mb-1 block">📞 No. Telepon</label>
        <div class="bg-gray-50 p-3 rounded-xl shadow-sm flex items-center gap-3">
          <input type="text" name="phone"
            placeholder="Nomor yang dapat dihubungi"
            value="{{ old('phone', $profile->phone) }}"
            class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-400">
        </div>
      </div>
    </div>

    <!-- Deskripsi (Full Width) -->
    <div class="md:col-span-2">
      <label class="text-sm font-semibold text-gray-700 mb-1 block">📝 Deskripsi / Riwayat Pengalaman</label>
      <div class="bg-gray-50 p-3 rounded-xl shadow-sm flex items-start gap-3">
        <textarea name="description" placeholder="Ceritakan pengalaman, proyek, atau layanan Anda"
          rows="3"
          class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-400 resize-none">{{ old('description', $profile->description) }}</textarea>
      </div>
    </div>

    <!-- Tombol Simpan -->
    <div class="md:col-span-2 pt-5">
      <button type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-md transition">
        💾 Simpan Profil
      </button>
      <!-- Tombol Kembali -->
  <button type="button"
    onclick="window.history.back()"
    class="w-full bg-red-400 hover:bg-red-500 text-white font-semibold py-3 rounded-xl shadow-md transition">
    ⬅️ Kembali
</button>

    </div>
    
</div>

  </form>
</div>

{{-- Script interaktif untuk bidang usaha --}}
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

  // Kalau data lama berasal dari input manual
  if (input.value && !['Alat Kesehatan','Alat Kebersihan','Jasa Konstruksi','IT dan Elektronik','Obat dan Farmasi'].includes(input.value)) {
    select.value = 'Lainnya';
    input.classList.remove('hidden');
  }
</script>
@endsection
