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

    <!-- Form Card -->
    <form method="POST" action="{{ route('unit.pengadaan.store') }}"
          class="bg-white/10 backdrop-blur-lg p-5 rounded-2xl shadow-xl space-y-5 border border-white/20">
      @csrf

      <!-- Nama Pengadaan -->
      <div>
        <label class="block text-sm font-semibold mb-1 text-blue-200">Nama Pengadaan</label>
        <input type="text" name="nama_pengadaan"
               class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition"
               placeholder="Contoh: Pengadaan Alat Medis / Jasa Konstruksi" required>
      </div>

      <!-- Jenis Pengadaan -->
      <div>
        <label class="block text-sm font-semibold mb-1 text-blue-200">Jenis Pengadaan</label>
        <select id="jenis_pengadaan" name="jenis_pengadaan"
                class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-blue-400 focus:border-transparent transition">
          <option value="" class="text-gray-700">Pilih Jenis</option>
          <option value="barang" class="text-gray-700">Barang</option>
          <option value="jasa" class="text-gray-700">Jasa</option>
        </select>
      </div>

      <!-- === Bagian Barang === -->
      <div id="form-barang" class="space-y-4 hidden">
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-semibold mb-1 text-blue-200">Jumlah</label>
            <input type="number" name="jumlah" 
                   class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent" 
                   placeholder="0">
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1 text-blue-200">Satuan</label>
            <input type="text" name="satuan" 
                   class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent" 
                   placeholder="Unit / Box / Paket">
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold mb-1 text-blue-200">Spesifikasi Teknis</label>
          <textarea name="spesifikasi" rows="3"
                    class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                    placeholder="Tuliskan detail teknis barang yang dibutuhkan..."></textarea>
        </div>
      </div>

      <!-- === Bagian Jasa === -->
      <div id="form-jasa" class="space-y-4 hidden">
        <div>
          <label class="block text-sm font-semibold mb-1 text-blue-200">Uraian Pekerjaan</label>
          <textarea name="uraian_pekerjaan" rows="3"
                    class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                    placeholder="Contoh: Pembangunan ruang rawat inap baru..."></textarea>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-semibold mb-1 text-blue-200">Lokasi Pekerjaan</label>
            <input type="text" name="lokasi_pekerjaan"
                   class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                   placeholder="Contoh: Gedung A">
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1 text-blue-200">Waktu Pelaksanaan</label>
            <input type="text" name="waktu_pelaksanaan"
                   class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                   placeholder="Contoh: 30 Hari">
          </div>
        </div>
      </div>

      <!-- Estimasi & Alasan -->
      <div>
        <label class="block text-sm font-semibold mb-1 text-blue-200">Estimasi Anggaran (Rp)</label>
        <input type="number" name="estimasi_anggaran"
               class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition"
               placeholder="0">
      </div>

      <div>
        <label class="block text-sm font-semibold mb-1 text-blue-200">Alasan Kebutuhan</label>
        <textarea name="alasan" rows="2"
                  class="w-full rounded-xl p-3 bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-400 focus:border-transparent outline-none transition"
                  placeholder="Kenapa pengadaan ini dibutuhkan..."></textarea>
      </div>

      <!-- Tombol -->
      @if(!isset($pengadaan) || (isset($pengadaan) && $pengadaan->status == 'menunggu'))
      <div class="pt-3">
        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-blue-500 text-white font-semibold py-3 rounded-xl shadow-md hover:opacity-90 active:scale-95 transition-all">
          🚀 {{ isset($pengadaan) ? 'Perbarui' : 'Kirim' }} Pengajuan
        </button>
      </div>
      @endif
    </form>
  </div>
</div>

<script>
  // Ubah form sesuai jenis pengadaan
  document.getElementById('jenis_pengadaan').addEventListener('change', function () {
    const formBarang = document.getElementById('form-barang');
    const formJasa = document.getElementById('form-jasa');
    
    if (this.value === 'barang') {
      formBarang.classList.remove('hidden');
      formJasa.classList.add('hidden');
    } else if (this.value === 'jasa') {
      formJasa.classList.remove('hidden');
      formBarang.classList.add('hidden');
    } else {
      formBarang.classList.add('hidden');
      formJasa.classList.add('hidden');
    }
  });
</script>
@endsection
