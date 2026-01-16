<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class VendorKontrakController extends Controller
{
    // 🔹 LIST KONTRAK VENDOR
    public function index(Request $request)
{
    $vendorId = $request->user()->id;

    $kontraks = Kontrak::with(['pengadaan'])
        ->where('vendor_id', $vendorId)
        ->latest()
        ->get();

    return response()->json([
        'success' => true,
        'data' => $kontraks->map(function ($k) {
            return [
                'id' => $k->id,
                'nama_pengadaan' => $k->pengadaan->nama_pengadaan ?? '-',
                'nomor_kontrak' => $k->nomor_kontrak ?? '-',
                'nilai_kontrak' => (float) $k->nilai_kontrak,
                'tanggal_kontrak' => optional($k->tanggal_kontrak)
                    ? $k->tanggal_kontrak->toDateString()
                    : null,

                // 🔥 PENTING
                'status' => $k->status,
                'status_pembayaran' => $k->status_pembayaran,

                // 🔥 DOKUMEN
                'po_signed' => $k->po_signed,
                'bast_signed' => $k->bast_signed,
                'invoice' => $k->invoice,
                'faktur_pajak' => $k->faktur_pajak,
                'surat_permohonan' => $k->surat_permohonan,
            ];
        }),
    ]);
}


    // 🔹 DETAIL KONTRAK
    public function show(Request $request, $id)
{
    $vendorId = $request->user()->id;

    $kontrak = Kontrak::with(['pengadaan'])
        ->where('vendor_id', $vendorId)
        ->findOrFail($id);

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $kontrak->id,
            'nomor_kontrak' => $kontrak->nomor_kontrak,
            'nilai_kontrak' => $kontrak->nilai_kontrak,
            'tanggal_kontrak' => $kontrak->tanggal_kontrak
    ? Carbon::parse($kontrak->tanggal_kontrak)->format('Y-m-d')
    : null,

            'status' => $kontrak->status,
            'status_pembayaran' => $kontrak->status_pembayaran,

            'dokumen' => [
                'po_signed' => $kontrak->po_signed,
                'bast_signed' => $kontrak->bast_signed,
                'invoice' => $kontrak->invoice,
                'faktur_pajak' => $kontrak->faktur_pajak,
                'surat_permohonan' => $kontrak->surat_permohonan,
            ],

            'pengadaan' => [
                'nama_pengadaan' => $kontrak->pengadaan->nama_pengadaan ?? '-',
                'lokasi_pekerjaan' => $kontrak->pengadaan->lokasi_pekerjaan ?? '-',
            ],
        ],
    ]);
}


    public function upload(Request $request, $id)
{
    $kontrak = Kontrak::where('vendor_id', auth()->id())
        ->where('id', $id)
        ->firstOrFail();

    $fields = [
        'po_signed',
        'bast_signed',
        'invoice',
        'faktur_pajak',
        'surat_permohonan',
    ];

    foreach ($fields as $field) {
        if ($request->hasFile($field)) {

            // hapus file lama
            if ($kontrak->$field) {
                Storage::disk('public')->delete($kontrak->$field);
            }

            // simpan file baru
            $path = $request->file($field)
                ->store("vendor_documents/kontrak_{$kontrak->id}", 'public');

            $kontrak->$field = $path;
        }
    }

    $kontrak->status_pembayaran = 'process';
    $kontrak->save();

    return response()->json([
        'success' => true,
        'message' => 'Dokumen berhasil diupload',
    ]);
}

}
