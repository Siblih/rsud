@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

    {{-- 🏷️ Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold flex items-center gap-2">
            <i data-lucide="package" class="w-5 h-5 text-blue-300"></i>
            <span>Manajemen Pengadaan</span>
        </h1>
    </div>

    {{-- 🔹 Tab Navigasi --}}
    @php
        $tabs = ['paket' => 'Paket', 'kontrak' => 'Kontrak', 'po' => 'Purchase Order', 'BAST' => 'BAST', 'pembayaran' => 'Pembayaran'];
        $activeTab = request()->get('tab', 'paket');
    @endphp

    <div class="flex justify-between bg-white/10 backdrop-blur-md rounded-xl overflow-hidden shadow mb-6">
        @foreach ($tabs as $key => $label)
            <a href="{{ route('admin.pengadaan', ['tab' => $key]) }}"
               class="flex-1 text-center py-2 text-sm font-medium transition-all duration-200
                      {{ $activeTab === $key 
                          ? 'bg-white text-[#1A1F4A] font-semibold shadow-inner' 
                          : 'text-white/80 hover:bg-white/20' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- 🔸 Konten Tiap Tab --}}
    <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 shadow border border-white/20">

     @if ($activeTab === 'paket')

{{-- ================= FILTER ================= --}}
<form method="GET" class="flex flex-wrap gap-3 mb-5">
    <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Nama pengadaan" class="p-2 rounded text-black">
    <input type="text" name="unit" value="{{ request('unit') }}" placeholder="Unit" class="p-2 rounded text-black">

    <select name="status" onchange="this.form.submit()" class="p-2 rounded text-black">
        <option value="">Semua Status</option>
        <option value="menunggu" {{ request('status')=='menunggu' ? 'selected' : '' }}>Menunggu</option>
        <option value="disetujui" {{ request('status')=='disetujui' ? 'selected' : '' }}>Disetujui</option>
        <option value="ditolak" {{ request('status')=='ditolak' ? 'selected' : '' }}>Ditolak</option>
    </select>

    <button class="px-4 py-2 bg-blue-600 rounded">Filter</button>
</form>

{{-- ================= TABLE ================= --}}
<div class="overflow-x-auto">
<table class="w-full text-sm border-collapse">
<thead class="bg-white/20">
<tr>
    <th>#</th>
    <th>Nama</th>
    <th>Unit</th>
    <th class="text-right">Anggaran</th>
    <th class="text-center">Status</th>
    <th class="text-center">Aksi</th>
</tr>
</thead>

<tbody>
@forelse ($pengadaans as $i => $p)
<tr class="hover:bg-white/10">

<td class="text-center">{{ $i + 1 }}</td>
<td>{{ $p->nama_pengadaan }}</td>
<td>{{ $p->unit->name ?? '-' }}</td>
<td class="text-right">{{ number_format($p->estimasi_anggaran,0,',','.') }}</td>

{{-- STATUS --}}
<td class="text-center">
    @if ($p->status === 'menunggu')
        <span class="text-yellow-300">Menunggu</span>
    @elseif ($p->status === 'disetujui')
        <span class="text-green-300">Disetujui</span>
    @else
        <span class="text-red-300">Ditolak</span>
    @endif
</td>

{{-- AKSI --}}
<td class="text-center">
<div class="flex flex-wrap gap-1 justify-center">

{{-- DETAIL (SELALU ADA) --}}
<a href="{{ route('admin.pengadaan.show',$p->id) }}"
   class="px-2 py-1 bg-blue-500/20 rounded text-xs">
   Detail
</a>

{{-- SETUJUI / TOLAK --}}
@if ($p->status === 'menunggu')

    {{-- SETUJUI (BUKA PILIHAN METODE) --}}
    <button
        onclick="openMetodeModal({{ $p->id }})"
        class="px-2 py-1 bg-green-500/20 rounded text-xs">
        Setujui
    </button>

    {{-- TOLAK --}}
    <form method="POST"
          action="{{ route('admin.pengadaan.updateStatus',$p->id) }}"
          class="inline-block">
        @csrf
        <input type="hidden" name="status" value="ditolak">
        <button class="px-2 py-1 bg-red-500/20 rounded text-xs">
            Tolak
        </button>
    </form>

@endif
{{-- MODAL PILIH METODE --}}
<div id="metodeModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

    <div class="bg-[#1A1F4A] rounded-xl p-6 w-96 text-white shadow-lg">
        <h2 class="text-lg font-semibold mb-4 text-center">
            Pilih Metode Pengadaan
        </h2>

        <div class="flex gap-4 justify-center">

            {{-- KOMPETISI --}}
            <form method="POST" id="formKompetisi">
                @csrf
                <input type="hidden" name="metode_pengadaan" value="kompetisi">
                <button
                    class="px-4 py-2 bg-purple-600/80 rounded w-full">
                    Kompetisi (Tender)
                </button>
            </form>

            {{-- LANGSUNG --}}
            <form method="POST" id="formLangsung">
                @csrf
                <input type="hidden" name="metode_pengadaan" value="langsung">
                <button
                    class="px-4 py-2 bg-orange-600/80 rounded w-full">
                    Langsung
                </button>
            </form>

        </div>

        <button onclick="closeMetodeModal()"
                class="mt-4 w-full text-sm text-white/70 hover:underline">
            Batal
        </button>
    </div>
</div>


{{-- METODE PENGADAAN --}}
@if ($p->status === 'disetujui')

    {{-- BELUM PILIH METODE --}}
    @if (is_null($p->metode_pengadaan))
        {{-- kode kamu ASLI, tidak diubah --}}
        ...
    
    {{-- KOMPETISI --}}
    @elseif ($p->metode_pengadaan === 'kompetisi')

        {{-- JIKA SUDAH SELESAI --}}
        @if ($p->proses === 'selesai')
            <span class="px-2 py-1 bg-green-500/20 text-green-300 rounded text-xs">
                ✔ Vendor Ditetapkan
            </span>
            <a href="{{ route('admin.penawaran.show',$p->id) }}"
               class="px-2 py-1 bg-purple-500/20 rounded text-xs">
               Lihat Hasil Tender
            </a>
        @else
            <a href="{{ route('admin.penawaran.show',$p->id) }}"
               class="px-2 py-1 bg-purple-500/20 rounded text-xs">
               Kelola Tender
            </a>
        @endif

    {{-- LANGSUNG --}}
@else

    {{-- JIKA PRODUK BELUM DIPILIH --}}
    @if (is_null($p->katalog_product_id))
       <a href="{{ route('admin.katalog', ['pengadaan' => $p->id]) }}"
   class="px-2 py-1 bg-orange-500/20 rounded text-xs">
   Pilih Produk
</a>


    {{-- JIKA PRODUK SUDAH DIPILIH --}}
    @else
        <span class="px-2 py-1 bg-green-500/20 text-green-300 rounded text-xs">
            ✔ Produk Dipilih
        </span>

        <a href="{{ route('admin.penawaran.show',$p->id) }}"
           class="px-2 py-1 bg-purple-500/20 rounded text-xs">
           Lihat Penawaran
        </a>
    @endif

@endif
@endif

</div>
</td>

</tr>
@empty
<tr>
<td colspan="6" class="text-center py-4 text-white/50">
    Belum ada data
</td>
</tr>
@endforelse
</tbody>
</table>
</div>




    @elseif ($activeTab === 'kontrak')
{{-- TAB KONTRAK --}}
<h2 class="font-semibold mb-3 flex items-center gap-2 text-blue-200">
    <i data-lucide="file-signature" class="w-5 h-5"></i> Daftar Kontrak
</h2>

{{-- FILTER --}}
<form method="GET" class="flex flex-wrap gap-3 mb-5">
    <input type="hidden" name="tab" value="kontrak">

    {{-- NOMOR KONTRAK --}}
    <input 
        type="text" 
        name="nomor_kontrak" 
        placeholder="Cari Nomor Kontrak"
        value="{{ request('nomor_kontrak') }}"
        class="p-2 rounded text-black"
    >

    {{-- NAMA PENGADAAN --}}
    <input 
        type="text" 
        name="pengadaan_nama" 
        placeholder="Cari Pengadaan"
        value="{{ request('pengadaan_nama') }}"
        class="p-2 rounded text-black"
    >

    {{-- NAMA VENDOR --}}
    <input 
        type="text" 
        name="vendor_nama" 
        placeholder="Cari Vendor"
        value="{{ request('vendor_nama') }}"
        class="p-2 rounded text-black"
    >

    <button class="px-4 py-2 bg-blue-600 rounded" type="submit">
        Filter
    </button>
</form>

{{-- TABEL KONTRAK --}}
<div class="overflow-x-auto">
<table class="w-full text-sm text-white/90 border-collapse">
    <thead class="bg-white/20">
        <tr>
            <th class="p-2 border border-white/10">#</th>
            <th class="p-2 border border-white/10 text-left">Nomor Kontrak</th>
            <th class="p-2 border border-white/10 text-left">Pengadaan</th>
            <th class="p-2 border border-white/10 text-left">Vendor</th>
            <th class="p-2 border border-white/10 text-center">Status</th>
            <th class="p-2 border border-white/10 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($kontraks as $i => $k)
        <tr class="hover:bg-white/10 transition">
            <td class="border border-white/10 p-2 text-center">{{ $i + 1 }}</td>

            <td class="border border-white/10 p-2">
                {{ $k->nomor_kontrak }}
            </td>

            <td class="border border-white/10 p-2">
                {{ $k->pengadaan->nama_pengadaan ?? '-' }}
            </td>

            <td class="border border-white/10 p-2">
                {{ $k->vendor->vendorProfile->company_name ?? '-' }}
            </td>

            {{-- GABUNGAN LOGIKA STATUS PUNYAMU + PUNYAKU --}}
            <td class="border border-white/10 p-2 text-center">

                {{-- JIKA PROSES PENGADAAN SUDAH SELESAI --}}
                @if ($k->pengadaan?->proses === 'selesai')
                    <span class="px-2 py-1 bg-green-600/30 text-green-300 rounded text-xs">
                        ✔ Selesai
                    </span>

                {{-- LOGIKA STATUS LAMA (MENUNGGU / DISETUJUI / DITOLAK) --}}
                @elseif ($k->pengadaan?->status === 'menunggu')
                    <span class="text-yellow-300 text-xs">Menunggu</span>

                @elseif ($k->pengadaan?->status === 'disetujui')
                    <span class="text-green-300 text-xs">Disetujui</span>

                @elseif ($k->pengadaan?->status === 'ditolak')
                    <span class="text-red-300 text-xs">Ditolak</span>

                {{-- FALLBACK STATUS KONTRAK --}}
                @else
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($k->status === 'aktif')
                            bg-green-500/30 text-green-200
                        @else
                            bg-gray-500/30 text-gray-200
                        @endif">
                        {{ ucfirst($k->status) }}
                    </span>
                @endif

            </td>

            <td class="border border-white/10 p-2 text-center">
                <a href="{{ route('admin.kontrak.show', $k->id) }}" 
                   class="text-blue-300 hover:underline">
                    Detail
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center text-white/60 py-4">
                Belum ada kontrak.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>

        @elseif ($activeTab === 'po')
{{-- TAB PURCHASE ORDER --}}

<h2 class="font-semibold mb-3 flex items-center gap-2 text-blue-200">
    <i data-lucide="file-plus" class="w-5 h-5"></i> Daftar Purchase Order
</h2>

{{-- FILTER --}}
<form method="GET" class="flex flex-wrap gap-3 mb-5">
    <input type="hidden" name="tab" value="po">

    {{-- NOMOR PO --}}
    <input 
        type="text" 
        name="nomor_po" 
        placeholder="Cari Nomor PO"
        value="{{ request('nomor_po') }}"
        class="p-2 rounded text-black"
    >

    {{-- NOMOR KONTRAK --}}
    <input 
        type="text" 
        name="nomor_kontrak" 
        placeholder="Cari Nomor Kontrak"
        value="{{ request('nomor_kontrak') }}"
        class="p-2 rounded text-black"
    >

    {{-- NAMA VENDOR --}}
    <input 
        type="text" 
        name="vendor_nama" 
        placeholder="Cari Vendor"
        value="{{ request('vendor_nama') }}"
        class="p-2 rounded text-black"
    >

    <button class="px-4 py-2 bg-blue-600 rounded" type="submit">
        Filter
    </button>
</form>


{{-- TABEL PO --}}
<div class="overflow-x-auto">
    <table class="w-full text-sm text-white/90 border-collapse">
        <thead class="bg-white/20">
            <tr>
                <th class="p-2 border border-white/10">#</th>
                <th class="p-2 border border-white/10 text-left">Nomor PO</th>
                <th class="p-2 border border-white/10 text-left">Kontrak</th>
                <th class="p-2 border border-white/10 text-left">Vendor</th>
                <th class="p-2 border border-white/10 text-center">Status</th>
                <th class="p-2 border border-white/10 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($poList as $i => $po)
                <tr class="hover:bg-white/10 transition">
                    <td class="p-2 border border-white/10 text-center">{{ $i+1 }}</td>
                    <td class="p-2 border border-white/10">{{ $po->nomor_po }}</td>
                    <td class="p-2 border border-white/10">
                        {{ $po->kontrak->nomor_kontrak ?? '-' }}
                    </td>
                    <td class="p-2 border border-white/10">{{ $po->vendor->name ?? '-' }}</td>

                    <td class="p-2 border border-white/10 text-center">
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($po->status == 'selesai') bg-green-500/30 text-green-200
                            @elseif($po->status == 'dikirim') bg-yellow-500/30 text-yellow-200
                            @else bg-gray-500/30 text-gray-200 @endif">
                            {{ ucfirst($po->status) }}
                        </span>
                    </td>

                    <td class="p-2 border border-white/10 text-center">
                        <a href="{{ route('admin.po.show', $po->id) }}" class="text-blue-300 hover:underline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-4 text-center text-white/50">
                        Belum ada PO.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@elseif ($activeTab === 'BAST')
{{-- TAB BAST --}}

<h2 class="font-semibold mb-3 flex items-center gap-2 text-blue-200">
    <i data-lucide="package-check" class="w-5 h-5"></i>
    Daftar BAST
</h2>

<div class="overflow-x-auto">
<table class="w-full text-sm text-white/90 border-collapse">
    <thead class="bg-white/20">
        <tr>
            <th class="p-2 border border-white/10">#</th>
            <th class="p-2 border border-white/10 text-left">Nama Pengadaan</th>
            <th class="p-2 border border-white/10 text-left">Kode / PO</th>
            <th class="p-2 border border-white/10 text-left">Vendor</th>
            <th class="p-2 border border-white/10 text-left">Nilai Kontrak</th>
            <th class="p-2 border border-white/10 text-center">Status</th>
            <th class="p-2 border border-white/10 text-center">Aksi</th>
        </tr>
    </thead>

    <tbody>
    @forelse ($bastList as $i => $pengadaan)
    @php
    // 🔥 Ambil kontrak pertama dari pengadaan
    $kontrak = $pengadaan->kontraks->first();

    // 🔥 Ambil PO dari kontrak
    $po = $kontrak?->purchaseOrders?->first();

    // 🔥 Penawaran MENANG
    $penawaran = $pengadaan->penawarans->first();

    // 🔥 Vendor pemenang
    $vendor = $penawaran?->vendor;

    // 🔥 Status BAST (virtual)
    $statusBast = $kontrak?->status_bast ?? 'disetujui';

        $pembayaran = $po?->pembayaran;

@endphp


    <tr class="hover:bg-white/10 transition">
        <td class="p-2 border border-white/10 text-center">
            {{ $i + 1 }}
        </td>

        {{-- NAMA PENGADAAN --}}
        <td class="p-2 border border-white/10 font-semibold">
            {{ $pengadaan->nama_pengadaan ?? '-' }}
        </td>

        {{-- KODE / PO --}}
        @php
    // 🔥 Ambil PO pertama dari kontrak
    $po = $pengadaan->kontraks
            ->first()
            ?->purchaseOrders
            ?->first();
@endphp

<td class="p-2 border border-white/10 text-xs text-blue-200">
    {{ $po?->nomor_po ?? '-' }}
</td>


        {{-- VENDOR --}}
        <td class="p-2 border border-white/10">
            {{ $vendor?->vendorProfile?->company_name ?? '-' }}
        </td>

        {{-- NILAI KONTRAK --}}
        <td class="p-2 border border-white/10">
            Rp {{ number_format($penawaran->harga ?? 0, 0, ',', '.') }}
        </td>

        {{-- STATUS (MENIRU VENDOR) --}}
        <td class="p-2 border border-white/10 text-center">
            <span class="px-2 py-1 rounded-full text-xs font-semibold
                @if($statusBast === 'disetujui')
                    bg-green-400/20 text-green-300
                @else
                    bg-yellow-400/20 text-yellow-300
                @endif">
                {{ ucfirst($statusBast) }}
            </span>
        </td>

        {{-- AKSI --}}
      <td class="p-2 border border-white/10 text-center space-y-1">

    {{-- 🔥 TOMBOL LIHAT BAST (WAJIB ADA) --}}
    @if ($kontrak)
        <a href="{{ route('admin.bast.show', $kontrak->id) }}"
           class="block px-3 py-1 bg-blue-600/30 rounded text-xs hover:bg-blue-600/50">
            Lihat BAST
        </a>
        

    @endif

    {{-- 🔥 STATUS PEMBAYARAN --}}
    @if ($pembayaran && $pembayaran->bukti_bayar)
        {{-- ✅ SUDAH UPLOAD --}}
        <span class="block text-xs text-green-300 mt-1 font-semibold">
            Sudah Dibayar
        </span>

        <a href="{{ asset('storage/'.$pembayaran->bukti_bayar) }}"
           target="_blank"
           class="block px-3 py-1 bg-emerald-600/30 rounded text-xs hover:bg-emerald-600/50">
            Lihat Bukti
        </a>
    @else
       @if ($po)
    <form action="{{ route('admin.pembayaran.upload', $po->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="flex flex-col gap-1 mt-1">
        @csrf

        <input type="file"
               name="bukti_bayar"
               required
               class="text-xs text-white">

        <button type="submit"
                class="px-3 py-1 bg-green-600/30 rounded text-xs hover:bg-green-600/50">
            Upload Bukti
        </button>
    </form>
@else
    <span class="text-xs text-red-400 italic">
        PO belum dibuat
    </span>
@endif

    @endif

</td>




    </tr>

@empty
    <tr>
        <td colspan="7" class="text-center py-4 text-white/60">
            Belum ada BAST.
        </td>
    </tr>
@endforelse

    </tbody>
</table>
</div>

@elseif ($activeTab === 'pembayaran')
{{-- TAB PEMBAYARAN --}}
<h2 class="font-semibold mb-3 flex items-center gap-2 text-blue-200">
    <i data-lucide="wallet" class="w-5 h-5"></i>
    Daftar Pembayaran
</h2>

<div class="overflow-x-auto">
<table class="w-full text-sm text-white/90 border-collapse">
    <thead class="bg-white/20">
        <tr>
            <th class="p-2 border border-white/10">#</th>
            <th class="p-2 border border-white/10 text-left">Pengadaan</th>
            <th class="p-2 border border-white/10 text-left">Vendor</th>
            <th class="p-2 border border-white/10 text-left">No Kontrak</th>
            <th class="p-2 border border-white/10 text-left">No PO</th>
            <th class="p-2 border border-white/10 text-right">Nilai</th>
            <th class="p-2 border border-white/10 text-center">Status Pembayaran</th>
            <th class="p-2 border border-white/10 text-center">Aksi</th>
        </tr>
    </thead>

    <tbody>
    @forelse ($pembayaranList as $i => $po)

       @php
    $kontrak    = $po->kontrak ?? null;
    $pengadaan  = $kontrak?->pengadaan;
    $pembayaran = $po->pembayaran;

    $penawaran = $pengadaan?->penawarans
        ?->where('status', 'menang')
        ->first();

    $vendor = $penawaran?->vendor;
@endphp



        <tr class="hover:bg-white/10 transition">
            <td class="p-2 border border-white/10 text-center">{{ $i + 1 }}</td>

            <td class="p-2 border border-white/10">
                {{ $pengadaan?->nama_pengadaan ?? '-' }}

            </td>

            <td class="p-2 border border-white/10">
               {{ $vendor?->vendorProfile?->company_name ?? '-' }}

            </td>

            <td class="p-2 border border-white/10 text-xs">
               {{ $kontrak?->nomor_kontrak ?? '-' }}

            </td>

            <td class="p-2 border border-white/10 text-xs">
               {{ $po->nomor_po }}

            </td>

            <td class="p-2 border border-white/10 text-right">
               Rp {{ number_format($penawaran?->harga ?? 0, 0, ',', '.') }}

            </td>

            {{-- STATUS PEMBAYARAN --}}
            <td class="p-2 border border-white/10 text-center">
    @if ($pembayaran && $pembayaran->status === 'lunas')
        <span class="px-2 py-1 bg-green-500/20 text-green-300 rounded text-xs">
            Dibayar
        </span>
    @elseif ($pembayaran)
        <span class="px-2 py-1 bg-yellow-400/20 text-yellow-300 rounded text-xs">
            Diproses
        </span>
    @else
        <span class="px-2 py-1 bg-gray-500/20 text-gray-300 rounded text-xs">
            Belum Dibayar
        </span>
    @endif
</td>


            {{-- AKSI --}}
           <td class="p-2 border border-white/10 text-center">
    @if ($pembayaran && $pembayaran->status === 'lunas')
        <span class="text-green-300 text-xs">✔ Selesai</span>
    @else
    <span class="px-2 py-1 bg-blue-600/30 rounded-full text-xs text-white/80">
        Proses
    </span>
@endif

</td>

        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center py-4 text-white/60">
                Belum ada data pembayaran.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>

        
        @endif
    </div>
</div>

<script>
    lucide.createIcons();
    function openMetodeModal(pengadaanId) {
        document.getElementById('metodeModal').classList.remove('hidden');

        document.getElementById('formKompetisi')
            .action = `/admin/pengadaan/${pengadaanId}/metode`;

        document.getElementById('formLangsung')
            .action = `/admin/pengadaan/${pengadaanId}/metode`;
    }

    function closeMetodeModal() {
        document.getElementById('metodeModal').classList.add('hidden');
    }

</script>
@endsection
