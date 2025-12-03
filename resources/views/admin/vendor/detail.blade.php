@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-white flex items-center gap-2">
            📁 Detail Vendor
        </h2>
    </div>

    {{-- INFO VENDOR --}}
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-xl p-6 mb-8">

        {{-- ICON + NAMA --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-2xl bg-blue-100/20 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" 
                    class="w-9 h-9">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 21h10.5M4.5 21V7.5A2.25 2.25 0 016.75 5.25h10.5A2.25 2.25 0 0119.5 7.5V21M8.25 15h7.5" />
                </svg>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-white">
                    {{ $vendor->vendorProfile->company_name ?? '-' }}
                </h3>
                <p class="text-sm text-blue-200">
                    {{ $vendor->name }}
                </p>
            </div>
        </div>

        {{-- GRID INFORMASI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-blue-200">👤 Nama Vendor</p>
                <p class="font-semibold text-white">{{ $vendor->name }}</p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-blue-200">🏢 Perusahaan</p>
                <p class="font-semibold text-white">{{ $vendor->vendorProfile->company_name ?? '-' }}</p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-blue-200">💼 Bidang Usaha</p>
                <p class="font-semibold text-white">{{ $vendor->vendorProfile->bidang_usaha ?? '-' }}</p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-blue-200">🪪 NPWP</p>
                <p class="font-semibold text-white">{{ $vendor->vendorProfile->npwp ?? '-' }}</p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4 md:col-span-2">
                <p class="text-blue-200">📍 Alamat</p>
                <p class="font-semibold text-white">{{ $vendor->vendorProfile->alamat ?? '-' }}</p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-blue-200">📞 Contact Person</p>
                <p class="font-semibold text-white">{{ $vendor->vendorProfile->contact_person ?? '-' }}</p>
            </div>

            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-blue-200">📱 Telepon</p>
                <p class="font-semibold text-white">{{ $vendor->vendorProfile->phone ?? '-' }}</p>
            </div>

            {{-- STATUS VERIFIKASI --}}
            <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
                <p class="text-blue-200">🔐 Status Verifikasi</p>

                @php
                    $status = optional($vendor->vendorProfile)->verification_status ?? 'pending';
                @endphp

                <span class="inline-block px-3 py-1 mt-1 rounded-full text-xs font-semibold
                    @if($status == 'verified') 
                        bg-green-400/20 text-green-300
                    @elseif($status == 'rejected') 
                        bg-red-400/20 text-red-300
                    @else 
                        bg-yellow-400/20 text-yellow-300
                    @endif">
                    {{ ucfirst($status) }}
                </span>
            </div>

        </div>
    </div>

    {{-- DOKUMEN --}}
<div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-xl p-6">

    <h3 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
        📄 Dokumen Vendor
    </h3>

    @php
        $documents = $vendor->vendorProfile->vendorDocuments->first();

        $fields = [
            'nib' => 'NIB (Nomor Induk Berusaha)',
            'siup' => 'SIUP',
            'npwp' => 'NPWP',
            'akta_perusahaan' => 'Akta Perusahaan',
            'domisili' => 'Surat Domisili',
            'sertifikat_halal' => 'Sertifikat Halal',
            'sertifikat_iso' => 'Sertifikat ISO',
            'pengalaman' => 'Dokumen Pengalaman'
        ];
    @endphp

    @if (!$documents)
        {{-- Jika belum ada dokumen --}}
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 text-center">
            <p class="text-blue-200 text-sm">Belum ada dokumen yang diunggah.</p>
        </div>
    @else

        {{-- GRID RESPONSIF --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @foreach ($fields as $field => $label)
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 flex flex-col justify-between hover:bg-white/10 transition">

                    <div>
                        <p class="font-medium text-white text-sm mb-1">{{ $label }}</p>

                        <p class="text-xs text-blue-200 break-all">
                            {{ $documents->$field ? basename($documents->$field) : 'Belum diupload' }}
                        </p>
                    </div>

                    <div class="mt-3">
                        @if ($documents->$field)
                            <a href="{{ asset('storage/' . $documents->$field) }}"
                                target="_blank"
                                class="block text-center text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-full shadow transition">
                                👁️ Lihat Dokumen
                            </a>
                        @else
                            <span class="block text-center text-gray-400 italic text-xs mt-1">
                                Tidak ada file
                            </span>
                        @endif
                    </div>

                </div>
            @endforeach

        </div>
    @endif

</div>


    {{-- KEMBALI --}}
    <div class="mt-10 flex justify-center">
        <a href="{{ route('admin.vendor.data_vendor') }}"
        class="bg-white/10 hover:bg-white/20 border border-white/30 text-white 
            text-sm font-semibold px-6 py-2 rounded-full transition backdrop-blur-lg shadow">
            ⬅️ Kembali ke Daftar Vendor
        </a>
    </div>

</div>
@endsection
