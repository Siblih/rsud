<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERSUD - Pengadaan RSUD BANGIL</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-8 text-center">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-4">
            <img src="{{ asset('images/rsud.png') }}" alt="Logo KOYAMI" class="w-20 h-20 mb-2">
            <h1 class="text-xl font-bold text-gray-800">PERSUD</h1>
            <p class="text-sm text-gray-500">Pengadaan RSUD BANGIL</p>
        </div>

        <hr class="my-4">

        <!-- Teks Sambutan -->
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Selamat Datang di PERSUD</h2>
        <p class="text-sm text-gray-500 mb-6">Pengadaan RSUD BANGIL</p>
        <p class="text-gray-600 text-sm mb-8">
            Aplikasi Management Pengadaan RSUD BANGIL
        </p>

        <!-- Tombol -->
        <div class="flex flex-col space-y-3">
            <a href="{{ route('login') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg flex items-center justify-center space-x-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4 4m-4-4l4-4m9 8V8a2 2 0 012-2h3" />
                </svg>
                <span>Masuk</span>
            </a>

            <a href="{{ route('register') }}"
               class="border border-gray-300 text-gray-700 hover:bg-gray-100 font-medium py-2 rounded-lg flex items-center justify-center space-x-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 4v16m8-8H4" />
                </svg>
                <span>Daftar</span>
            </a>
        </div>

        <!-- Footer -->
    <footer class="mt-6 text-sm text-gray-500">
        © 2025 PERSUD. Dibuat oleh 
        <a href="https://siblih.rf.gd" 
           class="text-blue-600 hover:text-blue-800 font-medium" 
           target="_blank" 
           rel="noopener noreferrer">
           SIBLIH
        </a>.
        Hak cipta dilindungi.
    </footer>

    </div>

</body>
</html>
