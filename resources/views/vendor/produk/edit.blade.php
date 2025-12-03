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


      {{-- ========================= --}}
      {{-- TIPE PRODUK BARANG/JASA/DIGITAL --}}
      {{-- ========================= --}}
      <div class="md:col-span-2">
        <label class="text-sm text-blue-200">Tipe Produk</label>
        <select 
          name="tipe_produk" id="tipe_produk" required
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
          <option value="barang" {{ old('tipe_produk', $product->tipe_produk ?? '')=='barang' ? 'selected' : '' }}>Barang</option>
          <option value="jasa" {{ old('tipe_produk', $product->tipe_produk ?? '')=='jasa' ? 'selected' : '' }}>Jasa</option>
          <option value="digital" {{ old('tipe_produk', $product->tipe_produk ?? '')=='digital' ? 'selected' : '' }}>Produk Digital</option>
        </select>
      </div>


      {{-- ========================= --}}
      {{-- NAMA PRODUK --}}
      {{-- ========================= --}}
      <div class="md:col-span-2">
        <label class="text-sm text-blue-200">Nama Produk</label>
        <input 
          name="name"
          value="{{ old('name', $product->name ?? '') }}" 
          required
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>



      {{-- ============================================================ --}}
      {{-- ==================   FORM TIPE BARANG   ==================== --}}
      {{-- ============================================================ --}}
      <div class="md:col-span-2 barang-section hidden">

        {{-- Kategori Produk Barang --}}
        <label class="text-sm text-blue-200">Kategori Produk</label>
        <select name="kategori" id="kategori_barang" required
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
          <option value="umum" {{ old('kategori',$product->kategori ?? '')=='umum' ? 'selected':'' }}>Produk Umum</option>
          <option value="obat" {{ old('kategori',$product->kategori ?? '')=='obat' ? 'selected':'' }}>Obat</option>
          <option value="alkes" {{ old('kategori',$product->kategori ?? '')=='alkes' ? 'selected':'' }}>Alat Kesehatan</option>
        </select>

        {{-- Subfilter Barang --}}
        <div class="grid grid-cols-2 gap-2 mt-3">
          <label class="flex items-center gap-2 text-blue-200">
            <input type="checkbox" name="is_dalam_negeri" 
              {{ old('is_dalam_negeri', $product->is_dalam_negeri ?? false) ? 'checked':'' }}>
            Dalam Negeri
          </label>

          <label class="flex items-center gap-2 text-blue-200">
            <input type="checkbox" name="is_umk" 
              {{ old('is_umk', $product->is_umk ?? false) ? 'checked':'' }}>
            UMK
          </label>

          <label class="flex items-center gap-2 text-blue-200">
            <input type="checkbox" name="is_konsolidasi" 
              {{ old('is_konsolidasi', $product->is_konsolidasi ?? false) ? 'checked':'' }}>
            Konsolidasi
          </label>

          <label class="flex items-center gap-2 text-blue-200">
            <input type="checkbox" name="is_tkdn_sertifikat" 
              {{ old('is_tkdn_sertifikat', $product->is_tkdn_sertifikat ?? false) ? 'checked':'' }}>
            Bersertifikat TKDN
          </label>
        </div>

      </div>



      {{-- KATEGORI OBAT --}}
      <div class="md:col-span-2 kategori-obat hidden">
        <label class="text-sm text-blue-200">Nomor Izin Edar (BPOM)</label>
        <input 
          name="izin_bpom"
          value="{{ old('izin_bpom', $product->izin_bpom ?? '') }}"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">

        {{-- Sertifikat CPOB --}}
        <label class="text-sm text-blue-200 mt-2">Sertifikat CPOB (PDF)</label>
        <input type="file" name="sertifikat_cpob" accept="application/pdf"
               class="w-full rounded-lg p-2 bg-white/5 border border-white/20 text-white">

        {{-- Surat Distributor --}}
        <label class="text-sm text-blue-200 mt-2">Surat Distributor</label>
        <input type="file" name="surat_distributor" accept="application/pdf"
               class="w-full rounded-lg p-2 bg-white/5 border border-white/20 text-white">
      </div>



      {{-- KATEGORI ALKES --}}
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



      {{-- KATEGORI UMUM --}}
      <div class="md:col-span-2 kategori-umum hidden">
        <label class="text-sm text-blue-200">Surat Penunjukan Distributor (PDF)</label>
        <input type="file" name="surat_penunjukan" accept="application/pdf"
               class="w-full rounded-lg p-2 bg-white/5 border border-white/20 text-white">
      </div>




      {{-- ========================================= --}}
      {{-- SECTION JASA --}}
      {{-- ========================================= --}}
      <div class="md:col-span-2 jasa-section hidden">
        <label class="text-sm text-blue-200">Jenis Jasa</label>
        <select name="jenis_jasa"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
          <option value="" disabled selected>- pilih -</option>
          <option value="konstruksi" 
            {{ old('jenis_jasa',$product->jenis_jasa ?? '')=='konstruksi' ? 'selected':'' }}>
            Jasa Konstruksi
          </option>
          <option value="non_konstruksi" 
            {{ old('jenis_jasa',$product->jenis_jasa ?? '')=='non_konstruksi' ? 'selected':'' }}>
            Jasa Non Konstruksi
          </option>
          <option value="konsultansi" 
            {{ old('jenis_jasa',$product->jenis_jasa ?? '')=='konsultansi' ? 'selected':'' }}>
            Konsultansi
          </option>
        </select>
      </div>




      {{-- ========================================= --}}
      {{-- SECTION DIGITAL --}}
      {{-- ========================================= --}}
      <div class="md:col-span-2 digital-section hidden">
        <label class="text-sm text-blue-200">Jenis Produk Digital</label>
        <select name="jenis_digital"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
          <option value="" disabled selected>- pilih -</option>
          <option value="software_lisensi">Software Berlisensi</option>
          <option value="software_open_source">Software Open Source</option>
          <option value="cloud_service">Cloud Service</option>
          <option value="it_service">IT Managed Service</option>
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

      {{-- Satuan --}}
      <div>
        <label class="text-sm text-blue-200">Satuan</label>
        <input name="unit" 
               value="{{ old('unit', $product->unit ?? '') }}"
               class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>

      {{-- Deskripsi --}}
      <div class="md:col-span-2">
        <label class="text-sm text-blue-200">Deskripsi</label>
        <textarea 
          name="description" rows="4"
          class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">{{ old('description', $product->description ?? '') }}</textarea>
      </div>

      {{-- Stok --}}
      <div>
        <label class="text-sm text-blue-200">Stok</label>
        <input type="number" name="stock"
               value="{{ old('stock', $product->stock ?? '') }}"
               class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>

      {{-- TKDN --}}
      <div>
        <label class="text-sm text-blue-200">TKDN (%)</label>
        <input type="number" min="0" max="100" name="tkdn"
               value="{{ old('tkdn', $product->tkdn ?? '') }}"
               class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>

      {{-- Izin Edar --}}
      <div>
        <label class="text-sm text-blue-200">Izin Edar</label>
        <input name="izin_edar"
               value="{{ old('izin_edar', $product->izin_edar ?? '') }}"
               class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>

      {{-- Lead Time --}}
      <div>
        <label class="text-sm text-blue-200">Lead Time (hari)</label>
        <input type="number" name="lead_time_days"
               value="{{ old('lead_time_days', $product->lead_time_days ?? '') }}"
               class="w-full rounded-lg p-3 bg-white/5 border border-white/20 text-white">
      </div>

      {{-- Foto Produk --}}
      <div class="md:col-span-2">
        <label class="text-sm text-blue-200">Foto Produk (boleh banyak)</label>
        <input type="file" name="photos[]" multiple accept="image/*"
               class="w-full rounded-lg p-2 bg-white/5 border border-white/20 text-white">
      </div>

      {{-- Brosur --}}
      <div class="md:col-span-2">
        <label class="text-sm text-blue-200">Brosur PDF (opsional)</label>
        <input type="file" name="brochure" accept="application/pdf"
               class="w-full rounded-lg p-2 bg-white/5 border border-white/20 text-white">
      </div>

      {{-- Tombol --}}
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


// initial load
toggleTipeProduk();
toggleKategoriBarang();

document.getElementById('tipe_produk').addEventListener('change', toggleTipeProduk);
document.getElementById('kategori_barang').addEventListener('change', toggleKategoriBarang);

</script>
@endsection
