<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use Illuminate\Http\Request;

class VendorPengadaanController extends Controller
{
    /* =========================
     | INDEX (LIST)
     ========================= */
    public function index(Request $request)
    {
        $vendorId = $request->user()->id;

        $pengadaans = Pengadaan::with([
                'penawarans' => function ($q) use ($vendorId) {
                    $q->where('vendor_id', $vendorId);
                }
            ])
            ->where('status', 'disetujui')
            ->orderBy('approved_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_paket' => $item->nama_pengadaan ?? '-',
                    'kode_tender' => $item->kode_tender_fix,
                    'status' => $item->status ?? '-',

                    // 🔥 HASIL DARI MODEL (BUKAN HITUNG ULANG)
                    'batas_penawaran' => optional($item->batas_penawaran)
                        ?->toDateTimeString(),

                    'estimasi_anggaran' => (float) ($item->estimasi_anggaran ?? 0),

                    'penawaran' => $item->penawarans->first()
                        ? [
                            'status' => $item->penawarans->first()->status,
                        ]
                        : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $pengadaans,
        ]);
    }

    /* =========================
     | SHOW (DETAIL)
     ========================= */
    public function show(Request $request, $id)
    {
        $vendorId = $request->user()->id;

        $pengadaan = Pengadaan::with([
                'unit',
                'penawarans' => function ($q) use ($vendorId) {
                    $q->where('vendor_id', $vendorId);
                }
            ])
            ->where('status', 'disetujui')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pengadaan->id,
                'nama_pengadaan' => $pengadaan->nama_pengadaan ?? '-',
                'kode_tender' => $pengadaan->kode_tender_fix,
                'status' => $pengadaan->status ?? '-',
                'unit' => $pengadaan->unit->name ?? '-',

                'estimasi_anggaran' => (float) ($pengadaan->estimasi_anggaran ?? 0),

                'uraian_pekerjaan' =>
                    $pengadaan->uraian_pekerjaan
                    ?? $pengadaan->nama_pengadaan
                    ?? '-',

                'lokasi_pekerjaan' =>
                    $pengadaan->lokasi_pekerjaan
                    ?? (($pengadaan->unit->name ?? '-') . ' RSUD BANGIL'),

                // 🔥 DARI MODEL (FINAL)
               'batas_penawaran' => optional($pengadaan->batas_penawaran)?->toISOString(),
'waktu_pelaksanaan' => optional($pengadaan->waktu_pelaksanaan_fix)?->toISOString(),



                'penawaran' => $pengadaan->penawarans->first()
                    ? [
                        'status' => $pengadaan->penawarans->first()->status,
                        'harga' => (float) $pengadaan->penawarans->first()->harga,
                        'file' => $pengadaan->penawarans->first()->file_penawaran,
                    ]
                    : null,
            ],
        ]);
    }
}
