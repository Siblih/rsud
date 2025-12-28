<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $po->nomor_po }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }
        h3, h4 {
            margin-bottom: 6px;
            margin-top: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 6px;
            vertical-align: top;
        }
        .no-border, .no-border td {
            border: none;
        }
        .right { text-align: right; }
        .center { text-align: center; }
    </style>
</head>
<body>

{{-- =========================
A. HEADER PO
========================= --}}
<table class="no-border">
    <tr>
        <td class="center">
            <h3>PURCHASE ORDER (PO)</h3>
            <strong>{{ $po->nomor_po }}</strong>
        </td>
    </tr>
</table>

<hr>

{{-- =========================
B. IDENTITAS PARA PIHAK
========================= --}}
<h4>B. IDENTITAS PARA PIHAK</h4>
<table>
    <tr>
        <td width="25%">Nama Instansi</td>
        <td width="75%">RSUD</td>
    </tr>
    <tr>
        <td>Alamat</td>
        <td>Alamat RSUD</td>
    </tr>
    <tr>
        <td>Vendor</td>
        <td>{{ $po->vendor->name }}</td>
    </tr>
    <tr>
        <td>Alamat Vendor</td>
       <td>{{ $po->vendor->vendorProfile->alamat ?? '-' }}</td>
    </tr>
</table>

{{-- =========================
C. DETAIL PENGADAAN
========================= --}}
<h4>C. DETAIL PENGADAAN</h4>
<table>
    <tr>
        <td width="30%">Nama Paket Pengadaan</td>
        <td width="70%">{{ $po->kontrak->pengadaan->nama_pengadaan }}</td>
    </tr>
    <tr>
        <td>Nomor Kontrak</td>
        <td>{{ $po->kontrak->nomor_kontrak }}</td>
    </tr>
    <tr>
        <td>Tanggal Kontrak</td>
        <td>{{ \Carbon\Carbon::parse($po->kontrak->tanggal_kontrak)->format('d M Y') }}</td>
    </tr>
    <tr>
        <td>Sumber Dana</td>
        <td>APBD</td>
    </tr>
    <tr>
        <td>Tahun Anggaran</td>
        <td>{{ date('Y') }}</td>
    </tr>
    <tr>
        <td>Nilai Kontrak</td>
        <td>Rp {{ number_format($po->kontrak->nilai_kontrak,0,',','.') }}</td>
    </tr>
</table>

{{-- =========================
D. DAFTAR BARANG / JASA
========================= --}}
<h4>D. DAFTAR BARANG / JASA</h4>
<table>
    <thead>
        <tr class="center">
            <th width="4%">No</th>
            <th width="26%">Nama Barang / Jasa</th>
            <th width="25%">Spesifikasi Teknis</th>
            <th width="6%">Qty</th>
            <th width="7%">Satuan</th>
            <th width="16%">Harga Satuan (Rp)</th>
            <th width="16%">Subtotal (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($po->items as $i => $item)
        <tr>
            <td class="center">{{ $i+1 }}</td>
            <td>{{ $item->nama_item }}</td>
            <td>{{ $item->spesifikasi ?? '-' }}</td>
            <td class="center">{{ $item->qty }}</td>
            <td class="center">{{ $item->satuan ?? '-' }}</td>
            <td class="right">{{ number_format($item->harga,0,',','.') }}</td>
            <td class="right">{{ number_format($item->total,0,',','.') }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="6" class="right"><strong>TOTAL</strong></td>
            <td class="right"><strong>Rp {{ number_format($po->total,0,',','.') }}</strong></td>
        </tr>
    </tbody>
</table>

{{-- =========================
E. KETENTUAN PURCHASE ORDER
========================= --}}
<h4>E. KETENTUAN PURCHASE ORDER</h4>
<ol>
    <li>Vendor wajib menyerahkan barang/jasa sesuai spesifikasi teknis.</li>
    <li>Jangka waktu pelaksanaan maksimal sesuai ketentuan kontrak.</li>
    <li>Tempat pengiriman sesuai alamat instansi.</li>
    <li>Pembayaran dilakukan setelah BAST ditandatangani.</li>
    <li>Keterlambatan dikenakan sanksi sesuai kontrak.</li>
</ol>

{{-- =========================
F. PENUTUP & TANDA TANGAN
========================= --}}
<h4>F. PENUTUP</h4>
<table class="no-border" style="margin-top:40px;">
    <tr>
        <td width="50%" class="center">
            <p>Pejabat Pengadaan</p>
            <br><br><br>
            <strong>Nama PPK</strong><br>
            NIP. 19XXXXXXXX
        </td>
        <td width="50%" class="center">
            <p>Vendor</p>
            <br><br><br>
            <strong>{{ $po->vendor->name }}</strong>
        </td>
    </tr>
</table>

</body>
</html>
