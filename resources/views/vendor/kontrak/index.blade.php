@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white px-4 pt-6 pb-24">

  <h1 class="text-xl font-bold mb-5 text-center flex items-center justify-center gap-2">
      <i data-lucide="file-text" class="w-5 h-5"></i> Kontrak & Pembayaran
  </h1>

  @forelse($kontraks as $kontrak)
  <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-5 mb-4 shadow-lg hover:bg-white/20 transition">

      {{-- 🔹 Header Info --}}
      <div class="flex justify-between items-center mb-2">
          <h2 class="font-semibold text-lg text-blue-100">{{ $kontrak->pengadaan->nama_pengadaan ?? 'Tanpa Nama' }}</h2>
          <span class="text-xs px-3 py-1 rounded-full 
              {{ $kontrak->status === 'selesai' ? 'bg-green-400/20 text-green-300' : 'bg-yellow-400/20 text-yellow-300' }}">
              {{ ucfirst($kontrak->status ?? 'Proses') }}
          </span>
      </div>

      {{-- 🔸 Detail Singkat --}}
      <p class="text-sm text-blue-200 mb-2">
          Nomor Kontrak: <span class="font-medium text-white">{{ $kontrak->nomor_kontrak ?? '-' }}</span>
      </p>
      <p class="text-sm text-blue-200 mb-2">
          Nilai Kontrak: <span class="font-medium text-white">Rp{{ number_format($kontrak->nilai_kontrak ?? 0, 0, ',', '.') }}</span>
      </p>
      <p class="text-sm text-blue-200">
          Tanggal: <span class="font-medium text-white">{{ \Carbon\Carbon::parse($kontrak->tanggal_kontrak ?? now())->translatedFormat('d M Y') }}</span>
      </p>

      {{-- 🔹 Aksi --}}
      <div class="mt-4 flex flex-col gap-2">

          <a href="{{ route('vendor.kontrak.show', $kontrak->id) }}" 
             class="text-sm bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-lg text-white shadow text-center">
             Lihat Detail Kontrak
          </a>

          @if($kontrak->status !== 'selesai')
              <a href="{{ route('vendor.kontrak.upload.form', $kontrak->id) }}#upload"
                 class="text-sm bg-green-500/20 hover:bg-green-500/30 px-3 py-1 rounded-lg 
                        text-green-300 border border-green-300/20 text-center">
                 Upload Dokumen Pembayaran
              </a>
          @endif

          <a href="{{ route('vendor.kontrak.show', $kontrak->id) }}#pembayaran"
             class="text-sm bg-white/10 hover:bg-white/20 px-3 py-1 rounded-lg 
                    text-blue-200 border border-white/20 text-center">
             Status Pembayaran
          </a>

      </div>
  </div>
  @empty
  <div class="text-center text-blue-200 mt-10">
      📄 Belum ada kontrak yang tercatat.
  </div>
  @endforelse

</div>

<script>
    lucide.createIcons();
</script>
@endsection
