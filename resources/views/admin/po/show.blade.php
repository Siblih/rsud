@extends('layouts.admin-app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-[#0A2647] to-[#144272] text-white p-6">

    <div class="max-w-4xl mx-auto bg-white/10 p-8 rounded-2xl shadow-lg">

        <h2 class="text-2xl font-bold mb-6">📄 Detail Purchase Order</h2>

        {{-- ================= INFO PO ================= --}}
        <div class="grid md:grid-cols-2 gap-4 text-sm">

            <div>
                <span class="text-gray-300">Nomor PO</span>
                <p class="text-lg font-semibold">{{ $po->nomor_po }}</p>
            </div>

            <div>
                <span class="text-gray-300">Nomor Kontrak</span>
                <p class="font-semibold">{{ $po->kontrak->nomor_kontrak }}</p>
            </div>

            <div>
                <span class="text-gray-300">Vendor</span>
                <p class="font-semibold">{{ $po->vendor->name }}</p>
            </div>

            <div>
                <span class="text-gray-300">Tanggal PO</span>
                <p>{{ $po->tanggal_po }}</p>
            </div>

            <div>
                <span class="text-gray-300">Status</span><br>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                    @if($po->status === 'approved') bg-green-600/70
                    @elseif($po->status === 'draft') bg-yellow-600/70
                    @else bg-gray-600/70 @endif">
                    {{ strtoupper($po->status) }}
                </span>
            </div>

        </div>

        {{-- ================= ITEM PO ================= --}}
        <div class="mt-10">
            <h3 class="text-lg font-semibold mb-3">📦 Item Purchase Order</h3>

            @if($po->items->count())
            <div class="overflow-x-auto">
                <table class="w-full border border-white/10 text-sm">
                    <thead class="bg-white/20">
                        <tr>
                            <th class="p-2 border">#</th>
                            <th class="p-2 border text-left">Nama Item</th>
                            <th class="p-2 border text-left">Spesifikasi</th>
                            <th class="p-2 border text-center">Qty</th>
                            <th class="p-2 border text-center">Satuan</th>
                            <th class="p-2 border text-right">Harga</th>
                            <th class="p-2 border text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($po->items as $i => $item)
                        <tr class="hover:bg-white/10">
                            <td class="p-2 border text-center">{{ $i+1 }}</td>
                            <td class="p-2 border">{{ $item->nama_item }}</td>
                            <td class="p-2 border">{{ $item->spesifikasi ?? '-' }}</td>
                            <td class="p-2 border text-center">{{ $item->qty }}</td>
                            <td class="p-2 border text-center">{{ $item->satuan ?? '-' }}</td>
                            <td class="p-2 border text-right">
                                Rp {{ number_format($item->harga,0,',','.') }}
                            </td>
                            <td class="p-2 border text-right">
                                Rp {{ number_format($item->qty * $item->harga,0,',','.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-right text-lg font-bold">
                Total: Rp {{ number_format($po->total,0,',','.') }}
            </div>
            @else
                <p class="italic text-gray-300">Belum ada item.</p>
            @endif
        </div>
{{-- ================= RIWAYAT REVISI ================= --}}
<div class="mt-10">
    <h3 class="text-lg font-semibold mb-4">🕓 Riwayat Revisi</h3>

    @if($po->revision_history)
        <div class="space-y-3">
            @foreach(json_decode($po->revision_history, true) as $h)
                <div class="p-4 bg-white/5 rounded-xl border border-white/10">
                    <div class="flex justify-between text-xs text-gray-300 mb-1">
                        <span>
                            {{ $h['timestamp'] ?? '-' }}
                        </span>
                        <span>
                            User ID: {{ $h['user_id'] ?? '-' }}
                        </span>
                    </div>

                    <p class="font-semibold text-white">
                        {{ strtoupper($h['action'] ?? 'UPDATE') }}
                    </p>

                    @if(!empty($h['note']))
                        <p class="text-sm text-gray-200 mt-1">
                            {{ $h['note'] }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p class="italic text-gray-300">
            Belum ada riwayat revisi.
        </p>
    @endif
</div>

        {{-- ================= STATUS PDF & AKSI ================= --}}
        <div class="mt-10 p-4 bg-white/5 rounded-xl border border-white/10">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                {{-- STATUS PDF --}}
                <div>
                    <p class="text-gray-300 text-sm">Status PDF</p>
                    @if($po->file_po)
                        <span class="text-green-400 font-semibold">✔ Sudah digenerate</span>
                    @else
                        <span class="text-yellow-400 font-semibold">⚠ Belum digenerate</span>
                    @endif
                </div>

                {{-- AKSI --}}
                <div class="flex flex-wrap gap-3">

                    <a href="{{ route('admin.po.edit', $po->id) }}"
                       class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 rounded-lg shadow text-sm font-semibold">
                        ✏️ Edit PO
                    </a>

                    <a href="{{ route('admin.po.generate-pdf', $po->id) }}"
                       class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg shadow text-sm font-semibold">
                        📄 {{ $po->file_po ? 'Regenerate PDF' : 'Generate PDF' }}
                    </a>

                    @if($po->file_po)
                    <a href="{{ asset('storage/'.$po->file_po) }}"
                       target="_blank"
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg shadow text-sm font-semibold">
                        ⬇️ Download PDF
                    </a>
                    @endif

                </div>
            </div>
        </div>

        {{-- ================= PREVIEW PDF ================= --}}
        @if($po->file_po)
        <div class="mt-6">
            <iframe
                src="{{ asset('storage/'.$po->file_po) }}"
                class="w-full h-[600px] rounded-lg bg-white"
                type="application/pdf">
            </iframe>
        </div>
        @endif

        {{-- ================= KEMBALI ================= --}}
        <div class="mt-10 text-right">
            <a href="{{ url('/admin/pengadaan?tab=po') }}"
               class="inline-block px-5 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg shadow">
                ← Kembali ke Tabel PO
            </a>
        </div>

    </div>
</div>
@endsection
