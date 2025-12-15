<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $po->nomor_po }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; font-size: 12px; }
        .header { text-align: center; margin-bottom: 12px; }
        .meta { margin-bottom: 12px; }
        .items table { width:100%; border-collapse: collapse; }
        .items th, .items td { border:1px solid #ddd; padding:6px; }
        .right { text-align:right; }
        .signature { margin-top:40px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>RSUD - Purchase Order</h2>
        <p>Nomor: {{ $po->nomor_po }}</p>
    </div>

    <div class="meta">
        <strong>Vendor:</strong> {{ $po->vendor->name }} <br>
        <strong>Kontrak:</strong> {{ $po->kontrak->nomor_kontrak }} <br>
        <strong>Tanggal:</strong> {{ $po->tanggal_po }}
    </div>

    <div class="items">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Item</th>
                    <th>Qty</th>
                    <th class="right">Harga</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($po->items as $i => $it)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $it->nama_item }}</td>
                    <td class="right">{{ $it->qty }}</td>
                    <td class="right">{{ number_format($it->harga,2,',','.') }}</td>
                    <td class="right">{{ number_format($it->total,2,',','.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="right"><strong>Total</strong></td>
                    <td class="right"><strong>{{ number_format($po->total,2,',','.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="signature">
        <table style="width:100%; margin-top:40px;">
            <tr>
                <td style="width:50%; text-align:center;">
                    PPK / Admin<br><br><br>
                    (_____________________)
                </td>
                <td style="width:50%; text-align:center;">
                    Vendor<br><br><br>
                    (_____________________)
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
