@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white p-6">

    <div class="max-w-4xl mx-auto bg-white/10 p-8 rounded-2xl shadow-lg backdrop-blur-sm border border-white/10">

        {{-- HEADER --}}
        <div class="flex items-center gap-3 mb-6">
            <i data-lucide="file-text" class="w-8 h-8 text-blue-300"></i>
            <h2 class="text-3xl font-bold">Detail Kontrak</h2>
        </div>

        {{-- INFORMASI UTAMA --}}
        <div class="grid md:grid-cols-2 gap-6 mb-8">

            <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                <p class="text-gray-300 text-sm">Nomor Kontrak</p>
                <p class="text-lg font-semibold">{{ $kontrak->nomor_kontrak }}</p>
            </div>

            <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                <p class="text-gray-300 text-sm">Nama Pengadaan</p>
                <p class="text-lg font-semibold">{{ $kontrak->pengadaan->nama_pengadaan }}</p>
            </div>

            <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                <p class="text-gray-300 text-sm">Vendor</p>
                <p class="text-lg font-semibold">{{ $kontrak->vendor->name }}</p>
            </div>

            <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                <p class="text-gray-300 text-sm">Nilai Kontrak</p>
                <p class="text-lg font-semibold">
                    Rp {{ number_format($kontrak->nilai_kontrak, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                <p class="text-gray-300 text-sm">Tanggal Kontrak</p>
                <p class="text-lg font-semibold">
                    {{ \Carbon\Carbon::parse($kontrak->tanggal_kontrak)->format('d M Y') }}
                </p>
            </div>

            <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                <p class="text-gray-300 text-sm">Status</p>
                <span class="px-3 py-1 mt-1 inline-block rounded-full text-sm
                    @if($kontrak->status == 'aktif')
                        bg-green-500/30 text-green-200
                    @else
                        bg-gray-500/30 text-gray-200
                    @endif">
                    {{ ucfirst($kontrak->status) }}
                </span>
            </div>

        </div>

        <hr class="border-white/20 my-6">

        {{-- DOKUMEN KONTRAK --}}
        <h3 class="text-xl font-semibold mb-3 flex items-center gap-2">
            <i data-lucide="folder-open" class="w-6 h-6 text-yellow-300"></i>
            Dokumen Kontrak
        </h3>

        <div class="space-y-4">

            {{-- File Kontrak --}}
            <div class="bg-white/5 p-4 rounded-xl border border-white/10 flex justify-between items-center">
                <span>File Kontrak</span>
                @if($kontrak->file_kontrak)
                    <a href="{{ asset('storage/' . $kontrak->file_kontrak) }}" 
                       target="_blank"
                       class="text-blue-300 underline hover:text-blue-400">
                        Download
                    </a>
                @else
                    <span class="text-gray-400">Tidak ada</span>
                @endif
            </div>

            {{-- PO Signed --}}
            <div class="bg-white/5 p-4 rounded-xl border border-white/10 flex justify-between items-center">
                <span>PO Signed</span>
                @if($kontrak->po_signed)
                    <a href="{{ asset('storage/'.$kontrak->po_signed) }}" 
                       target="_blank"
                       class="text-blue-300 underline hover:text-blue-400">
                        Download
                    </a>
                @else
                    <span class="text-gray-400">Tidak ada</span>
                @endif
            </div>

            {{-- BAST Signed --}}
            <div class="bg-white/5 p-4 rounded-xl border border-white/10 flex justify-between items-center">
                <span>BAST Signed</span>
                @if($kontrak->bast_signed)
                    <a href="{{ asset('storage/'.$kontrak->bast_signed) }}" 
                       target="_blank"
                       class="text-blue-300 underline hover:text-blue-400">
                        Download
                    </a>
                @else
                    <span class="text-gray-400">Tidak ada</span>
                @endif
            </div>

        </div>
{{-- BUAT PO --}}
<div class="mt-10">
    <a href="{{ route('admin.po.create', $kontrak->id) }}"
       class="px-5 py-3 bg-green-600 hover:bg-green-700 rounded-lg shadow-md transition font-semibold">
        ➕ Buat Purchase Order
    </a>
</div>

        {{-- BUTTON KEMBALI --}}
       <div class="mt-10 text-right">
    <a href="{{ url('/admin/pengadaan?tab=kontrak') }}" 
       class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition">
        ← Kembali 
    </a>
</div>

        

    </div>

</div>
@endsection
