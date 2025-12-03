@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white pb-24 px-4 pt-6">
  <div class="max-w-5xl mx-auto">

    <!-- Header -->
    <div class="text-center mb-6">
      <h2 class="text-xl font-bold text-white">📑 Upload Dokumen Vendor</h2>
      <p class="text-sm text-blue-200">Unggah atau perbarui dokumen perusahaan Anda</p>
    </div>

    {{-- Pesan --}}
    @if (session('success'))
      <div class="bg-green-500/20 text-green-200 px-4 py-2 rounded-lg mb-4 text-center border border-green-400/30">
        ✅ {{ session('success') }}
      </div>
    @endif

    @if(isset($vendorProfile) && $vendorProfile->verification_status === 'rejected')
      <div class="bg-red-500/20 text-red-200 px-4 py-3 rounded-xl mb-5 border border-red-400/30">
        <strong>⚠️ Dokumen Anda ditolak.</strong><br>
        {{ $vendorProfile->reject_reason ?? 'Silahkan perbaiki dan unggah ulang.' }}
      </div>
    @endif

    <form action="{{ route('vendor.documents.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white/10 border border-white/20 rounded-2xl p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
      @csrf

      @php
        $fields = [
          'nib' => 'Nomor Induk Berusaha (NIB)',
          'siup' => 'PB UMKU / Surat Izin Usaha',
          'npwp' => 'NPWP Perusahaan',
          'akta_perusahaan' => 'Akta Pendirian Perusahaan',
          'domisili' => 'Surat Domisili',
          'sertifikat_halal' => 'Sertifikat Halal (Opsional)',
          'sertifikat_iso' => 'Sertifikat ISO (Opsional)',
          'pengalaman' => 'KBLI (Surat Klasifikasi)',
        ];
      @endphp


      {{-- LOOP ITEM 2 KOLOM --}}
      @foreach($fields as $key => $label)
      <div class="space-y-1">

        <label class="text-sm text-blue-200 font-medium">{{ $label }}</label>

        <div class="bg-white/5 border border-white/20 rounded-xl p-3">

          {{-- Jika SUDAH ADA --}}
          @if(!empty($documents?->$key))

            <div class="flex flex-col gap-2">

              {{-- Status + Nama File --}}
              <div>
                <span class="text-green-300 text-xs flex items-center gap-1">
                  <i data-lucide="check-circle" class="w-4 h-4"></i> Sudah diunggah
                </span>

                <span class="text-white text-sm font-semibold block">
                  {{ basename($documents->$key) }}
                </span>
              </div>

              {{-- Tombol aksi --}}
              <div class="flex items-center gap-2">

                {{-- Tombol lihat --}}
                <a href="{{ asset('storage/'.$documents->$key) }}"
                   target="_blank"
                   class="flex items-center gap-1 bg-blue-600 hover:bg-blue-700 
                          px-3 py-1 rounded-lg text-xs font-medium transition">
                  <i data-lucide="eye" class="w-4 h-4"></i> Lihat
                </a>

                {{-- Tombol ganti --}}
                <button type="button"
                        onclick="document.getElementById('replace_{{ $key }}').click();"
                        class="flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600
                               px-3 py-1 rounded-lg text-xs font-medium transition">
                    <i data-lucide="file-up" class="w-4 h-4"></i> Ganti
                </button>

                {{-- Hidden file input --}}
                <input type="file" id="replace_{{ $key }}" name="{{ $key }}" class="hidden">

              </div>

            </div>

          @else
          {{-- Jika BELUM DIUPLOAD --}}
            <div class="flex justify-between items-center">

              <div class="flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-300"></i>
                <span class="text-red-300 text-sm">Belum diunggah</span>
              </div>

              <input type="file"
                     name="{{ $key }}"
                     class="w-36 bg-white/10 border border-white/20 rounded-lg p-2 text-xs">
            </div>

          @endif

        </div>

      </div>
      @endforeach


      <!-- Tombol Simpan -->
      <div class="md:col-span-2 pt-5">
        <button type="submit"
          class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl shadow-md transition">
          💾 Simpan Dokumen
        </button>

        <a href="{{ route('vendor.documents.index') }}"
           class="w-full inline-block text-center mt-3 bg-white/10 border border-white/30 
                  text-blue-200 font-semibold py-3 rounded-xl shadow-md hover:bg-white/20 transition">
          📂 Lihat Semua Dokumen
        </a>
      </div>

    </form>

  </div>
</div>

<script>
  lucide.createIcons();
</script>
@endsection
