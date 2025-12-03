@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white pb-24 px-4 pt-6">
  <div class="max-w-3xl mx-auto">

    <h1 class="text-lg font-semibold text-white mb-4">
      {{ isset($product) ? 'Ubah Produk' : 'Tambah Produk' }}
    </h1>

    <form 
      action="{{ isset($product) ? route('vendor.produk.update', $product->id) : route('vendor.produk.store') }}"
      method="POST" enctype="multipart/form-data"
      class="bg-white/10 backdrop-blur-lg p-5 rounded-2xl border border-white/20 grid grid-cols-1 md:grid-cols-2 gap-4">

      @csrf
      @if(isset($product)) @method('PUT') @endif


      {{-- ====================== --}}
      {{-- TIPE PRODUK (BARANG/JASA/DIGITAL) --}}
      {{-- ====================== --}}
      <div class="md:col-span-2">
        <label class="text-sm text-blue-200">Tipe Produk</label>
        <select name="tipe_produk" id="tipe_produk"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white" required>
          <option value="barang" {{ old('tipe_produk', $product->tipe_produk ?? '')=='barang' ? 'selected' : '' }}>Barang</option>
          <option value="jasa" {{ old('tipe_produk', $product->tipe_produk ?? '')=='jasa' ? 'selected' : '' }}>Jasa</option>
          <option value="digital" {{ old('tipe_produk', $product->tipe_produk ?? '')=='digital' ? 'selected' : '' }}>Produk Digital</option>
        </select>
      </div>


      {{-- ====================== --}}
      {{-- NAMA PRODUK --}}
      {{-- ====================== --}}
      <div class="md:col-span-2">
        <label class="text-sm text-blue-200">Nama Produk</label>
        <input name="name"
          value="{{ old('name', $product->name ?? '') }}" required
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>


      {{-- ============================================================ --}}
      {{-- ===============   FORM PRODUK TIPE BARANG   =============== --}}
      {{-- ============================================================ --}}
      <div class="md:col-span-2 barang-section hidden">

        {{-- Kategori Barang (UMUM / OBAT / ALKES) --}}
        <label class="text-sm text-blue-200">Kategori Produk Barang</label>
        <select name="kategori" id="kategori_barang"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
          <option value="umum" {{ old('kategori', $product->kategori ?? '')=='umum' ? 'selected':'' }}>Produk Umum</option>
          <option value="obat" {{ old('kategori', $product->kategori ?? '')=='obat' ? 'selected':'' }}>Obat</option>
          <option value="alkes" {{ old('kategori', $product->kategori ?? '')=='alkes' ? 'selected':'' }}>Alat Kesehatan</option>
        </select>

        {{-- ====== SUBFILTER Barang ====== --}}
        <div class="grid grid-cols-2 gap-2 mt-3">
          <label class="flex items-center gap-2 text-blue-200">
            <input type="checkbox" name="is_dalam_negeri"
              {{ old('is_dalam_negeri', $product->is_dalam_negeri ?? false) ? 'checked' : '' }}>
            Dalam Negeri
          </label>

          <label class="flex items-center gap-2 text-blue-200">
            <input type="checkbox" name="is_umk"
              {{ old('is_umk', $product->is_umk ?? false) ? 'checked' : '' }}>
            UMK
          </label>

          <label class="flex items-center gap-2 text-blue-200">
            <input type="checkbox" name="is_konsolidasi"
              {{ old('is_konsolidasi', $product->is_konsolidasi ?? false) ? 'checked' : '' }}>
            Konsolidasi
          </label>

          <label class="flex items-center gap-2 text-blue-200">
            <input type="checkbox" name="is_tkdn_sertifikat"
              {{ old('is_tkdn_sertifikat', $product->is_tkdn_sertifikat ?? false) ? 'checked' : '' }}>
            Bersertifikat TKDN
          </label>
        </div>
      </div>


      {{-- ========================= --}}
      {{-- FORM OBAT --}}
      {{-- ========================= --}}
      <div class="md:col-span-2 kategori-obat hidden">
        <label class="text-sm text-blue-200">Nomor Izin Edar (BPOM)</label>
        <input name="izin_bpom"
          value="{{ old('izin_bpom', $product->izin_bpom ?? '') }}"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">

        <label class="text-sm text-blue-200 mt-2">Sertifikat CPOB</label>
        <input type="file" name="sertifikat_cpob" accept="application/pdf"
          class="w-full rounded-lg p-2 bg-white/5 border border-white/20 text-white">

        @if(isset($product->sertifikat_cpob))
        <a href="{{ asset('storage/'.$product->sertifikat_cpob) }}" target="_blank"
          class="underline text-blue-300 text-xs">Lihat Sertifikat</a>
        @endif

        <label class="text-sm text-blue-200 mt-2">Surat Distributor</label>
        <input type="file" name="surat_distributor" accept="application/pdf"
          class="w-full rounded-lg p-2 bg-white/5 border border-white/20 text-white">
      </div>


      {{-- ========================= --}}
      {{-- FORM ALKES --}}
      {{-- ========================= --}}
      <div class="md:col-span-2 kategori-alkes hidden">
        <label class="text-sm text-blue-200">Nomor AKD</label>
        <input name="no_akd" value="{{ old('no_akd', $product->no_akd ?? '') }}"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">

        <label class="text-sm text-blue-200 mt-2">Nomor AKL</label>
        <input name="no_akl" value="{{ old('no_akl', $product->no_akl ?? '') }}"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">

        <label class="text-sm text-blue-200 mt-2">Nomor PKRT</label>
        <input name="no_pkrt" value="{{ old('no_pkrt', $product->no_pkrt ?? '') }}"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>


      {{-- ========================= --}}
      {{-- FORM PRODUK UMUM --}}
      {{-- ========================= --}}
      <div class="md:col-span-2 kategori-umum hidden">
        <label class="text-sm text-blue-200">Surat Penunjukan Distributor</label>
        <input type="file" name="surat_penunjukan" accept="application/pdf"
          class="w-full rounded-lg p-2 bg-white/5 border border-white/20 text-white">
      </div>



      {{-- ========================================= --}}
      {{-- =========   FORM JASA   ================ --}}
      {{-- ========================================= --}}
      <div class="md:col-span-2 jasa-section hidden">
        <label class="text-sm text-blue-200">Jenis Jasa</label>
        <select name="jenis_jasa"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
          <option value="" disabled selected>- pilih -</option>
          <option value="konstruksi"
            {{ old('jenis_jasa', $product->jenis_jasa ?? '')=='konstruksi' ? 'selected':'' }}>
            Jasa Konstruksi
          </option>
          <option value="non_konstruksi"
            {{ old('jenis_jasa', $product->jenis_jasa ?? '')=='non_konstruksi' ? 'selected':'' }}>
            Jasa Non Konstruksi
          </option>
          <option value="konsultansi"
            {{ old('jenis_jasa', $product->jenis_jasa ?? '')=='konsultansi' ? 'selected':'' }}>
            Jasa Konsultansi
          </option>
        </select>
      </div>



      {{-- ================================================= --}}
      {{-- =========   FORM PRODUK DIGITAL   ============== --}}
      {{-- ================================================= --}}
      <div class="md:col-span-2 digital-section hidden">
        <label class="text-sm text-blue-200">Jenis Produk Digital</label>
        <select name="jenis_digital"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
          <option value="" disabled selected>- pilih -</option>
          <option value="software_lisensi"
            {{ old('jenis_digital', $product->jenis_digital ?? '')=='software_lisensi' ? 'selected':'' }}>
            Software Berlisensi
          </option>
          <option value="software_open_source"
            {{ old('jenis_digital', $product->jenis_digital ?? '')=='software_open_source' ? 'selected':'' }}>
            Software Open Source
          </option>
          <option value="cloud_service"
            {{ old('jenis_digital', $product->jenis_digital ?? '')=='cloud_service' ? 'selected':'' }}>
            Cloud Service
          </option>
          <option value="it_service"
            {{ old('jenis_digital', $product->jenis_digital ?? '')=='it_service' ? 'selected':'' }}>
            IT Managed Service
          </option>
        </select>
      </div>



      {{-- ===================== --}}
      {{-- Harga --}}
      {{-- ===================== --}}
      <div>
        <label class="text-sm text-blue-200">Harga</label>
        <input type="number" name="price" step="0.01"
          value="{{ old('price', $product->price ?? '') }}" required
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>


      {{-- ===================== --}}
      {{-- Satuan --}}
      {{-- ===================== --}}
      <div>
        <label class="text-sm text-blue-200">Satuan</label>
        <input name="unit" 
          value="{{ old('unit', $product->unit ?? '') }}"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>


      {{-- ===================== --}}
      {{-- Deskripsi --}}
      {{-- ===================== --}}
      <div class="md:col-span-2">
        <label class="text-sm text-blue-200">Deskripsi</label>
        <textarea 
          name="description" rows="4"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">{{ old('description', $product->description ?? '') }}</textarea>
      </div>


      {{-- ===================== --}}
      {{-- Stok --}}
      {{-- ===================== --}}
      <div>
        <label class="text-sm text-blue-200">Stok</label>
        <input type="number" name="stock"
          value="{{ old('stock', $product->stock ?? '') }}"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>


      {{-- ===================== --}}
      {{-- TKDN --}}
      {{-- ===================== --}}
      <div>
        <label class="text-sm text-blue-200">TKDN (%)</label>
        <input type="number" min="0" max="100" name="tkdn"
          value="{{ old('tkdn', $product->tkdn ?? '') }}"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>


      {{-- ===================== --}}
      {{-- Izin Edar --}}
      {{-- ===================== --}}
      <div>
        <label class="text-sm text-blue-200">Izin Edar</label>
        <input name="izin_edar"
          value="{{ old('izin_edar', $product->izin_edar ?? '') }}"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>


      {{-- ===================== --}}
      {{-- Lead Time --}}
      {{-- ===================== --}}
      <div>
        <label class="text-sm text-blue-200">Lead Time (hari)</label>
        <input type="number" name="lead_time_days"
          value="{{ old('lead_time_days', $product->lead_time_days ?? '') }}"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>


      {{-- FOTO PRODUK --}}
      <div class="md:col-span-2">
        <label class="text-sm text-blue-200">Foto Produk (boleh banyak)</label>
        <input type="file" name="photos[]" multiple accept="image/*"
          class="w-full rounded-lg p-2 bg-white/5 border border-white/20 text-white">

        @if(isset($product) && !empty($product->photos))
        <div class="mt-2 flex gap-2 overflow-x-auto">
          @foreach($product->photos as $ph)
          <img src="{{ asset('storage/'.$ph) }}" class="w-20 h-20 object-cover rounded-lg" />
          @endforeach
        </div>
        @endif
      </div>


      {{-- Brosur --}}
      <div class="md:col-span-2">
        <label class="text-sm text-blue-200">Brosur PDF (opsional)</label>
        <input type="file" name="brochure" accept="application/pdf"
          class="w-full rounded-lg p-2 bg-white/5 border border-white/20 text-white">
      </div>


      {{-- TOMBOL --}}
      <div class="md:col-span-2 flex gap-2 mt-3">
        <button class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 py-3 rounded-xl font-semibold">
          Simpan
        </button>

        <a href="{{ route('vendor.produk') }}"
          class="bg-white/10 border border-white/20 px-4 py-3 rounded-xl text-center">
          Batal
        </a>
      </div>

    </form>
  </div>
