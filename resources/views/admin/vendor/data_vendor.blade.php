@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white px-6 py-10">

    <h1 class="text-3xl font-bold mb-8 tracking-wide">📦 Data Vendor Aktif</h1>

    <div class="bg-[#1C315E] rounded-xl p-6 shadow-2xl border border-blue-400/20">
        
        @if ($vendors->count() == 0)
            <p class="text-center text-gray-300 py-10 text-lg">Belum ada vendor aktif.</p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-6">

            @foreach ($vendors as $v)
            <div class="bg-[#0B3A66] rounded-2xl p-5 shadow-lg hover:shadow-2xl hover:scale-[1.02] 
                        transition-all duration-300 border border-white/10">

                {{-- Nama Perusahaan --}}
                <h2 class="text-xl font-bold mb-2 text-blue-200">
                    {{ $v->vendorProfile->company_name ?? 'Nama perusahaan tidak tersedia' }}
                </h2>

                {{-- Bidang Usaha --}}
                <p class="text-gray-200 text-sm flex items-center gap-2">
                    <span class="text-blue-300">🏢</span>
                    {{ $v->vendorProfile->bidang_usaha ?? '-' }}
                </p>

                {{-- Telepon --}}
                <p class="text-gray-200 text-sm flex items-center gap-2">
                    <span class="text-green-300">📞</span>
                    {{ $v->vendorProfile->phone ?? '-' }}
                </p>

                {{-- Email --}}
                <p class="text-gray-200 text-sm flex items-center gap-2">
                    <span class="text-yellow-300">✉️</span>
                    {{ $v->email ?? '-' }}
                </p>

                {{-- Alamat --}}
                <p class="text-gray-300 text-xs mt-3 flex items-start gap-2 leading-relaxed">
                    <span class="text-red-300 mt-[2px]">📍</span>
                    {{ $v->vendorProfile->alamat ?? 'Alamat tidak tersedia' }}
                </p>

                {{-- Status --}}
                <span class="inline-block mt-4 px-3 py-1 text-xs rounded-full 
                             bg-green-500/30 text-green-200 border border-green-500/40">
                    ✔ Aktif & Terverifikasi
                </span>

                {{-- Button --}}
                <a href="{{ route('admin.vendor.detail', $v->id) }}"
                   class="block mt-5 bg-blue-600 text-center py-2.5 rounded-xl font-semibold 
                          hover:bg-blue-700 transition shadow-md hover:shadow-lg">
                    Lihat Detail
                </a>

            </div>
            @endforeach

        </div>

    </div>

</div>
@endsection
