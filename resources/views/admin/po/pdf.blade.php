{{-- resources/views/admin/po/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $po->nomor_po }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px; text-align: left; }
        .right { text-align: right; }
        .signature { width: 45%; display: inline-block; text-align: center; margin-top: 50px; }
    </style>
</head>
<body>

<div class="header">
    <h2>RSUD {{ $po->rsud_name ?? '' }}</h2>
    <p>{{ $po->rsud_address ?? '' }}</p>
    <h3>Purchase Order (PO)</h3>
    <p>Nomor PO: {{ $po->nomor_po }}</p>
    <p>Tanggal PO: {{ \Carbon\Carbon::parse($po->tanggal_po)->format('d-m-Y') }}</p>
</div>

<p><strong>Vendor:</strong> {{ $po->vendor->name }}</p>
<p><strong>Alamat:</strong> {{ $po->vendor->address ?? '-' }}</p>
<p><strong>Kontrak Terkait:</strong> {{ $po->kontrak->nomor_kontrak }}</p>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Item / Jasa</th>
            <th>Spesifikasi</th>
            <th>Qty</th>
            <th>Satuan</th>
            <th>Harga Satuan (Rp)</th>
            <th>Subtotal (Rp)</th>
        </tr>
    </thead>
    <tbody>
       @foreach($po->items as $i => $item)
<tr>
    <td>{{ $i+1 }}</td>
    <td>{{ $item->nama_item }}</td>
    <td>{{ $item->spesifikasi ?? '-' }}</td>
    <td>{{ $item->qty }}</td>
    <td>{{ number_format($item->harga,0,',','.') }}</td>
    <td>{{ number_format($item->total,0,',','.') }}</td>
</tr>
@endforeach

    </tbody>
</table>

<p class="right"><strong>Total: Rp {{ number_format($po->total, 0, ',', '.') }}</strong></p>

<p><strong>Keterangan:</strong> {{ $po->catatan ?? '-' }}</p>

<div class="signature">
    <p>RSUD</p>
    <br><br>
    <p>_________________________</p>
    <p>{{ $po->ppk_name ?? '-' }}</p>
</div>

<div class="signature" style="float: right;">
    <p>Vendor</p>
    <br><br>
    <p>_________________________</p>
    <p>{{ $po->vendor->name }}</p>
</div>

</body>
</html>
