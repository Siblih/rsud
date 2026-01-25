@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white p-6">

    <div class="max-w-4xl mx-auto bg-white/10 p-8 rounded-2xl shadow-lg backdrop-blur-sm border border-white/10">

        {{-- HEADER --}}
        <div class="flex items-center gap-3 mb-6">
            <i data-lucide="file-text" class="w-8 h-8 text-blue-300"></i>
            <h2 class="text-3xl font-bold">Buat Kontrak Baru</h2>
        </div>

        <form action="{{ route('admin.kontrak.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <input type="hidden" name="pengadaan_id" value="{{ $pengadaan->id }}">
            <input type="hidden" name="vendor_id" value="{{ $pemenang->vendor_id }}">

            {{-- INFORMASI UTAMA --}}
            <div class="grid md:grid-cols-2 gap-6">

                {{-- Nomor Kontrak --}}
                <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                    <p class="text-gray-300 text-sm">Nomor Kontrak</p>
                    <input type="text" name="nomor_kontrak"
                           value="KONTRAK-{{ date('Ymd') }}-{{ rand(100,999) }}"
                           readonly
                           class="w-full p-2 rounded text-black bg-white/20 border border-white/30">
                </div>

                {{-- Nama Pengadaan --}}
                <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                    <p class="text-gray-300 text-sm">Nama Pengadaan</p>
                    <p class="text-lg font-semibold">{{ $pengadaan->nama_pengadaan }}</p>
                </div>

                {{-- Vendor Pemenang --}}
                <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                    <p class="text-gray-300 text-sm">Vendor Pemenang</p>
                    <p class="text-lg font-semibold">
                        {{ $pemenang->vendor->vendorProfile->company_name }}
                    </p>
                </div>

                {{-- Nilai Kontrak --}}
                <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                    <p class="text-gray-300 text-sm">Nilai Kontrak</p>
                    <input type="number" name="nilai_kontrak"
                           value="{{ $pemenang->harga }}"
                           required
                           class="w-full p-2 rounded text-black bg-white/20 border border-white/30">
                </div>

                {{-- Tanggal Kontrak --}}
                <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                    <p class="text-gray-300 text-sm">Tanggal Kontrak</p>
                    <input type="date" name="tanggal_kontrak"
                           value="{{ date('Y-m-d') }}"
                           required
                           class="w-full p-2 rounded text-black bg-white/20 border border-white/30">
                </div>

                {{-- Status --}}
                <div class="bg-white/10 p-4 rounded-xl border border-white/10">
                    <p class="text-gray-300 text-sm">Status</p>
                    <select name="status"
                            class="w-full p-2 rounded text-black bg-white/20 border border-white/30">
                        <option value="aktif" selected>Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                {{-- Upload File --}}
                <div class="bg-white/10 p-4 rounded-xl border border-white/10 col-span-2">
                    <p class="text-gray-300 text-sm">File Kontrak (opsional)</p>
                    <input type="file" name="file_kontrak" class="w-full text-sm text-black/80">
                </div>

            </div>

            {{-- BUTTON --}}
            <div class="text-right">
                <button type="submit"
                        class="px-5 py-2 bg-green-600 hover:bg-green-700 rounded-lg shadow-md transition">
                    💾 Simpan Kontrak
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
