@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-bold text-blue-700 flex items-center gap-2">
            <i data-lucide="file-text" class="w-5 h-5"></i> Detail Pengadaan
        </h1>
        <a href="{{ route('admin.pengadaan') }}" 
           class="text-sm text-blue-600 hover:underline flex items-center gap-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="text-lg font-semibold mb-3 text-gray-800">{{ $pengadaan->nama_paket ?? 'Tanpa Nama' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <p><strong>Unit:</strong> {{ $pengadaan->unit->nama_unit ?? '-' }}</p>
            <p><strong>Nilai Pengadaan:</strong> Rp {{ number_format($pengadaan->nilai ?? 0, 0, ',', '.') }}</p>
            <p><strong>Status:</strong> {{ $pengadaan->status ?? '-' }}</p>
            <p><strong>Tanggal Dibuat:</strong> {{ $pengadaan->created_at ? $pengadaan->created_at->format('d M Y') : '-' }}</p>
        </div>

        <hr class="my-4">

        <h3 class="font-semibold text-gray-700 mb-2">Deskripsi / Spesifikasi:</h3>
        <p class="text-gray-600 text-sm leading-relaxed mb-4">
            {{ $pengadaan->deskripsi ?? 'Belum ada deskripsi untuk pengadaan ini.' }}
        </p>

        <form action="{{ route('admin.pengadaan.updateStatus', $pengadaan->id) }}" method="POST" class="mt-6">
            @csrf
            <label class="block text-sm font-medium text-gray-700 mb-1">Ubah Status Pengadaan</label>
            <select name="status" class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200 mb-3">
                <option value="Proses" {{ $pengadaan->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                <option value="Kontrak Aktif" {{ $pengadaan->status == 'Kontrak Aktif' ? 'selected' : '' }}>Kontrak Aktif</option>
                <option value="Selesai" {{ $pengadaan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Dibatalkan" {{ $pengadaan->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
