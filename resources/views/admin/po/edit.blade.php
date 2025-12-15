@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white p-6">

    <div class="max-w-3xl mx-auto bg-white/10 p-8 rounded-2xl">

        <h2 class="text-2xl font-bold mb-6">✏️ Edit Purchase Order</h2>

        <form action="{{ route('admin.po.update', $po->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Tanggal PO --}}
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Tanggal PO</label>
                <input type="date" name="tanggal_po"
                       value="{{ $po->tanggal_po }}"
                       class="w-full p-2 rounded text-black" required>
            </div>

            <hr class="my-6 border-gray-400/30">

            {{-- ITEMS --}}
            <h3 class="text-xl font-bold mb-3">📦 Daftar Item</h3>

            <div id="items-wrapper" class="space-y-4">

                {{-- ITEM EXISTING --}}
                @foreach ($po->items as $index => $item)
                    <div class="bg-white/10 p-4 rounded-xl relative item-row">
                        
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">

                        <div class="mb-2">
                            <label class="block text-sm">Nama Item</label>
                            <input type="text"
                                   name="items[{{ $index }}][nama_item]"
                                   value="{{ $item->nama_item }}"
                                   class="w-full p-2 rounded text-black" required>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm">Qty</label>
                                <input type="number"
                                       name="items[{{ $index }}][qty]"
                                       value="{{ $item->qty }}"
                                       class="w-full p-2 rounded text-black" required>
                            </div>

                            <div>
                                <label class="block text-sm">Harga</label>
                                <input type="number"
                                       name="items[{{ $index }}][harga]"
                                       value="{{ $item->harga }}"
                                       class="w-full p-2 rounded text-black" required>
                            </div>
                        </div>

                        <button type="button"
                                class="absolute top-2 right-2 text-red-300 hover:text-red-500 remove-item">
                            ✖
                        </button>
                    </div>
                @endforeach

            </div>

            {{-- BUTTON TAMBAH ITEM --}}
            <button type="button"
                    id="add-item"
                    class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg">
                ➕ Tambah Item
            </button>


            <hr class="my-6 border-gray-400/30">

            {{-- SUBMIT --}}
            <button type="submit"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-semibold">
                💾 Simpan Perubahan
            </button>

            <a href="{{ route('admin.po.show', $po->id) }}"
               class="ml-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg">
                ⬅ Kembali
            </a>

        </form>

    </div>
</div>

{{-- JavaScript --}}
<script>
let itemIndex = {{ count($po->items) }};

document.getElementById('add-item').addEventListener('click', function() {
    const wrapper = document.getElementById('items-wrapper');

    const html = `
        <div class="bg-white/10 p-4 rounded-xl relative item-row">

            <div class="mb-2">
                <label class="block text-sm">Nama Item</label>
                <input type="text" name="items[${itemIndex}][nama_item]" class="w-full p-2 rounded text-black" required>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm">Qty</label>
                    <input type="number" name="items[${itemIndex}][qty]" class="w-full p-2 rounded text-black" required>
                </div>
                <div>
                    <label class="block text-sm">Harga</label>
                    <input type="number" name="items[${itemIndex}][harga]" class="w-full p-2 rounded text-black" required>
                </div>
            </div>

            <button type="button"
                    class="absolute top-2 right-2 text-red-300 hover:text-red-500 remove-item">
                ✖
            </button>
        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    itemIndex++;
});

// Hapus item
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.item-row').remove();
    }
});
</script>

@endsection
