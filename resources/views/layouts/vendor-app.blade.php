<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERSUD - Vendor Area</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- 🎨 Font elegan --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #eef4ff, #ffffff);
        }

        /* 🌈 Efek transparan glassmorphism */
        .glass {
            background: rgba(29, 78, 216, 0.35);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .brand-title {
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 0.5px;
            color: #fff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
        }
        .brand-sub {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.75);
        }
    </style>
</head>
<body class="text-gray-800 min-h-screen flex flex-col relative">

    {{-- 🌤 HEADER --}}
    <nav class="glass fixed top-0 left-0 w-full px-5 py-3 flex items-center justify-between z-50 rounded-b-3xl shadow-lg">
        {{-- Logo & Nama Vendor --}}
        <div class="flex items-center space-x-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Vendor') }}&background=1D4ED8&color=fff"
                 alt="Avatar" class="w-10 h-10 rounded-full shadow-md">
            <div>
                <h1 class="brand-title">PERSUD Bangil</h1>
                <p class="brand-sub">{{ Auth::user()->name ?? 'Vendor' }}</p>
            </div>
        </div>

        {{-- Logout --}}
       <div class="flex items-center space-x-3">
            <button class="relative bg-white/30 hover:bg-white/40 transition rounded-full p-2 shadow-inner">
                <i data-lucide="bell" class="w-5 h-5 text-white"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
            @auth
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-1 rounded-full text-xs font-medium shadow hover:opacity-90 transition">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </nav>

    <div class="h-20"></div>

    {{-- ✅ SweetAlert --}}
    @if (session('success') || session('error'))
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: '{{ session("success") ? "success" : "error" }}',
            title: '{{ session("success") ?? session("error") }}',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            background: '#fff',
            color: '#333',
            customClass: { popup: 'rounded-xl shadow-lg' }
          });
        });
      </script>
    @endif

    {{-- 🌟 KONTEN UTAMA --}}
    <main class="flex-grow p-4 pb-24 z-0">
        @yield('content')
    </main>

    <footer class="glass fixed bottom-0 left-0 w-full py-2 rounded-t-3xl shadow-lg flex justify-around items-center backdrop-blur-xl border-t border-white/20 text-white">
    
    {{-- 🏠 Dashboard --}}
    <a href="{{ route('vendor.dashboard') }}" 
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('vendor.dashboard') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="home" class="w-5 h-5 mb-0.5"></i>
        <span>Dashboard</span>
    </a>

    {{-- 🏢 Profil --}}
    <a href="{{ route('vendor.profile.show') }}" 
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('vendor.profile.*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="building-2" class="w-5 h-5 mb-0.5"></i>
        <span>Profil</span>
    </a>

    {{-- 📦 Produk Saya --}}
    <a href="{{ route('vendor.produk') }}" 
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('vendor.produk*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="box" class="w-5 h-5 mb-0.5"></i>
        <span>Produk</span>
    </a>

    {{-- 💼 Pengadaan --}}
    <a href="{{ route('vendor.pengadaan') }}"
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('vendor.pengadaan*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="briefcase" class="w-5 h-5 mb-0.5"></i>
        <span>Pengadaan</span>
    </a>

    {{-- 📜 Kontrak & Pembayaran --}}
    <a href="{{ route('vendor.kontrak') }}" 
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('vendor.kontrak*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="file-text" class="w-5 h-5 mb-0.5"></i>
        <span>Kontrak</span>
    </a>

    {{-- 🔔 Notifikasi --}}
    <a href="{{ route('vendor.notifikasi') }}" 
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('vendor.notifikasi*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="bell" class="w-5 h-5 mb-0.5"></i>
        <span>Notifikasi</span>
    </a>

    {{-- 📈 Riwayat --}}
    <a href="{{ route('vendor.riwayat') }}" 
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('vendor.riwayat*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="bar-chart-3" class="w-5 h-5 mb-0.5"></i>
        <span>Riwayat</span>
    </a>
</footer>


    <script>
        lucide.createIcons();
    </script>

</body>
</html>
