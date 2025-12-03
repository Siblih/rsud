@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white pb-24">

  <!-- Header -->
  <div class="p-4 flex justify-between items-center">
      <h2 class="text-xl font-bold">📦 Daftar Pengajuan</h2>
      <a href="{{ route('unit.pengadaan.create') }}" 
         class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full shadow-md transition flex items-center gap-1">
          <span class="text-lg">＋</span>
      </a>
  </div>

  <!-- Daftar Pengadaan -->
  <div class="px-4">
    @forelse($pengadaans as $p)
      <div class="flex items-center justify-between bg-[#2F377B] rounded-2xl shadow-lg mb-3 p-4 border border-white/10 backdrop-blur-sm">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-xl bg-blue-400/20 flex items-center justify-center text-blue-300 text-2xl">
            📦
          </div>
          <div>
            <h3 class="text-base font-semibold text-white">{{ $p->nama_pengadaan }}</h3>
            <p class="text-sm text-blue-200">Rp{{ number_format($p->estimasi_anggaran ?? 0, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400">
              {{ \Carbon\Carbon::parse($p->created_at)->translatedFormat('d M Y H:i') }}
            </p>
          </div>
        </div>

        <div class="flex flex-col items-end gap-1">
          <button onclick="showDetail({{ $p->id }})" class="bg-blue-500/20 hover:bg-blue-500/30 p-2 rounded-lg text-blue-300 transition">
            🔍
          </button>

          @if($p->status == 'menunggu')
          <a href="{{ route('unit.pengadaan.edit', $p->id) }}" 
             class="bg-yellow-500/20 hover:bg-yellow-500/30 p-2 rounded-lg text-yellow-300 transition">✏️</a>

          <form action="{{ route('unit.pengadaan.destroy', $p->id) }}" method="POST" 
                onsubmit="return confirm('Yakin ingin menghapus pengadaan ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="bg-red-500/20 hover:bg-red-500/30 p-2 rounded-lg text-red-300 transition">🗑️</button>
          </form>
          @endif
        </div>
      </div>
    @empty
      <p class="text-center text-gray-300 mt-10">Belum ada pengajuan pengadaan.</p>
    @endforelse
  </div>
</div>

<!-- Bottom Navigation Bar -->
<nav class="fixed bottom-0 left-0 right-0 bg-[#2A3170]/90 backdrop-blur-md text-white shadow-lg rounded-t-2xl">
  <div class="flex justify-around items-center py-3">
      <a href="{{ route('unit.dashboard') }}" class="flex flex-col items-center text-sm {{ request()->is('unit/dashboard') ? 'text-blue-400' : 'text-gray-300' }}">
          🏠
          <span class="text-xs">Beranda</span>
      </a>
      <a href="{{ route('unit.pengadaan.index') }}" class="flex flex-col items-center text-sm {{ request()->is('unit/pengadaan*') ? 'text-blue-400' : 'text-gray-300' }}">
          📦
          <span class="text-xs">Pengadaan</span>
      </a>
      <a href="#" class="bg-pink-500 hover:bg-pink-600 text-white w-12 h-12 flex items-center justify-center rounded-full text-2xl -mt-6 shadow-lg">
          ＋
      </a>
      <a href="#" class="flex flex-col items-center text-sm text-gray-300">
          🔔
          <span class="text-xs">Notifikasi</span>
      </a>
      <a href="#" class="flex flex-col items-center text-sm text-gray-300">
          👤
          <span class="text-xs">Profil</span>
      </a>
  </div>
</nav>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 px-3">
  <div class="bg-[#2F377B] text-white rounded-2xl shadow-2xl max-w-md w-full p-6 overflow-y-auto max-h-[90vh] relative border border-white/10 backdrop-blur-md">
    <button onclick="closeDetail()" class="absolute top-3 right-3 text-gray-300 hover:text-white text-2xl font-bold">&times;</button>
    <h3 class="text-xl font-bold mb-5" id="modalNama"></h3>

    <div class="space-y-4">
      <div class="bg-[#323B85] rounded-xl p-3">
        <p class="text-sm text-gray-300 font-semibold">Kategori</p>
        <p id="modalJenis" class="text-white"></p>
      </div>

      <div class="bg-[#323B85] rounded-xl p-3 grid grid-cols-2 gap-3">
        <div>
          <p class="text-sm text-gray-300 font-semibold">Jumlah</p>
          <p id="modalJumlah" class="text-white"></p>
        </div>
        <div>
          <p class="text-sm text-gray-300 font-semibold">Satuan</p>
          <p id="modalSatuan" class="text-white"></p>
        </div>
      </div>

      <div class="bg-[#323B85] rounded-xl p-3">
        <p class="text-sm text-gray-300 font-semibold">Estimasi Anggaran</p>
        <p id="modalNilai" class="text-blue-300 font-semibold text-lg">Rp0</p>
      </div>

      <div class="bg-[#323B85] rounded-xl p-3">
        <p class="text-sm text-gray-300 font-semibold">Spesifikasi / Uraian</p>
        <p id="modalSpesifikasi" class="text-white"></p>
      </div>

      <div class="bg-[#323B85] rounded-xl p-3">
        <p class="text-sm text-gray-300 font-semibold">Alasan</p>
        <p id="modalAlasan" class="text-white"></p>
      </div>

      <div class="bg-[#323B85] rounded-xl p-3 flex justify-between items-center">
        <div>
          <p class="text-sm text-gray-300 font-semibold">Status</p>
          <span id="modalStatus" class="px-2 py-1 rounded-full text-white font-semibold text-xs"></span>
        </div>
        <div>
          <p class="text-sm text-gray-300 font-semibold">Diajukan pada</p>
          <p id="modalTanggal" class="text-gray-200 text-sm"></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const pengadaans = @json($pengadaans);

  function showDetail(id) {
    const p = pengadaans.find(item => item.id === id);
    if (!p) return;

    document.getElementById('modalNama').textContent = p.nama_pengadaan;
    document.getElementById('modalJenis').textContent = p.jenis_pengadaan || '-';
    document.getElementById('modalJumlah').textContent = p.jumlah ?? '-';
    document.getElementById('modalSatuan').textContent = p.satuan ?? '-';
    document.getElementById('modalNilai').textContent = `Rp${(p.estimasi_anggaran ?? 0).toLocaleString('id-ID')}`;
    document.getElementById('modalSpesifikasi').textContent = p.spesifikasi || p.uraian_pekerjaan || '-';
    document.getElementById('modalAlasan').textContent = p.alasan || '-';

    const statusEl = document.getElementById('modalStatus');
    statusEl.textContent = p.status;
    statusEl.className = 'px-2 py-1 rounded-full text-white font-semibold text-xs';
    if(p.status === 'menunggu') statusEl.classList.add('bg-yellow-500');
    else if(p.status === 'disetujui') statusEl.classList.add('bg-green-500');
    else if(p.status === 'ditolak') statusEl.classList.add('bg-red-500');

    document.getElementById('modalTanggal').textContent = new Date(p.created_at)
      .toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute:'2-digit' });

    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
  }

  function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
  }
</script>
@endsection
