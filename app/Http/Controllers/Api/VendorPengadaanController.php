<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use Illuminate\Http\Request;

class VendorPengadaanController extends Controller
{
    public function index(Request $request)
    {
        $pengadaans = Pengadaan::where('status', 'disetujui')
            ->select('id', 'nama_pengadaan', 'estimasi_anggaran', 'batas_pengajuan')
            ->latest()
            ->get();

        return response()->json([
            'data' => $pengadaans
        ]);

    }
}
