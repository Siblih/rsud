<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorDocumentController extends Controller
{
   public function show(Request $request)
    {
        // 🔥 ambil SATU dokumen
        $documents = $request->user()
            ->vendorDocuments()
            ->latest()
            ->first();

        if (!$documents) {
            return response()->json([
                'data' => null
            ], 200);
        }

        return response()->json([
    'data' => [
        'nib' => $documents->nib ? asset('storage/' . $documents->nib) : null,
        'pengalaman' => $documents->pengalaman ? asset('storage/' . $documents->pengalaman) : null,
        'siup' => $documents->siup ? asset('storage/' . $documents->siup) : null,
        'npwp' => $documents->npwp ? asset('storage/' . $documents->npwp) : null,
        'akta_perusahaan' => $documents->akta_perusahaan ? asset('storage/' . $documents->akta_perusahaan) : null,
        'domisili' => $documents->domisili ? asset('storage/' . $documents->domisili) : null,
        'sertifikat_halal' => $documents->sertifikat_halal ? asset('storage/' . $documents->sertifikat_halal) : null,
        'sertifikat_iso' => $documents->sertifikat_iso ? asset('storage/' . $documents->sertifikat_iso) : null,
    ]
], 200);

    }

}
