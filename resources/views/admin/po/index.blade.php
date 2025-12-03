@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white p-6">

    <div class="bg-white/10 p-6 rounded-2xl shadow-lg max-w-6xl mx-auto">

        <h2 class="text-2xl font-bold mb-6">📄 Daftar Purchase Order</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-white/20 text-blue-200">
                        <th class="p-2">Nomor PO</th>
                        <th class="p-2">Kontrak</th>
                        <th class="p-2">Vendor</th>
                        <th class="p-2">Tanggal</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($poList as $po)
                    <tr class="border-b border-white/10">
                        <td class="p-2 font-semibold">{{ $po->nomor_po }}</td>
                        <td class="p-2">{{ $po->kontrak->nomor_kontrak }}</td>
                        <td class="p-2">{{ $po->vendor->name }}</td>
                        <td class="p-2">{{ $po->tanggal_po }}</td>

                        {{-- Status badge --}}
                        <td class="p-2">
                            <span
                                class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($po->status === 'approved') bg-green-600/60 text-green-200 
                                @elseif($po->status === 'pending') bg-yellow-500/60 text-yellow-100
                                @elseif($po->status === 'rejected') bg-red-600/60 text-red-200
                                @else bg-gray-500/60 text-gray-200
                                @endif">
                                {{ ucfirst($po->status) }}
                            </span>
                        </td>

                        {{-- Tombol Aksi --}}
                        <td class="p-2 flex gap-2">

                            {{-- Detail --}}
                            <a href="{{ route('admin.po.show', $po->id) }}"
                                class="px-3 py-1 bg-blue-600/80 rounded-lg hover:bg-blue-700 transition">
                                Detail
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('admin.po.edit', $po->id) }}"
                                class="px-3 py-1 bg-yellow-400 text-black rounded-lg hover:bg-yellow-500 transition">
                                Edit
                            </a>

                            {{-- Preview PDF --}}
                            @if ($po->file_po)
                            <button
                                onclick="openPdfModal('{{ asset('storage/'.$po->file_po) }}')"
                                class="px-3 py-1 bg-white text-black rounded-lg hover:bg-gray-200 transition">
                                Preview
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>


    {{-- MODAL PREVIEW PDF --}}
    <div id="pdfModal" class="hidden fixed inset-0 bg-black/70 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-3xl h-[80vh] rounded-2xl overflow-hidden relative shadow-xl">
            <button onclick="closePdfModal()"
                    class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1 rounded">
                X
            </button>

            <iframe id="pdfFrame" src="" class="w-full h-full"></iframe>
        </div>
    </div>

</div>

{{-- JS Modal --}}
<script>
    function openPdfModal(url) {
        document.getElementById('pdfFrame').src = url;
        document.getElementById('pdfModal').classList.remove('hidden');
    }
    function closePdfModal() {
        document.getElementById('pdfModal').classList.add('hidden');
        document.getElementById('pdfFrame').src = "";
    }
</script>

@endsection
