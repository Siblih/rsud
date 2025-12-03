@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white p-6">

    <div class="max-w-2xl mx-auto bg-white/10 p-8 rounded-2xl">

        <h2 class="text-2xl font-bold mb-6">📝 Buat Purchase Order</h2>

       <form action="{{ route('admin.po.store', $kontrak->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="kontrak_id" value="{{ $kontrak->id }}">
            <input type="hidden" name="vendor_id" value="{{ $kontrak->vendor_id }}">

            {{-- Nomor PO otomatis --}}
<div class="mb-4">
    <label class="block mb-1 font-semibold">Nomor PO (Otomatis)</label>
    <input type="text"
           value="{{ $nomorPO }}"
           disabled
           class="w-full p-2 rounded bg-gray-300 text-black font-semibold">
</div>



            <div class="mb-4">
                <label class="block mb-1">Tanggal PO</label>
                <input type="date" name="tanggal_po" class="w-full p-2 rounded text-black" required>
            </div>

           <div class="mb-4">
    <label class="block mb-1">File PO (Opsional)</label>

    <label class="bg-white text-black px-4 py-2 rounded cursor-pointer inline-block">
        📄 Pilih File
        <input type="file" name="file_po" class="hidden">
    </label>
</div>


            <button type="submit"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg">
                Simpan PO
            </button>

        </form>

    </div>

</div>
@endsection
