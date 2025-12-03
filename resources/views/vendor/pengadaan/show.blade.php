@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

  <h1 class="text-xl font-bold mb-5 text-center">📋 Detail Pengadaan</h1>

  <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-5 shadow-xl mb-6">
      <h2 class="text-lg font-semibold text-white">{{ $pengadaan->nama_pengadaan }}</h2>
      <p class="text-sm text-blue-200 mt-1">{{ $pengadaan->deskripsi }}</p>
      <p class="text-sm mt-2">📅 Batas Pengajuan:
          <span class="text-yellow-300">{{ \Carbon\Carbon::parse($pengadaan->batas_pengajuan)->translatedFormat('d M Y H:i') }}</span>
      </p>
  </div>

  {{-- 📤 Form Kirim Penawaran --}}
  @if(!$penawaran)
  <form method="POST" action="{{ route('vendor.pengadaan.penawaran', $pengadaan->id) }}" enctype="multipart/form-data"
        class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-5 shadow-lg">
      @csrf
      <div class="mb-4">
          <label class="block text-blue-200 text-sm mb-1">💰 Harga Penawaran</label>
          <input type="number" name="harga" step="0.01"
                 class="w-full p-2 rounded-lg bg-white/10 text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-blue-400"
                 placeholder="Masukkan harga penawaran" required>
      </div>

      <div class="mb-4">
          <label class="block text-blue-200 text-sm mb-1">📄 Upload Dokumen Penawaran (PDF/DOC)</label>
          <input type="file" name="file_penawaran"
                 class="w-full p-2 text-sm bg-white/10 text-blue-100 border border-white/20 rounded-lg" required>
      </div>

      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold shadow">
              🚀 Kirim Penawaran
      </button>
  </form>
  @else
  <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-5 text-center">
      <p class="text-blue-200 mb-2">📤 Kamu sudah mengirim penawaran untuk pengadaan ini.</p>
      <p class="text-sm">Status: 
          <span class="px-3 py-1 rounded-full text-xs font-semibold
              @if($penawaran->status === 'verified') bg-green-400/20 text-green-300
              @elseif($penawaran->status === 'rejected') bg-red-400/20 text-red-300
              @else bg-yellow-400/20 text-yellow-300 @endif">
              {{ ucfirst($penawaran->status) }}
          </span>
      </p>
  </div>
  @endif

</div>
@endsection
