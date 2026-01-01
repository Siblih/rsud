<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use App\Models\Kontrak;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;


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
        $kontraks = Kontrak::with(['pengadaan', 'vendor'])
            ->when($request->status && $activeTab === 'kontrak', fn ($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->nomor_kontrak, fn ($q) =>
                $q->where('nomor_kontrak', 'like', '%' . $request->nomor_kontrak . '%')
            )
            ->when($request->pengadaan_nama, fn ($q) =>
                $q->whereHas('pengadaan', fn ($p) =>
                    $p->where('nama_pengadaan', 'like', '%' . $request->pengadaan_nama . '%')
                )
            )
            ->when($request->vendor_nama, fn ($q) =>
                $q->whereHas('vendor', fn ($v) =>
                    $v->where('name', 'like', '%' . $request->vendor_nama . '%')
                )
            )
            ->latest()
            ->get();

        /**
         * =========================
         * TAB PURCHASE ORDER
         * =========================
         */
        $poList = PurchaseOrder::with(['kontrak', 'vendor'])
            ->latest()
            ->get();

        return view('admin.pengadaan.index', compact(
            'pengadaans',
            'kontraks',
            'poList',
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
     * UPDATE STATUS (APPROVE / REJECT)
     * =========================
     */
 public function updateStatus(Request $request, $id)
{
    $pengadaan = Pengadaan::findOrFail($id);

    $pengadaan->status = $request->status;

    if ($request->status === 'disetujui') {
        $pengadaan->keterangan = 'Disetujui oleh Admin';
    }

    if ($request->status === 'ditolak') {
        $pengadaan->keterangan = 'Ditolak oleh Admin';
    }

    $pengadaan->save();

    return back(); // langsung reload halaman
}


}
