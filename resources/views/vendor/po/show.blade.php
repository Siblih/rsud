@extends('layouts.vendor-app')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Detail PO {{ $po->nomor_po }}</h2>

    <div class="mb-4">
        <p><strong>Kontrak:</strong> {{ $po->kontrak->nomor_kontrak }}</p>
        <p><strong>Tanggal:</strong> {{ $po->tanggal_po }}</p>
        <p><strong>Status:</strong> {{ $po->status }}</p>
    </div>

    <div class="mb-6">
        <h3>Items</h3>
        <ul>
            @foreach($po->items as $it)
                <li>{{ $it->nama_item }} — {{ $it->qty }} × {{ number_format($it->harga,0,',','.') }} = {{ number_format($it->total,0,',','.') }}</li>
            @endforeach
        </ul>
        <p><strong>Total:</strong> Rp {{ number_format($po->total,0,',','.') }}</p>
    </div>

    {{-- Signature canvas --}}
    <div class="mb-4">
        <label class="block mb-2 font-semibold">Tanda Tangan (digital)</label>
        <div id="signature-pad" style="background:#fff; border:1px solid #ccc; width:500px; height:200px;">
            <canvas id="sigCanvas" width=500 height=200></canvas>
        </div>
        <div class="mt-2">
            <button id="clearBtn" class="px-3 py-1 bg-gray-300">Clear</button>
            <button id="saveBtn" class="px-3 py-1 bg-green-600 text-white">Tandatangani & Kirim</button>
        </div>
    </div>

    {{-- preview existing signature --}}
    @if($po->vendor_signature_path)
    <div class="mt-4">
        <p class="mb-2">Existing signature:</p>
        <img src="{{ asset('storage/'.$po->vendor_signature_path) }}" alt="signature" style="max-width:300px;">
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    const canvas = document.getElementById('sigCanvas');
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgba(255,255,255,0)'
    });

    document.getElementById('clearBtn').addEventListener('click', ()=> signaturePad.clear());

    document.getElementById('saveBtn').addEventListener('click', async () => {
        if (signaturePad.isEmpty()) {
            alert('Silakan tanda tangani terlebih dahulu.');
            return;
        }
        const dataUrl = signaturePad.toDataURL('image/png');

        const res = await fetch("{{ route('vendor.po.sign', $po->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({signature: dataUrl})
        });
        const json = await res.json();
        if (json.success) {
            alert('Signature saved');
            window.location.reload();
        } else {
            alert(json.error || json.message || 'Gagal menyimpan tanda tangan');
        }
    });
</script>
@endsection
