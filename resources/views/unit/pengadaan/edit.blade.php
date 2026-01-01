@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white pb-24 px-4 pt-6">
  <div class="max-w-md mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <a href="{{ route('unit.pengadaan.index') }}" class="flex items-center text-gray-300 hover:text-white transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        <span>Kembali</span>
      </a>
      <h1 class="text-lg font-bold text-white">📝 Perbarui Pengadaan</h1>
      <div class="w-6"></div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('unit.pengadaan.update', $pengadaan->id) }}">
      @csrf
      @method('PUT')

      <!-- Nama -->
      <div>
        <label class="block text-sm font-semibold mb-1 text-blue-200">Nama Pengadaan</label>
        <input type="text" name="nama_pengadaan"
          value="{{ old('nama_pengadaan', $pengadaan->nama_pengadaan) }}"
          class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white"
          required>
      </div>

      <!-- Jenis -->
      <div>
        <label class="block text-sm font-semibold mb-1 text-blue-200">Jenis Pengadaan</label>
        <select id="jenis_pengadaan" name="jenis_pengadaan"
          class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white">
          <option value="">Pilih Jenis</option>
          <option value="barang" {{ old('jenis_pengadaan', $pengadaan->jenis_pengadaan) == 'barang' ? 'selected' : '' }}>Barang</option>
          <option value="jasa" {{ old('jenis_pengadaan', $pengadaan->jenis_pengadaan) == 'jasa' ? 'selected' : '' }}>Jasa</option>
        </select>
      </div>

      <!-- BARANG -->
      <div id="form-barang" class="space-y-4 hidden">
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-sm text-blue-200">Jumlah</label>
            <input type="number" name="jumlah"
              value="{{ old('jumlah', $pengadaan->jumlah) }}"
              class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white">
          </div>
          <div>
            <label class="text-sm text-blue-200">Satuan</label>
            <input type="text" name="satuan"
              value="{{ old('satuan', $pengadaan->satuan) }}"
              class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white">
          </div>
        </div>

        <div>
          <label class="text-sm text-blue-200">Spesifikasi Teknis</label>
          <textarea name="spesifikasi" rows="3"
            class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white">{{ old('spesifikasi', $pengadaan->spesifikasi) }}</textarea>
        </div>
      </div>

      <!-- JASA -->
      <div id="form-jasa" class="space-y-4 hidden">
        <div>
          <label class="text-sm text-blue-200">Uraian Pekerjaan</label>
          <textarea name="uraian_pekerjaan" rows="3"
            class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white">{{ old('uraian_pekerjaan', $pengadaan->uraian_pekerjaan) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-sm text-blue-200">Lokasi Pekerjaan</label>
            <input type="text" name="lokasi_pekerjaan"
              value="{{ old('lokasi_pekerjaan', $pengadaan->lokasi_pekerjaan) }}"
              class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white">
          </div>
          <div>
            <label class="text-sm text-blue-200">Waktu Pelaksanaan</label>
            <input type="text" name="waktu_pelaksanaan"
              value="{{ old('waktu_pelaksanaan', $pengadaan->waktu_pelaksanaan) }}"
              class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white">
          </div>
        </div>
      </div>

      <!-- Estimasi -->
      <div>
        <label class="text-sm text-blue-200">Estimasi Anggaran</label>
        <input type="number" name="estimasi_anggaran"
          value="{{ old('estimasi_anggaran', $pengadaan->estimasi_anggaran) }}"
          class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white">
      </div>

      <!-- Alasan -->
      <div>
        <label class="text-sm text-blue-200">Alasan</label>
        <textarea name="alasan" rows="2"
          class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white">{{ old('alasan', $pengadaan->alasan) }}</textarea>
      </div>

      <!-- Tombol -->
      @if($pengadaan->status === 'menunggu')
      <div class="pt-4">
        <button type="submit"
          class="w-full bg-blue-600 py-3 rounded-xl font-semibold">
          💾 Perbarui Pengadaan
        </button>
      </div>
      @endif
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const jenis = "{{ old('jenis_pengadaan', $pengadaan->jenis_pengadaan) }}";
  const barang = document.getElementById('form-barang');
  const jasa = document.getElementById('form-jasa');

  if (jenis === 'barang') barang.classList.remove('hidden');
  if (jenis === 'jasa') jasa.classList.remove('hidden');

  document.getElementById('jenis_pengadaan').addEventListener('change', function () {
    barang.classList.add('hidden');
    jasa.classList.add('hidden');
    if (this.value === 'barang') barang.classList.remove('hidden');
    if (this.value === 'jasa') jasa.classList.remove('hidden');
  });
});
</script>
@endsection
