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
        $tabs = ['paket' => 'Paket', 'kontrak' => 'Kontrak', 'po' => 'Purchase Order', 'pembayaran' => 'Pembayaran'];
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
            {{-- TAB PAKET --}}
            <form method="GET" class="flex flex-wrap gap-3 mb-5">

    {{-- Filter Nama Pengadaan --}}
    <input type="text" 
           name="nama" 
           value="{{ request('nama') }}"
           placeholder="Cari nama pengadaan..."
           class="p-2 rounded text-black">

    {{-- Filter Unit --}}
    <input type="text" 
           name="unit" 
           value="{{ request('unit') }}"
           placeholder="Cari unit..."
           class="p-2 rounded text-black">

    {{-- Filter Status --}}
    <select name="status" 
            onchange="this.form.submit()" 
            class="p-2 rounded text-black">
        <option value="">Semua Status</option>
        <option value="menunggu" {{ request('status')=='menunggu' ? 'selected' : '' }}>menunggu</option>
        <option value="disetujui" {{ request('status')=='disetujui' ? 'selected' : '' }}>Disetujui</option>
        <option value="ditolak" {{ request('status')=='ditolak' ? 'selected' : '' }}>Ditolak</option>
    </select>

    <button class="px-4 py-2 bg-blue-600 rounded" type="submit">
        Filter
    </button>
</form>


            <h2 class="font-semibold mb-3 flex items-center gap-2 text-blue-200">
                <i data-lucide="file-box" class="w-5 h-5"></i> Daftar Paket Pengadaan
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-white/90 border-collapse">
                    <thead class="bg-white/20">
                        <tr>
                            <th class="p-2 border border-white/10">#</th>
                            <th class="p-2 border border-white/10 text-left">Nama Pengadaan</th>
                            <th class="p-2 border border-white/10 text-left">Unit</th>
                            <th class="p-2 border border-white/10 text-right">Estimasi Anggaran (Rp)</th>
                            <th class="p-2 border border-white/10 text-center">Status</th>
                            <th class="p-2 border border-white/10 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengadaans as $i => $p)
                            <tr class="hover:bg-white/10 transition">
                                <td class="border border-white/10 p-2 text-center">{{ $i + 1 }}</td>
                                <td class="border border-white/10 p-2">{{ $p->nama_pengadaan ?? '-' }}</td>
                                <td class="border border-white/10 p-2">{{ $p->unit->name ?? '-' }}</td>
                                <td class="border border-white/10 p-2 text-right">{{ number_format($p->estimasi_anggaran ?? 0, 0, ',', '.') }}</td>
                                <td class="border border-white/10 p-2 text-center">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($p->status == 'disetujui') bg-green-500/30 text-green-200
                                        @elseif($p->status == 'ditolak') bg-red-500/30 text-red-200
                                        @else bg-yellow-500/30 text-yellow-200 @endif">
                                        {{ ucfirst($p->status ?? 'menunggu') }}
                                    </span>
                                </td>
                                <td class="border border-white/10 p-2 text-center">
                                    <a href="{{ route('admin.pengadaan.show', $p->id) }}" 
                                       class="text-blue-300 hover:underline">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-white/60 py-4">Belum ada data pengadaan.</td>
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

    {{-- STATUS --}}
    <select name="status" class="p-2 rounded text-black">
        <option value="">Semua Status</option>
        <option value="aktif" {{ request('status')=='aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="selesai" {{ request('status')=='selesai' ? 'selected' : '' }}>Selesai</option>
    </select>

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
        value="{{ request('pengadaan') }}"
        class="p-2 rounded text-black"
    >

    {{-- NAMA VENDOR --}}
    <input 
        type="text" 
        name="vendor_nama" 
        placeholder="Cari Vendor"
        value="{{ request('vendor') }}"
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
                    <td class="border border-white/10 p-2">{{ $k->nomor_kontrak }}</td>
                    <td class="border border-white/10 p-2">{{ $k->pengadaan->nama_pengadaan }}</td>
                    <td class="border border-white/10 p-2">
                      {{ $k->vendor->name ?? '-' }}
                    </td>
                    <td class="border border-white/10 p-2 text-center">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($k->status == 'aktif') bg-green-500/30 text-green-200
                            @else bg-gray-500/30 text-gray-200 @endif">
                            {{ ucfirst($k->status) }}
                        </span>
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


        @elseif ($activeTab === 'pembayaran')
            {{-- TAB PEMBAYARAN --}}
            <h2 class="font-semibold mb-3 flex items-center gap-2 text-blue-200">
                <i data-lucide="wallet" class="w-5 h-5"></i> Pembayaran Vendor
            </h2>
            <p class="text-white/80 text-sm leading-relaxed">
                Menampilkan daftar status pembayaran vendor yang telah menyelesaikan BAST.
                Admin dapat memverifikasi bukti transfer dan mengelola rekap pembayaran.
            </p>
        @endif
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
