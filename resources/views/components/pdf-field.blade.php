@props(['label','value'])

<div class="flex justify-between bg-white/5 border border-white/10 px-3 py-2 rounded-lg">
  <span class="text-blue-200">{{ $label }}</span>
  <span class="text-white font-semibold">{{ $value ?? '-' }}</span>
</div>
