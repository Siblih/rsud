{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERSUD - Aplikasi Pengadaan RSUD Bangil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-blue-700 text-white p-4 flex justify-between items-center shadow-md">
        <h1 class="text-lg font-bold tracking-wide">PeRSUD Bangil</h1>
        <div>
            @auth
                <span class="mr-2 text-sm">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded-md text-sm">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    {{-- Konten utama --}}
    <main class="flex-grow p-4">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center text-sm text-gray-500 py-4 border-t bg-gray-50 mt-auto">
        © {{ date('Y') }} 
        <a href="https://siblih.rf.gd" 
           target="_blank" 
           class="text-blue-600 hover:underline font-semibold">
            SIBLIH
        </a> — PeRSUD Bangil. All rights reserved.
    </footer>

</body>
</html>
