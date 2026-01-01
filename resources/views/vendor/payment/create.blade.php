@extends('layouts.vendor-app')

@section('content')
<div class="max-w-3xl mx-auto bg-white/10 p-8 rounded-xl">

    <h2 class="text-2xl font-bold mb-4 text-white">
        Ajukan Pembayaran
    </h2>

    <p class="text-white/70 mb-6">
        Kontrak: <b>{{ $kontrak->nomor_kontrak }}</b>
    </p>

    <form method="POST"
          action="{{ route('vendor.payment.store', $kontrak->id) }}"
          enctype="multipart/form-data"
          class="space-y-4">

        @csrf

        @foreach ([
            'invoice' => 'Invoice',
            'bast' => 'BAST',
            'faktur_pajak' => 'Faktur Pajak',
            'surat_permohonan' => 'Surat Permohonan'
        ] as $field => $label)

            <div>
                <label class="text-white block mb-1">{{ $label }}</label>
                <input type="file"
                       name="{{ $field }}"
                       required
                       class="w-full bg-white p-2 rounded">
            </div>

        @endforeach

        <div class="text-right mt-6">
            <button class="px-6 py-2 bg-green-600 rounded hover:bg-green-700">
                Ajukan Pembayaran
            </button>
        </div>
    </form>

</div>
@endsection
