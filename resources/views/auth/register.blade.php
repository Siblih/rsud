<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun | PERSUD</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

  <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-md">
    <div class="text-center mb-6">
      <img src="{{ asset('images/rsud.png') }}" alt="Logo" class="mx-auto w-16 mb-2">
      <h2 class="text-2xl font-semibold text-gray-800">PERSUD</h2>
      <p class="text-gray-500 text-sm">Pengadaan RSUD BANGIL</p>
    </div>

    <hr class="mb-6 border-gray-300">

    <h3 class="text-center text-lg font-semibold mb-4">Daftar sebagai Vendor</h3>
    <p class="text-center text-sm text-gray-500 mb-6">Silakan isi data di bawah untuk mendaftar.</p>

    <form method="POST" action="/register" class="space-y-4">
      @csrf

      <div>
        <label for="name" class="block text-sm text-gray-600 mb-1">Nama Perusahaan</label>
        <input id="name" type="text" name="name" placeholder="Nama" required
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <div>
        <label for="email" class="block text-sm text-gray-600 mb-1">Email</label>
        <input id="email" type="email" name="email" placeholder="Email" required
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <div class="grid grid-cols-2 gap-2">
        <div>
          <label for="password" class="block text-sm text-gray-600 mb-1">Password</label>
          <input id="password" type="password" name="password" placeholder="Password" required
                 class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div>
          <label for="password_confirmation" class="block text-sm text-gray-600 mb-1">Konfirmasi Password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Konfirmasi Password" required
                 class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
      </div>

      

      <button type="submit"
              class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
        🚀 Daftar Sekarang
      </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-4">
      Sudah punya akun?
      <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Masuk di sini</a>
    </p>

    <footer class="text-center text-sm text-gray-500 mt-6 mb-2">
      © {{ date('Y') }} <strong>PERSUD</strong>. Dibuat oleh 
      <a href="https://siblih.rf.gd" target="_blank" class="text-blue-600 hover:underline">SIBLIH</a>. 
      Hak cipta dilindungi.
    </footer>
  </div>

</body>
</html>
