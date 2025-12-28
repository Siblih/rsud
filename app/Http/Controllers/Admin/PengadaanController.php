<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Kontrak;

class PengadaanController extends Controller
{
    public function index(Request $request)
{
    // Ambil filter dari request
    $status      = $request->status;
    $nama        = $request->nama;
    $unit        = $request->unit;
    $activeTab   = $request->activeTab;

    // =============================
    // TAB 1 : PENGADAAN
    // =============================
    $query = Pengadaan::with('unit')->latest();

    if ($status) {
        $query->where('status', $status);
    }

    if ($nama) {
        $query->where('nama_pengadaan', 'like', '%'.$nama.'%');
    }

    if ($unit) {
        $query->whereHas('unit', function ($q) use ($unit) {
            $q->where('name', 'like', '%'.$unit.'%');
        });
    }

    $pengadaans = $query->get();

    // 🔹 Fix status paket sesuai kontrak yang sudah ada
    foreach ($pengadaans as $p) {
        if ($p->kontrak && $p->status != 'disetujui') {
            $p->status = 'disetujui';
            $p->save();
        }
    }

    // =============================
    // TAB 2 : KONTRAK
    // =============================
    $kontraks = Kontrak::with(['pengadaan', 'vendor'])->latest();

    if ($request->status) {
        $kontraks->where('status', $request->status);
    }
    if ($request->nomor_kontrak) {
        $kontraks->where('nomor_kontrak', 'like', '%' . $request->nomor_kontrak . '%');
    }
    if ($request->pengadaan_nama) {
        $kontraks->whereHas('pengadaan', function ($q) use ($request) {
            $q->where('nama_pengadaan', 'like', '%' . $request->pengadaan_nama . '%');
        });
    }
    if ($request->vendor_nama) {
        $kontraks->whereHas('vendor', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->vendor_nama . '%');
        });
    }

    $kontraks = $kontraks->get();

    $poList = \App\Models\PurchaseOrder::with(['kontrak', 'vendor'])->latest()->get();

    return view('admin.pengadaan.index', compact(
        'pengadaans',
        'kontraks',
        'status',
        'nama',
        'poList',  
        'unit',
        'activeTab'
    ));
}



    public function show($id)
{
    $pengadaan = Pengadaan::with([
        'unit',
        'kontraks.purchaseOrders.vendor'
    ])->findOrFail($id);

    return view('admin.pengadaan.show', compact('pengadaan'));
}


    public function updateStatus(Request $request, $id)
{
    $pengadaan = Pengadaan::findOrFail($id);
    $pengadaan->status = $request->status;
    $pengadaan->save();

    // 🔹 Jika paket disetujui dan belum punya kontrak, buat kontrak otomatis
    if ($request->status == 'disetujui' && !$pengadaan->kontrak) {
        $nomorKontrak = 'KTR-' . str_pad(Kontrak::count() + 1, 3, '0', STR_PAD_LEFT) . '/' . date('Y');
        Kontrak::create([
            'pengadaan_id' => $pengadaan->id,
            'vendor_id' => $pengadaan->vendors()->first()->id ?? null, // vendor pertama misal
            'nomor_kontrak' => $nomorKontrak,
            'status' => 'aktif',
            'nilai_kontrak' => $pengadaan->estimasi_anggaran,
            'tanggal_kontrak' => now(),
        ]);
    }

    return redirect()->route('admin.pengadaan.index')
        ->with('success', 'Status pengadaan berhasil diperbarui!');
}

}
