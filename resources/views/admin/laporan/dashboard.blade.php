@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

    {{-- 📊 Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold flex items-center gap-2">
            <i data-lucide="bar-chart-3" class="w-5 h-5 text-blue-300"></i>
            <span>Laporan & Audit</span>
        </h1>
    </div>

    {{-- 📁 Kontainer --}}
    <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-lg p-5 border border-white/10">
        <p class="text-blue-200 mb-4 text-sm text-center">
            Pilih jenis laporan untuk ditampilkan atau diunduh.
        </p>

        {{-- 📋 Tombol Menu Laporan --}}
        <div class="flex flex-col gap-3">

            {{-- Laporan Tahunan --}}
            <button class="flex items-center justify-between bg-blue-500/20 hover:bg-blue-500/30 border border-blue-400/20 text-blue-100 font-medium py-3 px-4 rounded-xl transition">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-400/30 p-2 rounded-lg">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <span>Laporan Tahunan</span>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 opacity-70"></i>
            </button>

            {{-- Rekap Nilai Kontrak --}}
            <button class="flex items-center justify-between bg-green-500/20 hover:bg-green-500/30 border border-green-400/20 text-green-100 font-medium py-3 px-4 rounded-xl transition">
                <div class="flex items-center gap-3">
                    <div class="bg-green-400/30 p-2 rounded-lg">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                    <span>Rekap Nilai Kontrak</span>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 opacity-70"></i>
            </button>

            {{-- Export PDF/Excel --}}
            <button class="flex items-center justify-between bg-purple-500/20 hover:bg-purple-500/30 border border-purple-400/20 text-purple-100 font-medium py-3 px-4 rounded-xl transition">
                <div class="flex items-center gap-3">
                    <div class="bg-purple-400/30 p-2 rounded-lg">
                        <i data-lucide="file-down" class="w-5 h-5"></i>
                    </div>
                    <span>Export PDF / Excel</span>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 opacity-70"></i>
            </button>
        </div>
    </div>

</div>

<script>
    lucide.createIcons();
</script>
@endsection
