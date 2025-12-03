@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

    {{-- ⚙️ Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold flex items-center gap-2">
            <i data-lucide="settings" class="w-5 h-5 text-blue-300"></i>
            <span>Pengaturan Sistem</span>
        </h1>
    </div>

    {{-- 🧩 Daftar Pengaturan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- Role & Hak Akses --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg p-5 flex flex-col items-center text-center hover:scale-[1.02] transition">
            <div class="bg-blue-500/20 p-3 rounded-full mb-3">
                <i data-lucide="shield" class="w-7 h-7 text-blue-300"></i>
            </div>
            <h2 class="font-semibold text-white mb-1">Role & Hak Akses</h2>
            <p class="text-sm text-blue-200">Kelola peran pengguna dan izin akses sistem.</p>
        </div>

        {{-- Integrasi Inaproc --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg p-5 flex flex-col items-center text-center hover:scale-[1.02] transition">
            <div class="bg-green-500/20 p-3 rounded-full mb-3">
                <i data-lucide="network" class="w-7 h-7 text-green-300"></i>
            </div>
            <h2 class="font-semibold text-white mb-1">Integrasi Inaproc v6</h2>
            <p class="text-sm text-blue-200">Sinkronisasi data kontrak & vendor dengan sistem nasional.</p>
        </div>

        {{-- Template Dokumen --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg p-5 flex flex-col items-center text-center hover:scale-[1.02] transition">
            <div class="bg-yellow-500/20 p-3 rounded-full mb-3">
                <i data-lucide="file-cog" class="w-7 h-7 text-yellow-300"></i>
            </div>
            <h2 class="font-semibold text-white mb-1">Template Dokumen</h2>
            <p class="text-sm text-blue-200">Atur SSUK, SSKK, BAHE, dan SPPBJ otomatis.</p>
        </div>

        {{-- Backup & Restore --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg p-5 flex flex-col items-center text-center hover:scale-[1.02] transition">
            <div class="bg-red-500/20 p-3 rounded-full mb-3">
                <i data-lucide="database-backup" class="w-7 h-7 text-red-300"></i>
            </div>
            <h2 class="font-semibold text-white mb-1">Backup & Restore</h2>
            <p class="text-sm text-blue-200">Lakukan pencadangan & pemulihan data sistem.</p>
        </div>

        {{-- Log Aktivitas --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl shadow-lg p-5 flex flex-col items-center text-center hover:scale-[1.02] transition">
            <div class="bg-purple-500/20 p-3 rounded-full mb-3">
                <i data-lucide="file-text" class="w-7 h-7 text-purple-300"></i>
            </div>
            <h2 class="font-semibold text-white mb-1">Log Aktivitas</h2>
            <p class="text-sm text-blue-200">Pantau semua aktivitas pengguna di sistem RSUD.</p>
        </div>
    </div>

</div>

<script>
    lucide.createIcons();
</script>
@endsection
