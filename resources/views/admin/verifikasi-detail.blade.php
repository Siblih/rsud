@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

  {{-- 🏷️ Header --}}
  <div class="flex items-center justify-between mb-6">
      <h2 class="text-lg font-semibold text-white flex items-center gap-2">
          📁 Detail Dokumen Vendor
      </h2>
  </div>

  {{-- 🌟 Info Vendor --}}
  <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-xl p-6 mb-8">
      <div class="flex items-center gap-4 mb-6">
          <div class="w-16 h-16 rounded-2xl bg-blue-100/20 flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                   stroke-width="1.5" stroke="#fff" class="w-9 h-9">
                  <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 21h10.5M4.5 21V7.5A2.25 2.25 0 016.75 5.25h10.5A2.25 2.25 0 0119.5 7.5V21M8.25 15h7.5" />
              </svg>
          </div>
          <div>
              <h3 class="text-lg font-semibold text-white">{{ $user->vendorProfile->company_name ?? '-' }}</h3>
              <p class="text-sm text-blue-200">{{ $user->name }}</p>
          </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
              <p class="text-blue-200">👤 Nama Vendor</p>
              <p class="font-semibold text-white">{{ $user->name }}</p>
          </div>

          <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
              <p class="text-blue-200">🏢 Perusahaan</p>
              <p class="font-semibold text-white">{{ $user->vendorProfile->company_name ?? '-' }}</p>
          </div>

          <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
              <p class="text-blue-200">💼 Bidang Usaha</p>
              <p class="font-semibold text-white">{{ $user->vendorProfile->bidang_usaha ?? '-' }}</p>
          </div>

          <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
              <p class="text-blue-200">🪪 NPWP</p>
              <p class="font-semibold text-white">{{ $user->vendorProfile->npwp ?? '-' }}</p>
          </div>

          <div class="bg-white/5 border border-white/10 rounded-2xl p-4 md:col-span-2">
              <p class="text-blue-200">📍 Alamat</p>
              <p class="font-semibold text-white">{{ $user->vendorProfile->alamat ?? '-' }}</p>
          </div>

          <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
              <p class="text-blue-200">📞 Contact Person</p>
              <p class="font-semibold text-white">{{ $user->vendorProfile->contact_person ?? '-' }}</p>
          </div>

          <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
              <p class="text-blue-200">📱 Telepon</p>
              <p class="font-semibold text-white">{{ $user->vendorProfile->phone ?? '-' }}</p>
          </div>

          <div class="bg-white/5 border border-white/10 rounded-2xl p-4">
              <p class="text-blue-200">🔐 Status Verifikasi</p>
              <span class="inline-block px-3 py-1 mt-1 rounded-full text-xs font-semibold
                @if($user->vendorProfile->verification_status == 'verified') bg-green-400/20 text-green-300
                @elseif($user->vendorProfile->verification_status == 'rejected') bg-red-400/20 text-red-300
                @else bg-yellow-400/20 text-yellow-300 @endif">
                  {{ ucfirst($user->vendorProfile->verification_status) }}
              </span>
          </div>
      </div>
  </div>

  {{-- 📂 Dokumen Vendor --}}
  <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl shadow-xl p-6">
      <h3 class="text-base font-semibold text-white mb-4 flex items-center gap-2">
          📄 Dokumen Vendor
      </h3>

      @if ($user->vendorProfile->vendorDocuments->isEmpty())
          <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
              <p class="text-blue-200 text-sm">Belum ada dokumen yang diunggah.</p>
          </div>
      @else
          <ul class="space-y-3">
              @foreach ($user->vendorProfile->vendorDocuments as $doc)
                  <li class="bg-white/5 border border-white/10 p-4 rounded-2xl flex justify-between items-center hover:bg-white/10 transition">
                      <div class="flex flex-col">
                          <p class="font-medium text-white text-sm">{{ $doc->document_name ?? 'Dokumen Vendor' }}</p>
                          <p class="text-xs text-blue-200">{{ basename($doc->file_path) }}</p>
                      </div>
                      <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                         class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-md transition">
                         📂 <span>Lihat</span>
                      </a>
                  </li>
              @endforeach
          </ul>
      @endif
  </div>

  {{-- 🔙 Tombol Navigasi --}}
  <div class="mt-10 flex justify-center">
      <a href="{{ route('admin.verifikasi') }}"
         class="bg-white/10 hover:bg-white/20 border border-white/30 text-white text-sm font-semibold px-6 py-2 rounded-full transition backdrop-blur-lg shadow">
         ⬅️ Kembali ke Daftar
      </a>
  </div>

</div>
@endsection
