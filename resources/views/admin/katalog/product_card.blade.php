<div class="min-w-[180px] bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 shadow-lg overflow-hidden hover:scale-[1.02] transition">

    {{-- Foto --}}
    <div class="w-full h-32 bg-white/5 border-b border-white/10">
        @if(!empty($p->photos[0]))
            <img src="{{ asset('storage/'.$p->photos[0]) }}" class="w-full h-full object-cover">
        @else
            <div class="h-full flex items-center justify-center text-blue-200">No Image</div>
        @endif
    </div>

    {{-- Body --}}
    <div class="p-3">

        <p class="font-semibold text-sm text-white line-clamp-2 min-h-[38px]">
            {{ $p->name }}
        </p>

        <p class="font-bold text-blue-300 mt-1">
            Rp {{ number_format($p->price,0,',','.') }}
        </p>

        {{-- TKDN --}}
        @if($p->tkdn)
            <p class="text-[11px] mt-1 px-2 py-1 rounded bg-green-500/20 text-green-300 border border-green-400/20 w-fit">
                TKDN {{ $p->tkdn }}%
            </p>
        @endif

        {{-- UMK --}}
        @if($p->is_umk)
            <p class="text-[11px] mt-1 px-2 py-1 rounded bg-yellow-500/20 text-yellow-200 border border-yellow-400/20 w-fit">
                UMK
            </p>
        @endif

        <a href="{{ route('admin.katalog.detail', $p->id) }}"
           class="mt-3 block text-center text-xs font-semibold bg-blue-600 hover:bg-blue-700 rounded-lg py-2">
            Detail
        </a>
    </div>
</div>
