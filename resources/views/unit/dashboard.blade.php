@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white p-4 pb-24">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6 mt-2">
        <h1 class="text-xl font-bold">📊 Dashboard {{ $unit->name }}</h1>
        <img src="https://ui-avatars.com/api/?name={{ urlencode($unit->name) }}&background=4C51BF&color=fff&size=60" 
             class="w-10 h-10 rounded-full border-2 border-white/30" alt="Profile">
    </div>

    <!-- Profil Card -->
    <div class="bg-[#2F377B] rounded-2xl p-4 mb-6 shadow-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-300">Unit</p>
                <h2 class="text-lg font-semibold">{{ $unit->name }}</h2>
            </div>
           
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
        <div class="bg-[#323B85] p-4 rounded-xl shadow-md text-center">
            <p class="text-xs text-gray-300">Total</p>
            <h3 class="text-lg font-bold text-blue-300 mt-1">{{ $total }}</h3>
        </div>
        <div class="bg-[#323B85] p-4 rounded-xl shadow-md text-center">
            <p class="text-xs text-gray-300">Disetujui</p>
            <h3 class="text-lg font-bold text-green-300 mt-1">{{ $disetujui }}</h3>
        </div>
        <div class="bg-[#323B85] p-4 rounded-xl shadow-md text-center">
            <p class="text-xs text-gray-300">Menunggu</p>
            <h3 class="text-lg font-bold text-yellow-300 mt-1">{{ $menunggu }}</h3>
        </div>
        <div class="bg-[#323B85] p-4 rounded-xl shadow-md text-center">
            <p class="text-xs text-gray-300">Ditolak</p>
            <h3 class="text-lg font-bold text-red-300 mt-1">{{ $ditolak }}</h3>
        </div>
    </div>

    <!-- Grafik Gabungan -->
    <div class="bg-[#2F377B] rounded-2xl p-4 mb-8 shadow-lg">
        <h2 class="text-sm text-gray-300 mb-3">📈 Statistik Pengadaan Gabungan</h2>
        <canvas id="combinedChart" height="150"></canvas>
    </div>

    <!-- Daftar Pengadaan -->
    <h2 class="text-sm text-gray-300 mb-2">📦 Daftar Pengadaan Terbaru</h2>
    @forelse($pengadaans as $item)
    <div class="bg-[#323B85] rounded-xl p-4 mb-3 shadow-md flex justify-between items-center">
        <div>
            <h3 class="text-sm font-semibold text-white">{{ $item->nama_pengadaan }}</h3>
            <p class="text-xs text-gray-300">
                Rp{{ number_format($item->estimasi_anggaran ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400">
                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
            </p>
        </div>
        <span class="text-xs px-2 py-1 rounded-full font-semibold
            {{ $item->status == 'disetujui' ? 'bg-green-500/20 text-green-300' :
               ($item->status == 'menunggu' ? 'bg-yellow-500/20 text-yellow-300' : 'bg-red-500/20 text-red-300') }}">
            {{ ucfirst($item->status) }}
        </span>
    </div>
    @empty
    <p class="text-center text-gray-400 mt-10">Belum ada pengajuan pengadaan.</p>
    @endforelse

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-[#2A3170]/90 backdrop-blur-md text-white shadow-lg rounded-t-2xl">
        <div class="flex justify-around items-center py-3">
            <a href="{{ route('unit.dashboard') }}" 
               class="flex flex-col items-center text-sm {{ request()->is('unit/dashboard') ? 'text-blue-400' : 'text-gray-300' }}">
                📊
                <span class="text-xs">Dashboard</span>
            </a>
            <a href="{{ route('unit.pengadaan.index') }}" 
               class="flex flex-col items-center text-sm {{ request()->is('unit/pengadaan*') ? 'text-blue-400' : 'text-gray-300' }}">
                📦
                <span class="text-xs">Pengadaan</span>
            </a>
            <a href="{{ route('unit.pengadaan.create') }}" 
               class="bg-pink-500 hover:bg-pink-600 text-white w-12 h-12 flex items-center justify-center rounded-full text-2xl -mt-6 shadow-lg">
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
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('combinedChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'], // contoh bulan
      datasets: [
        {
          label: 'Total',
          data: [{{ $total }}, {{ $total }}, {{ $total }}, {{ $total }}, {{ $total }}, {{ $total }}],
          borderColor: '#60A5FA',
          backgroundColor: 'rgba(96,165,250,0.15)',
          tension: 0.4,
          fill: true,
          borderWidth: 2,
          pointBackgroundColor: '#60A5FA'
        },
        {
          label: 'Disetujui',
          data: [{{ $disetujui }}, {{ $disetujui }}, {{ $disetujui }}, {{ $disetujui }}, {{ $disetujui }}, {{ $disetujui }}],
          borderColor: '#4ADE80',
          backgroundColor: 'rgba(74,222,128,0.15)',
          tension: 0.4,
          fill: true,
          borderWidth: 2,
          pointBackgroundColor: '#4ADE80'
        },
        {
          label: 'Menunggu',
          data: [{{ $menunggu }}, {{ $menunggu }}, {{ $menunggu }}, {{ $menunggu }}, {{ $menunggu }}, {{ $menunggu }}],
          borderColor: '#FACC15',
          backgroundColor: 'rgba(250,204,21,0.15)',
          tension: 0.4,
          fill: true,
          borderWidth: 2,
          pointBackgroundColor: '#FACC15'
        },
        {
          label: 'Ditolak',
          data: [{{ $ditolak }}, {{ $ditolak }}, {{ $ditolak }}, {{ $ditolak }}, {{ $ditolak }}, {{ $ditolak }}],
          borderColor: '#EF4444',
          backgroundColor: 'rgba(239,68,68,0.15)',
          tension: 0.4,
          fill: true,
          borderWidth: 2,
          pointBackgroundColor: '#EF4444'
        }
      ]
    },
    options: {
      plugins: { legend: { labels: { color: '#E2E8F0' } } },
      scales: {
        x: { ticks: { color: '#CBD5E1' }, grid: { color: 'rgba(255,255,255,0.05)' } },
        y: { ticks: { color: '#CBD5E1' }, grid: { color: 'rgba(255,255,255,0.05)' }, beginAtZero: true }
      }
    }
  });
</script>
@endsection
