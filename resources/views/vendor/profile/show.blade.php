@extends('layouts.vendor-app')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

    {{-- 🏢 Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold text-white">Profil Perusahaan</h1>
        <a href="{{ route('vendor.profile.edit') }}"
           class="bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium text-xs px-3 py-1.5 rounded-full transition-all backdrop-blur-lg shadow">
           ✏️ Edit
        </a>
    </div>

    {{-- 🌟 Kartu Profil --}}
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-xl p-5 mb-6">
        <div class="flex items-center gap-4 mb-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-100/30 flex items-center justify-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff"
                    class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 21h10.5M4.5 21V7.5a2.25 2.25 0 012.25-2.25h10.5A2.25 2.25 0 0119.5 7.5V21m-10.5-6h6" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-white">{{ $profile->company_name ?? 'Belum diisi' }}</h2>
                <p class="text-sm text-blue-200">{{ $profile->bidang_usaha ?? '-' }}</p>
            </div>
        </div>

        {{-- 📋 Info Utama --}}
        <div class="divide-y divide-white/10 text-sm">
            <div class="py-3 flex justify-between">
                <span class="text-blue-200">🧾 NIB</span>
                <span class="font-medium text-white">{{ $profile->nib ?? '-' }}</span>
            </div>
            <div class="py-3 flex justify-between">
                <span class="text-blue-200">📑 SIUP</span>
                <span class="font-medium text-white">{{ $profile->siup ?? '-' }}</span>
            </div>
            <div class="py-3 flex justify-between">
                <span class="text-blue-200">💳 NPWP</span>
                <span class="font-medium text-white">{{ $profile->npwp ?? '-' }}</span>
            </div>
            <div class="py-3 flex justify-between items-start">
                <span class="text-blue-200">📍 Alamat</span>
                <span class="font-medium text-white text-right w-1/2">{{ $profile->alamat ?? '-' }}</span>
            </div>
            <div class="py-3 flex justify-between">
                <span class="text-blue-200">👤 Contact Person</span>
                <span class="font-medium text-white">{{ $profile->contact_person ?? '-' }}</span>
            </div>
            <div class="py-3 flex justify-between">
                <span class="text-blue-200">📞 Telepon</span>
                <span class="font-medium text-white">{{ $profile->phone ?? '-' }}</span>
            </div>
        </div>

        {{-- 📝 Deskripsi --}}
        <div class="mt-5 bg-white/5 rounded-2xl p-4 border border-white/10">
            <p class="text-xs text-blue-200 mb-1 font-medium">📝 Deskripsi / Pengalaman</p>
            <p class="text-sm leading-relaxed text-white">
                {{ $profile->description ?? 'Belum ada deskripsi perusahaan.' }}
            </p>
        </div>

        {{-- 🔐 Status Verifikasi --}}
        <div class="mt-6 flex items-center justify-between bg-white/5 rounded-2xl px-4 py-3 border border-white/10">
            <span class="font-medium text-blue-100">Status Verifikasi</span>
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                @if($profile->verification_status == 'verified') bg-green-400/20 text-green-300
                @elseif($profile->verification_status == 'rejected') bg-red-400/20 text-red-300
                @else bg-yellow-400/20 text-yellow-300 @endif">
                {{ ucfirst($profile->verification_status ?? 'pending') }}
            </span>
        </div>
    </div>
{{-- 📂 Dokumen Perusahaan --}}
<div class="mt-6 bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-xl p-5">
    <h3 class="text-blue-200 font-semibold mb-4 flex items-center justify-between">
        <span class="flex items-center gap-2">
            <i data-lucide="folder-open" class="w-4 h-4"></i> Dokumen Perusahaan
        </span>
        <a href="{{ route('vendor.documents.index') }}" 
           class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full shadow transition">
           📂 Lihat Semua
        </a>
    </h3>

    @if($profile->vendorDocuments && $profile->vendorDocuments->count() > 0)
        <ul class="divide-y divide-white/10 text-sm">
            @foreach($profile->vendorDocuments->take(3) as $doc)
            <li class="py-3 flex justify-between items-center">
                <div>
                    <p class="font-medium text-white">
    {{ strtoupper(pathinfo($doc->file_path, PATHINFO_FILENAME)) }}
</p>

                    <p class="text-xs text-blue-200">
                        Diupload {{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('d M Y, H:i') }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($profile->verification_status == 'verified') bg-green-400/20 text-green-300
                @elseif($profile->verification_status == 'rejected') bg-red-400/20 text-red-300
                @else bg-yellow-400/20 text-yellow-300 @endif">
                {{ ucfirst($profile->verification_status ?? 'pending') }}
                    </span>
                </div>
            </li>
            @endforeach
        </ul>
        @if($profile->vendorDocuments->count() > 3)
            <p class="text-xs text-blue-300 mt-3 text-center">Menampilkan 3 dokumen terbaru...</p>
        @endif
    @else
        <div class="text-center text-sm text-blue-200 py-4">
            📁 Belum ada dokumen yang diupload.
        </div>
    @endif
</div>

    {{-- 🔙 Tombol Navigasi --}}
    <div class="mt-8 flex flex-col gap-3">

        <a href="{{ route('vendor.profile.edit') }}"
           class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-semibold py-3 rounded-xl shadow-md transition">
           ✏️ Ubah Profil
        </a>
    </div>

    {{-- 🌀 Floating Edit Button (Mobile) --}}
    <a href="{{ route('vendor.profile.update') }}"
       class="fixed bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg md:hidden">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
             class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M16.862 4.487l1.687 1.687a1.875 1.875 0 010 2.651l-8.486 8.486a4.5 4.5 0 01-1.591 1.024l-3.182 1.061 1.061-3.182a4.5 4.5 0 011.024-1.591l8.486-8.486a1.875 1.875 0 012.651 0z" />
        </svg>
    </a>

</div>
@endsection
