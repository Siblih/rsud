@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white p-6">

    <div class="max-w-5xl mx-auto bg-white/10 p-8 rounded-2xl shadow-lg backdrop-blur-sm border border-white/10">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <i data-lucide="file-text" class="w-8 h-8 text-purple-300"></i>
                <h1 class="text-2xl font-bold">Detail Pengadaan</h1>
            </div>

            <a href="{{ route('admin.pengadaan') }}"
               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-sm shadow">
                ← Kembali
            </a>
        </div>

        {{-- INFO UTAMA --}}
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                <p class="text-gray-300 text-sm">Nama Paket</p>
                <p class="text-lg font-semibold">{{ $pengadaan->nama_pengadaan ?? '-' }}</p>
            </div>

            <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                <p class="text-gray-300 text-sm">Unit</p>
                <p class="text-lg font-semibold">{{ $pengadaan->unit->name ?? '-' }}</p>
            </div>

            <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                <p class="text-gray-300 text-sm">Estimasi Anggaran</p>
                <p class="text-lg font-semibold">
                    Rp {{ number_format($pengadaan->estimasi_anggaran ?? 0, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                <p class="text-gray-300 text-sm">Status</p>
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-semibold
                    @if($pengadaan->status == 'Proses') bg-yellow-500/30 text-yellow-200 @endif
                    @if($pengadaan->status == 'Kontrak Aktif') bg-blue-500/30 text-blue-200 @endif
                    @if($pengadaan->status == 'Selesai') bg-green-500/30 text-green-200 @endif
                    @if($pengadaan->status == 'Dibatalkan') bg-red-500/30 text-red-200 @endif
                ">
                    {{ $pengadaan->status ?? '-' }}
                </span>
            </div>
        </div>

        {{-- DESKRIPSI --}}
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-2 flex items-center gap-2">
                <i data-lucide="align-left" class="w-5 h-5 text-purple-300"></i>
                Deskripsi / Spesifikasi
            </h3>

            <div class="bg-white/5 p-4 rounded-xl border border-white/10 text-gray-200 text-sm leading-relaxed">
                {{ $pengadaan->spesifikasi ?? 'Belum ada deskripsi pengadaan.' }}
            </div>
        </div>

        {{-- PURCHASE ORDER TERKAIT --}}
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-purple-300"></i>
                Purchase Order Terkait
            </h3>

            @if($pengadaan->kontraks->count())
                @foreach($pengadaan->kontraks as $kontrak)
                    @if($kontrak->purchaseOrders->count())
                        <div class="mb-6 bg-white/5 p-4 rounded-xl border border-white/10">
                            <p class="font-semibold text-purple-200 mb-3">
                                Kontrak: {{ $kontrak->nomor_kontrak }}
                            </p>

                            <table class="w-full text-sm border border-white/10 rounded-lg overflow-hidden">
                                <thead class="bg-white/10">
                                    <tr>
                                        <th class="p-2 text-left">Nomor PO</th>
                                        <th class="p-2 text-left">Vendor</th>
                                        <th class="p-2 text-center">Tanggal</th>
                                        <th class="p-2 text-right">Total</th>
                                        <th class="p-2 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kontrak->purchaseOrders as $po)
                                        <tr class="border-t border-white/10 hover:bg-white/10">
                                            <td class="p-2">{{ $po->nomor_po }}</td>
                                            <td class="p-2">{{ $po->vendor->name ?? '-' }}</td>
                                            <td class="p-2 text-center">{{ $po->tanggal_po }}</td>
                                            <td class="p-2 text-right">
                                                Rp {{ number_format($po->total,0,',','.') }}
                                            </td>
                                            <td class="p-2 text-center">
                                                <a href="{{ route('admin.po.show', $po->id) }}"
                                                   class="text-purple-300 hover:underline text-xs">
                                                    Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
            @else
                <p class="text-gray-400 italic">
                    Belum ada kontrak atau PO pada pengadaan ini.
                </p>
            @endif
        </div>

        {{-- UPDATE STATUS --}}
        <div>
            <h3 class="text-xl font-semibold mb-3 flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-5 h-5 text-purple-300"></i>
                Update Status Pengadaan
            </h3>

            <form action="{{ route('admin.pengadaan.updateStatus', $pengadaan->id) }}"
                  method="POST"
                  class="max-w-md">
                @csrf

                <select name="status"
                        class="w-full p-2 rounded-lg bg-white/10 border border-white/20 text-white mb-3">
                    <option value="Proses" {{ $pengadaan->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                    <option value="Kontrak Aktif" {{ $pengadaan->status == 'Kontrak Aktif' ? 'selected' : '' }}>Kontrak Aktif</option>
                    <option value="Selesai" {{ $pengadaan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Dibatalkan" {{ $pengadaan->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                <button type="submit"
                        class="px-5 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg shadow">
                    💾 Simpan Perubahan
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
