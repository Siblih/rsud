@extends('layouts.vendor-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#1A1F4A] to-[#2B3370] text-white pb-24 px-4 pt-6">
  <div class="max-w-xl mx-auto">

    <div class="flex items-center justify-between mb-4">
      <h1 class="text-lg font-semibold text-white">📦 Produk Saya</h1>
      <a href="{{ route('vendor.produk.create') }}" class="bg-blue-600 px-3 py-2 rounded-lg text-white text-sm">＋ Tambah</a>
    </div>

    @if(session('success'))
      <div class="bg-green-500/20 text-green-200 p-3 rounded-lg mb-3">{{ session('success') }}</div>
    @endif

    @forelse($products as $p)
      <div class="bg-white/10 border border-white/20 rounded-2xl p-4 mb-3 flex gap-3 items-start">

        {{-- FOTO --}}
        <div class="w-16 h-16 rounded-lg bg-white/5 flex items-center justify-center overflow-hidden">
          @if(!empty($p->photos[0]))
            <img src="{{ asset('storage/'.$p->photos[0]) }}" alt="foto" class="object-cover w-full h-full">
          @else
            <div class="text-blue-200">🏷</div>
          @endif
        </div>

        {{-- INFO --}}
        <div class="flex-1">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-white font-semibold">{{ $p->name }}</h3>
              <p class="text-xs text-blue-200 mt-1">{{ Str::limit($p->description, 80) }}</p>
            </div>

            <div class="text-right">
              <div class="text-sm text-white font-semibold">Rp{{ number_format($p->price,0,',','.') }}</div>
              <div class="text-xs text-gray-300 mt-1">{{ $p->unit ?? '-' }}</div>
            </div>
          </div>

          {{-- BUTTON --}}
          <div class="mt-3 flex gap-2">
            <a href="{{ route('vendor.produk.show', $p->id) }}" class="text-xs bg-blue-600 px-3 py-1 rounded-md">Detail</a>
            <a href="{{ route('vendor.produk.edit', $p->id) }}" class="text-xs bg-white/10 px-3 py-1 rounded-md">Ubah</a>

            <form action="{{ route('vendor.produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');">
              @csrf @method('DELETE')
              <button type="submit" class="text-xs bg-red-600 px-3 py-1 rounded-md">Hapus</button>
            </form>
          </div>

          {{-- STATUS --}}
          <div class="mt-2 text-xs flex items-center gap-1">
            <span class="text-blue-200">Status:</span>

            <span class="px-2 py-0.5 rounded-full text-xs
              {{ $p->status=='verified' ? 'bg-green-500/20 text-green-200' : 'bg-yellow-500/20 text-yellow-200' }}">
              {{ ucfirst($p->status) }}
            </span>
          </div>

        </div>
      </div>
    @empty
      <div class="text-center text-blue-200 mt-8">Belum ada produk. Tambah produk untuk tampil di katalog.</div>
    @endforelse

    <div class="mt-4">
      {{ $products->links() }}
    </div>

  </div>
</div>
@endsection
