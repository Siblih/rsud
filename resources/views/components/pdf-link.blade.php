@props(['file', 'text'])

@if($file)
  <a href="{{ asset('storage/'.$file) }}" 
     target="_blank"
     class="flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-2 rounded-lg hover:bg-white/10 transition">
     <span class="text-red-300 text-lg">📄</span>
     <span class="underline">{{ $text }}</span>
  </a>
@endif