</div>


{{-- SCRIPT --}}
<script>
function toggleTipeProduk() {
    const tipe = document.getElementById('tipe_produk').value;

    document.querySelectorAll('.barang-section').forEach(e => e.classList.add('hidden'));
    document.querySelectorAll('.jasa-section').forEach(e => e.classList.add('hidden'));
    document.querySelectorAll('.digital-section').forEach(e => e.classList.add('hidden'));

    if (tipe === "barang") document.querySelectorAll('.barang-section').forEach(e => e.classList.remove('hidden'));
    if (tipe === "jasa") document.querySelectorAll('.jasa-section').forEach(e => e.classList.remove('hidden'));
    if (tipe === "digital") document.querySelectorAll('.digital-section').forEach(e => e.classList.remove('hidden'));
}

function toggleKategoriBarang() {
    const val = document.getElementById('kategori_barang')?.value;

    document.querySelectorAll(".kategori-obat").forEach(e => e.classList.add("hidden"));
    document.querySelectorAll(".kategori-alkes").forEach(e => e.classList.add("hidden"));
    document.querySelectorAll(".kategori-umum").forEach(e => e.classList.add("hidden"));

    if (val === "obat") document.querySelectorAll(".kategori-obat").forEach(e => e.classList.remove("hidden"));
    if (val === "alkes") document.querySelectorAll(".kategori-alkes").forEach(e => e.classList.remove("hidden"));
    if (val === "umum") document.querySelectorAll(".kategori-umum").forEach(e => e.classList.remove("hidden"));
}

// initial run
toggleTipeProduk();
toggleKategoriBarang();

document.getElementById('tipe_produk').addEventListener('change', toggleTipeProduk);
document.getElementById('kategori_barang').addEventListener('change', toggleKategoriBarang);
</script>

@endsection
