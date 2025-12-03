@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

    {{-- 🧾 Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold flex items-center gap-2">
            <i data-lucide="file-text" class="w-5 h-5 text-blue-300"></i>
            <span>Log Aktivitas Sistem</span>
        </h1>
    </div>

    {{-- 📜 Container --}}
    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/10 shadow-lg">
        <p class="text-blue-200 mb-4 text-sm text-center">Riwayat aktivitas pengguna dalam sistem.</p>

        {{-- 🕓 Timeline Log --}}
        <div class="relative pl-6">
            {{-- Garis vertikal timeline --}}
            <div class="absolute left-2 top-0 bottom-0 w-[2px] bg-gradient-to-b from-blue-400/50 to-purple-500/50"></div>

            {{-- Item Log --}}
            <div class="space-y-4">
                <div class="relative flex items-start gap-3">
                    <div class="absolute -left-[14px] bg-blue-500 w-3 h-3 rounded-full border-2 border-white/40 shadow-md"></div>
                    <div class="flex-1 bg-white/10 rounded-xl p-3 border border-white/10">
                        <p class="text-sm"><span class="text-blue-300 font-semibold">08:22</span> — Admin menyetujui vendor <b>CV Medika Abadi</b></p>
                    </div>
                </div>

                <div class="relative flex items-start gap-3">
                    <div class="absolute -left-[14px] bg-green-500 w-3 h-3 rounded-full border-2 border-white/40 shadow-md"></div>
                    <div class="flex-1 bg-white/10 rounded-xl p-3 border border-white/10">
                        <p class="text-sm"><span class="text-green-300 font-semibold">09:10</span> — Vendor <b>PT Sehat Selalu</b> menambah produk baru</p>
                    </div>
                </div>

                <div class="relative flex items-start gap-3">
                    <div class="absolute -left-[14px] bg-yellow-400 w-3 h-3 rounded-full border-2 border-white/40 shadow-md"></div>
                    <div class="flex-1 bg-white/10 rounded-xl p-3 border border-white/10">
                        <p class="text-sm"><span class="text-yellow-300 font-semibold">10:45</span> — PPK menandatangani kontrak digital <b>#PO-024</b></p>
                    </div>
                </div>

                <div class="relative flex items-start gap-3">
                    <div class="absolute -left-[14px] bg-pink-500 w-3 h-3 rounded-full border-2 border-white/40 shadow-md"></div>
                    <div class="flex-1 bg-white/10 rounded-xl p-3 border border-white/10">
                        <p class="text-sm"><span class="text-pink-300 font-semibold">12:05</span> — Sistem menghasilkan laporan tahunan otomatis</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol bawah --}}
        <div class="mt-6 text-center">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-full shadow transition">
                <i data-lucide="refresh-ccw" class="inline-block w-4 h-4 mr-1"></i> Muat Lebih Banyak
            </button>
        </div>
    </div>

</div>

<script>
    lucide.createIcons();
</script>
@endsection
