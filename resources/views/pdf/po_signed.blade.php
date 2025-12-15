<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $po->nomor_po }} (Signed)</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; font-size: 12px; }
        .header { text-align:center; margin-bottom: 12px;}
        .items table { width:100%; border-collapse: collapse; }
        .items th, .items td { border:1px solid #ddd; padding:6px; }
        .right { text-align:right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>RSUD - Purchase Order</h2>
        <p>Nomor: {{ $po->nomor_po }}</p>
    </div>

    <div>
        <strong>Vendor:</strong> {{ $po->vendor->name }} <br>
        <strong>Kontrak:</strong> {{ $po->kontrak->nomor_kontrak }} <br>
        <strong>Tanggal:</strong> {{ $po->tanggal_po }}
    </div>

    <div class="items" style="margin-top:10px;">
        <table>
            <thead> ... same as above ... </thead>
            <tbody>
                @foreach($po->items as $i => $it)
                <tr> ... </tr>
                @endforeach
                <tr> ... total ... </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top:40px;">
        <table style="width:100%;">
            <tr>
                <td style="width:50%; text-align:center;">
                    PPK / Admin<br><br><br>
                    (_____________________)
                </td>
                <td style="width:50%; text-align:center;">
                    Vendor<br><br>
                    <img src="{{ $sigBase64 }}" style="width:200px; height:auto; border:0;" alt="signature" />
                    <div style="font-size:11px; margin-top:6px;">Signed at: {{ $po->signed_at ?? now() }}</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
