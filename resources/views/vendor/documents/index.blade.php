@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white pb-24 px-4 pt-6">
  <div class="max-w-4xl mx-auto">

    <!-- Header -->
    <div class="text-center mb-6">
      <h2 class="text-xl font-bold text-white">📁 Dokumen Vendor Anda</h2>
      <p class="text-sm text-blue-200">Berikut daftar dokumen yang telah Anda upload</p>
    </div>

    @if (!$documents)
      <div class="bg-yellow-500/20 border border-yellow-400/30 text-yellow-100 p-4 rounded-xl text-center">
        ⚠️ Anda belum mengunggah dokumen apapun.
      </div>

      <div class="text-center mt-6">
        <a href="{{ route('vendor.documents.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-5 rounded-xl shadow-md transition">
           📤 Upload Sekarang
        </a>
      </div>
    @else
      <div class="bg-white/10 backdrop-blur-lg p-6 rounded-2xl shadow-xl border border-white/20">
        <table class="min-w-full text-sm text-white">
          <tbody class="divide-y divide-white/10">

            @foreach ([
              'nib' => '🧾 NIB (Nomor Induk Berusaha)',
              'pengalaman' => '🧾 KBLI (Surat Klasifikasi)',
              'siup' => '📑 PB UMKU (Surat Izin Usaha)',
              'npwp' => '💳 NPWP',
              'akta_perusahaan' => '📘 Akta Pendirian & Perubahan Terakhir',
              'domisili' => '🏠 Surat Domisili',
              'sertifikat_halal' => '🕌 Sertifikat Halal',
              'sertifikat_iso' => '🏅 Sertifikat ISO'
              
            ] as $field => $label)
              <tr>
                <td class="py-3 px-4 font-medium text-blue-200">{{ $label }}</td>
                <td class="py-3 px-4 text-right">
                  @if ($documents->$field)
                   <a href="{{ asset('storage/'.$documents->$field) }}" target="_blank"
   class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 
          hover:from-indigo-600 hover:to-blue-700 text-white text-xs font-medium 
          px-3 py-1.5 rounded-full shadow-md transition-all duration-300 hover:scale-105">
    <i data-lucide="eye" class="w-4 h-4"></i>
    <span>Lihat Dokumen</span>
</a>

                  @else
                    <span class="text-gray-400 italic">Belum diupload</span>
                  @endif
                </td>
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>

      <div class="mt-6 text-center">
        <a href="{{ route('vendor.documents.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-5 rounded-xl shadow-md transition">
           ✏️ Perbarui Dokumen
        </a>
      </div>
    @endif

  </div>
</div>
@endsection
