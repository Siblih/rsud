@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

    {{-- 🏷️ Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold flex items-center gap-2">
            <i data-lucide="badge-check" class="w-5 h-5 text-blue-300"></i>
            <span>Menu Verifikasi</span>
        </h1>
    </div>

    {{-- 🔹 Tab Navigasi --}}
    @php
        $tabs = [
            'vendor' => 'Verifikasi Vendor',
            'produk' => 'Verifikasi Produk Vendor'
        ];
        $activeTab = request()->get('tab', 'vendor');
    @endphp

    <div class="flex justify-between bg-white/10 backdrop-blur-md rounded-xl overflow-hidden shadow mb-6">
        @foreach ($tabs as $key => $label)
            <a href="{{ route('admin.verifikasi', ['tab' => $key]) }}"
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

        {{-- ========================= --}}
        {{-- TAB VERIFIKASI VENDOR --}}
        {{-- ========================= --}}
        @if ($activeTab === 'vendor')
            <h2 class="font-semibold mb-4 flex items-center gap-2 text-blue-200">
                <i data-lucide="users" class="w-5 h-5"></i>
                Daftar Vendor Menunggu Verifikasi
            </h2>

            @if ($users->isEmpty())
                <p class="text-white/70 text-center py-6">Tidak ada vendor yang menunggu verifikasi.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-white/90 border-collapse">
                        <thead class="bg-white/20 text-xs">
                            <tr>
                                <th class="p-2 border border-white/10 text-left">Nama Vendor</th>
                                <th class="p-2 border border-white/10 text-left">Perusahaan</th>
                                <th class="p-2 border border-white/10 text-left">Bidang Usaha</th>
                                <th class="p-2 border border-white/10 text-left">NPWP</th>
                                <th class="p-2 border border-white/10 text-center">Status</th>
                                <th class="p-2 border border-white/10 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="hover:bg-white/10 transition">
                                    <td class="p-2 border border-white/10">{{ $user->name }}</td>
                                    <td class="p-2 border border-white/10">{{ $user->vendorProfile->company_name ?? '-' }}</td>
                                    <td class="p-2 border border-white/10">{{ $user->vendorProfile->bidang_usaha ?? '-' }}</td>
                                    <td class="p-2 border border-white/10">{{ $user->vendorProfile->npwp ?? '-' }}</td>

                                    <td class="p-2 border border-white/10 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs
                                            @if(($user->vendorProfile->verification_status ?? 'menunggu') == 'verified')
                                                bg-green-500/30 text-green-200
                                            @elseif(($user->vendorProfile->verification_status ?? 'menunggu') == 'rejected')
                                                bg-red-500/30 text-red-200
                                            @else
                                                bg-yellow-500/30 text-yellow-200
                                            @endif">
                                            {{ ucfirst($user->vendorProfile->verification_status ?? 'menunggu') }}
                                        </span>
                                    </td>

                                    <td class="p-2 border border-white/10 text-center space-x-1">
                                        <a href="{{ route('admin.verifikasi.detail', $user->id) }}"
                                            class="text-blue-300 hover:underline">Detail</a>

                                        <form action="{{ route('admin.verifikasi.setujui', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="text-green-300 hover:underline">Setujui</button>
                                        </form>

                                        <form action="{{ route('admin.verifikasi.tolak', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="text-red-300 hover:underline">Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif

        {{-- ========================= --}}
        {{-- TAB VERIFIKASI PRODUK --}}
        {{-- ========================= --}}
        @if ($activeTab === 'produk')
            <h2 class="font-semibold mb-4 flex items-center gap-2 text-blue-200">
                <i data-lucide="package-check" class="w-5 h-5"></i>
                Daftar Produk Vendor Menunggu Verifikasi
            </h2>

            @if ($produk->isEmpty())
                <p class="text-white/70 text-center py-6">Tidak ada produk yang menunggu verifikasi.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-white/90 border-collapse">
                        <thead class="bg-white/20 text-xs">
                            <tr>
                                <th class="p-2 border border-white/10 text-left">Nama Produk</th>
                                <th class="p-2 border border-white/10 text-left">Vendor</th>
                                <th class="p-2 border border-white/10 text-left">Harga</th>
                                <th class="p-2 border border-white/10 text-center">Status</th>
                                <th class="p-2 border border-white/10 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produk as $p)
                                <tr class="hover:bg-white/10 transition">
                                    <td class="p-2 border border-white/10">{{ $p->name }}</td>
                                    <td class="p-2 border border-white/10">{{ $p->vendor->name ?? '-' }}</td>
                                    <td class="p-2 border border-white/10">Rp {{ number_format($p->price, 0, ',', '.') }}</td>

                                    <td class="p-2 border border-white/10 text-center">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($p->status == 'verified') bg-green-500/30 text-green-200
                                            @elseif($p->status == 'rejected') bg-red-500/30 text-red-200
                                            @else bg-yellow-500/30 text-yellow-200 @endif">
                                            {{ ucfirst($p->status ?? 'menunggu') }}
                                        </span>
                                    </td>

                                    <td class="p-2 border border-white/10 text-center">

    <div class="flex items-center justify-center gap-1">

        {{-- Tombol Detail --}}
        <a href="{{ route('admin.verifikasi.produk.detail', $p->id) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded-md text-[10px] font-medium shadow transition whitespace-nowrap">
           🔍 Detail
        </a>

        {{-- Tombol Setujui --}}
        <form action="{{ route('admin.verifikasi.produk.setujui', $p->id) }}" method="POST">
            @csrf
            <button type="submit"
               class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded-md text-[10px] font-medium shadow transition whitespace-nowrap">
               ✔
            </button>
        </form>

        {{-- Tombol Tolak --}}
        <form action="{{ route('admin.verifikasi.produk.tolak', $p->id) }}" method="POST">
            @csrf
            <button type="submit"
               class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded-md text-[10px] font-medium shadow transition whitespace-nowrap">
               ✖
            </button>
        </form>

    </div>

</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif

    </div>

</div>

<script>
    lucide.createIcons();
</script>
@endsection
