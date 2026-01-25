<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use App\Models\Kontrak;
use App\Models\PurchaseOrder;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    /**
     * =========================
     * HALAMAN UTAMA ADMIN
     * =========================
     */
   public function index(Request $request)
{
    $activeTab = $request->get('tab', 'paket');

    /**
     * =========================
     * TAB PAKET
     * =========================
     */
    $pengadaans = Pengadaan::with('unit')
        ->when($request->status, fn ($q) =>
            $q->where('status', $request->status)
        )
        ->when($request->nama, fn ($q) =>
            $q->where('nama_pengadaan', 'like', '%' . $request->nama . '%')
        )
        ->when($request->unit, fn ($q) =>
            $q->whereHas('unit', fn ($u) =>
                $u->where('name', 'like', '%' . $request->unit . '%')
            )
        )
        ->latest()
        ->get();

    /**
     * =========================
     * TAB KONTRAK
     * =========================
     */
    $kontraks = Kontrak::with([
            'pengadaan',
            'vendor.vendorProfile'
        ])
        ->latest()
        ->get();

    /**
     * =========================
     * TAB PURCHASE ORDER
     * =========================
     */
    $poList = PurchaseOrder::with([
            'kontrak.pengadaan',
            'vendor'
        ])
        ->latest()
        ->get();

    /**
     * =========================
     * TAB BAST ✅ (LOGIKA BENAR)
     * =========================
     * - TANPA model/tabel BAST
     * - Ambil dari:
     *   Pengadaan -> Penawaran (MENANG) -> Kontrak -> PO
     */
    $bastList = Pengadaan::with([
            'unit',
            'penawarans' => fn ($q) =>
                $q->where('status', 'menang')
                  ->with('vendor.vendorProfile'),
            'kontraks.purchaseOrders'
        ])
        ->where('metode_pengadaan', 'kompetisi')
        ->whereHas('penawarans', fn ($q) =>
            $q->where('status', 'menang')
        )
        ->latest()
        ->get();

    return view('admin.pengadaan.index', compact(
        'pengadaans',
        'kontraks',
        'poList',
        'bastList',
        'activeTab'
    ));
}

    /**
     * =========================
     * DETAIL PENGADAAN
     * =========================
     */
    public function show($id)
    {
        $pengadaan = Pengadaan::with([
            'unit',
            'kontraks.vendor',
            'kontraks.purchaseOrders'
        ])->findOrFail($id);

        return view('admin.pengadaan.show', compact('pengadaan'));
    }

    /**
     * =========================
     * SET PEMENANG PENGADAAN
     * =========================
     */
    public function setPemenang($penawaranId)
    {
        $penawaran = Penawaran::with('pengadaan')->findOrFail($penawaranId);

        DB::transaction(function () use ($penawaran) {

            Penawaran::where('pengadaan_id', $penawaran->pengadaan_id)
                ->update(['status' => 'kalah']);

            $penawaran->update(['status' => 'menang']);

            $penawaran->pengadaan->update([
                'status' => 'disetujui',
                'proses' => 'selesai'
            ]);
        });

        return back()->with('success', 'Pemenang berhasil ditetapkan');
    }
}
