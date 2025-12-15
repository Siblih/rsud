@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white p-6">

    <div class="max-w-3xl mx-auto bg-white/10 p-8 rounded-2xl shadow-lg">

        <h2 class="text-2xl font-bold mb-6">📄 Detail Purchase Order</h2>

        {{-- Info PO --}}
        <div class="space-y-3">

            <div>
                <span class="text-gray-300">Nomor PO:</span>
                <p class="text-xl font-semibold">{{ $po->nomor_po }}</p>
            </div>

            <div>
                <span class="text-gray-300">Kontrak:</span>
                <p class="font-semibold">{{ $po->kontrak->nomor_kontrak }}</p>
            </div>

            <div>
                <span class="text-gray-300">Vendor:</span>
                <p class="font-semibold">{{ $po->vendor->name }}</p>
            </div>

            <div>
                <span class="text-gray-300">Tanggal PO:</span>
                <p>{{ $po->tanggal_po }}</p>
            </div>

            {{-- STATUS --}}
            <div>
                <span class="text-gray-300">Status:</span>
                <span class="px-4 py-1 rounded-full text-sm font-semibold
                    @if($po->status === 'approved') bg-green-600/60 @endif
                    @if($po->status === 'pending') bg-yellow-500/60 @endif
                    @if($po->status === 'rejected') bg-red-600/60 @endif
                ">
                    {{ ucfirst($po->status) }}
                </span>
            </div>

        </div>


        {{-- ITEM PO --}}
        <div class="mt-10">
            <h3 class="text-lg font-semibold mb-3">📦 Item Purchase Order</h3>

            @if($po->items && $po->items->count() > 0)
                <table class="w-full text-white/90 border-collapse">
                    <thead class="bg-white/20">
                        <tr>
                            <th class="p-2 border border-white/10">#</th>
                            <th class="p-2 border border-white/10 text-left">Nama Item</th>
                            <th class="p-2 border border-white/10 text-center">Qty</th>
                            <th class="p-2 border border-white/10 text-right">Harga</th>
                            <th class="p-2 border border-white/10 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($po->items as $i => $item)
                        <tr class="hover:bg-white/10">
                            <td class="border border-white/10 p-2 text-center">{{ $i+1 }}</td>
                            <td class="border border-white/10 p-2">{{ $item->nama_item }}</td>
                            <td class="border border-white/10 p-2 text-center">{{ $item->qty }}</td>
                            <td class="border border-white/10 p-2 text-right">{{ number_format($item->harga) }}</td>
                            <td class="border border-white/10 p-2 text-right">
                                {{ number_format($item->qty * $item->harga) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- TOTAL --}}
                <div class="mt-4 text-right">
                    <p class="text-lg font-bold">
                        Total: Rp {{ number_format($po->total) }}
                    </p>
                </div>

            @else
                <p class="text-gray-300 italic">Belum ada item di PO ini.</p>
            @endif
        </div>


        {{-- RIWAYAT REVISI --}}
        <div class="mt-10">
            <h3 class="text-lg font-semibold mb-2">🕓 Riwayat Revisi</h3>

            @if($po->revision_history)
                @foreach(json_decode($po->revision_history, true) as $h)
                    <div class="border-l-4 border-blue-400 pl-3 my-2">
                        <small class="text-gray-300">
                            {{ $h['timestamp'] }} — User: {{ $h['user_id'] }} — {{ $h['action'] }}
                        </small>
                        <p class="text-white">{{ $h['note'] ?? '' }}</p>
                    </div>
                @endforeach
            @else
                <p class="text-gray-300 italic">Belum ada revisi.</p>
            @endif
        </div>


        {{-- FILE PREVIEW --}}
        <div class="mt-10">
            <h3 class="text-lg font-semibold mb-2">📎 File PO</h3>

            @if($po->file_po)
                <iframe src="{{ asset('storage/'.$po->file_po) }}"
                        class="w-full h-[600px] rounded-lg bg-white"></iframe>
            @else
                <p class="text-gray-300 italic">Tidak ada file terlampir.</p>
            @endif
        </div>


        {{-- TOMBOL EDIT --}}
        <div class="mt-6 text-right">
            <a href="{{ route('admin.po.edit', $po->id) }}"
               class="px-5 py-2 bg-yellow-600 hover:bg-yellow-700 rounded-lg shadow">
                ✏️ Edit PO
            </a>
        </div>

        {{-- TOMBOL KEMBALI --}}
        <div class="mt-10 text-right">
            <a href="{{ url('/admin/pengadaan?tab=po') }}"
               class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition">
                ← Kembali ke Tabel PO
            </a>
        </div>

    </div>

</div>
@endsection
