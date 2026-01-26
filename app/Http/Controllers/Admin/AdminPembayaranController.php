<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AdminPembayaranController extends Controller
{
    /**
     * =========================
     * LIST PEMBAYARAN (TAB ADMIN)
     * =========================
     */
    public function index()
    {
        $pembayaranList = PurchaseOrder::with([
                'kontrak.pengadaan',
                'vendor.vendorProfile',
                'pembayaran'
            ])
            ->whereHas('pembayaran') // hanya PO yg ada invoice/vendor request
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('pembayaranList'));
    }

    /**
     * =========================
     * DETAIL PEMBAYARAN
     * =========================
     */
    public function show($id)
    {
        $po = PurchaseOrder::with([
                'kontrak.pengadaan',
                'vendor.vendorProfile',
                'pembayaran'
            ])->findOrFail($id);

        return view('admin.pembayaran.show', compact('po'));
    }

    /**
     * =========================
     * UPLOAD BUKTI PEMBAYARAN
     * =========================
     */
    public function uploadBukti(Request $request, PurchaseOrder $po)
    {
        $request->validate([
            'bukti_bayar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request, $po) {

            // upload file
            $path = $request->file('bukti_bayar')
                ->store('bukti-pembayaran', 'public');

            // buat / update pembayaran
            $pembayaran = $po->pembayaran;

if (!$pembayaran) {

    $tahun = now()->year;
    $bulan = now()->format('m');

    $lastNumber = Pembayaran::whereYear('created_at', $tahun)
        ->count() + 1;

    $noPembayaran = sprintf(
        'PAY/%s/%s/%04d',
        $tahun,
        $bulan,
        $lastNumber
    );

   $jumlahBayar =
    $po->total_harga
    ?? $po->total
    ?? $po->nilai_kontrak
    ?? 0;

$pembayaran = Pembayaran::create([
    'purchase_order_id' => $po->id,
    'no_pembayaran'     => $noPembayaran,
    'tanggal_bayar'     => Carbon::now(),
    'jumlah_bayar'      => $jumlahBayar,
    'status'            => 'pending',
]);


}


            // hapus file lama kalau ada
            if ($pembayaran->bukti_bayar) {
                Storage::disk('public')->delete($pembayaran->bukti_bayar);
            }

            $pembayaran->update([
                'bukti_bayar'   => $path,
                'status'        => 'lunas',
                'tanggal_bayar' => now(),
            ]);

            // OPTIONAL: update status kontrak
            if ($po->kontrak) {
                $po->kontrak->update([
                    'status_pembayaran' => 'dibayar'
                ]);
            }
        });

        return back()->with('success', 'Bukti pembayaran berhasil diupload & pembayaran diselesaikan');
    }

    /**
     * =========================
     * DOWNLOAD BUKTI PEMBAYARAN
     * =========================
     */
    public function downloadBukti($id)
{
    $pembayaran = Pembayaran::findOrFail($id);

    $path = storage_path('app/public/' . $pembayaran->bukti_bayar);

    return response()->download($path);
}

    /**
     * =========================
     * HAPUS PEMBAYARAN (OPTIONAL)
     * =========================
     */
    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        if ($pembayaran->bukti_bayar) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        $pembayaran->delete();

        return back()->with('success', 'Data pembayaran berhasil dihapus');
    }
}
