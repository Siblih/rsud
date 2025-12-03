<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERSUD - Admin RSUD Bangil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(to bottom, #eef4ff, #ffffff); }
        .glass { background: rgba(29, 78, 216, 0.35); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border: 1px solid rgba(255, 255, 255, 0.15); }
        .brand-title { font-weight: 700; font-size: 1.15rem; letter-spacing: 0.5px; color: #fff; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.25); }
        .brand-sub { font-size: 0.7rem; color: rgba(255,255,255,0.75); }
    </style>
</head>
<body class="text-gray-800 min-h-screen flex flex-col relative">

    {{-- HEADER --}}
    <nav class="glass fixed top-0 left-0 w-full px-5 py-3 flex items-center justify-between z-50 rounded-b-3xl shadow-lg">
        <div class="flex items-center space-x-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=1D4ED8&color=fff" class="w-10 h-10 rounded-full shadow-md">
            <div>
                <h1 class="brand-title">Admin RSUD</h1>
                <p class="brand-sub">{{ Auth::user()->name ?? 'Guest' }}</p>
            </div>
        </div>
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

    {{-- KONTEN UTAMA --}}
    <main class="flex-grow p-4 pb-28 z-0">
        @yield('content')
    </main>

    {{-- FOOTER / BOTTOM NAV --}}
    <footer class="glass fixed bottom-0 left-0 w-full py-2 rounded-t-3xl shadow-lg flex justify-around items-center backdrop-blur-xl border-t border-white/20 text-white">
    {{-- 🏠 Dashboard --}}
    <a href="{{ route('admin.dashboard') }}"
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('admin.dashboard') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="home" class="w-5 h-5 mb-0.5"></i>
        <span>Dashboard</span>
    </a>

    {{-- ✅ Verifikasi Vendor --}}
    <a href="{{ route('admin.verifikasi') }}"
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('admin.verifikasi*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="check-square" class="w-5 h-5 mb-0.5"></i>
        <span>Verifikasi</span>
    </a>

    {{-- 🛒 Katalog --}}
    <a href="{{ route('admin.katalog') }}"
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('admin.katalog*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="shopping-bag" class="w-5 h-5 mb-0.5"></i>
        <span>Katalog</span>
    </a>
{{-- ⚙️ Pengaturan (Tombol Tengah) --}}
    <a href="{{ route('admin.pengaturan') }}"
       class="relative -top-3 bg-white text-blue-600 rounded-full p-3 shadow-lg border-4 border-white/30 flex flex-col items-center justify-center hover:scale-105 transition">
        <i data-lucide="settings" class="w-5 h-5 mb-0.5"></i>
        <span class="text-[10px] font-medium mt-0.5">Pengaturan</span>
    </a>
    {{-- 📦 Pengadaan --}}
    <a href="{{ route('admin.pengadaan') }}"
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('admin.pengadaan*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="package" class="w-5 h-5 mb-0.5"></i>
        <span>Pengadaan</span>
    </a>

    {{-- 📊 Laporan --}}
    <a href="{{ route('admin.laporan') }}"
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('admin.laporan*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="bar-chart-3" class="w-5 h-5 mb-0.5"></i>
        <span>Laporan</span>
    </a>

    {{-- 🧾 Log Aktivitas --}}
    <a href="{{ route('admin.log') }}"
       class="flex flex-col items-center text-[11px] {{ request()->routeIs('admin.log*') ? 'text-blue-300 font-semibold' : 'text-white hover:text-blue-200' }}">
        <i data-lucide="file-text" class="w-5 h-5 mb-0.5"></i>
        <span>Log</span>
    </a>
</footer>


    <script>
        lucide.createIcons();
        
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>
</html>
