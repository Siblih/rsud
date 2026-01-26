<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk | PERSUD</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

  <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-md">
    <div class="text-center mb-6">
      <!-- Logo Ganda -->
        <div class="flex items-center justify-center space-x-6 mb-4">
            <img src="{{ asset('images/rsud.png') }}" alt="Logo RSUD" class="w-20 h-20">
            <img src="{{ asset('images/pasuruan.png') }}" alt="Logo PERSUD" class="w-20 h-20">
        </div>
      <h2 class="text-2xl font-semibold text-gray-800">PERSUD</h2>
      <p class="text-gray-500 text-sm">Pengadaan RSUD BANGIL</p>
    </div>

    <hr class="mb-6 border-gray-300">

    <h3 class="text-center text-lg font-semibold mb-4">Masuk</h3>
    <p class="text-center text-sm text-gray-500 mb-6">
      Gunakan akun yang telah terdaftar untuk masuk.
    </p>
@if(session('success'))
    <div class="bg-green-500 text-white px-4 py-2 rounded mb-4 text-center">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-500 text-white px-4 py-2 rounded mb-4 text-center">
        {{ $errors->first() }}
    </div>
@endif

    <form method="POST" action="/login" class="space-y-4">
      @csrf
      <div>
        <label for="email" class="block text-sm text-gray-600 mb-1">Email</label>
        <input id="email" type="email" name="email" placeholder="Masukkan email"
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <div>
        <label for="password" class="block text-sm text-gray-600 mb-1">Password</label>
        <input id="password" type="password" name="password" placeholder="Masukkan password"
               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>

      <div class="flex items-center justify-between text-sm">
        <label class="flex items-center">
          <input type="checkbox" name="remember" class="mr-2">
          Ingat saya
        </label>
      </div>

      <button type="submit"
              class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
        <span>🔐</span> Login
      </button>

      <p class="text-center text-sm text-gray-500 mt-4">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar sekarang</a>
      </p>

      <!-- 👇 COPYRIGHT di dalam form -->
      <div class="text-center text-xs text-gray-400 mt-6 border-t pt-4">
        © {{ date('Y') }} <strong>PERSUD</strong> <br>
        Dibuat oleh <a href="https://siblih.rf.gd" target="_blank"
        class="text-blue-500 hover:underline font-medium">SIBLIH</a>
      </div>
    </form>
  </div>

</body>
</html>
