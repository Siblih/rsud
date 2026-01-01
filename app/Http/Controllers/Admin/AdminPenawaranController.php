<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPenawaranController extends Controller
{
    // 📋 LIST PENGADAAN YANG ADA PENAWARAN
    public function index()
    {
        $pengadaans = Pengadaan::whereHas('penawarans')
            ->withCount('penawarans')
            ->latest()
            ->get();

        return view('admin.penawaran.index', compact('pengadaans'));
    }

    // 🔍 DETAIL PENAWARAN VENDOR
    public function show(Pengadaan $pengadaan)
    {
        $pengadaan->load('penawarans.vendor');

        return view('admin.penawaran.show', compact('pengadaan'));
    }

    // 🏆 SET PEMENANG
    public function setPemenang(Penawaran $penawaran)
    {
        DB::transaction(function () use ($penawaran) {

            // Semua penawaran di pengadaan ini → kalah
            Penawaran::where('pengadaan_id', $penawaran->pengadaan_id)
                ->update(['status' => 'rejected']);

            // Penawaran terpilih → menang
            $penawaran->update(['status' => 'menang']);

            // Pengadaan → selesai
            $penawaran->pengadaan->update(['status' => 'selesai']);
        });

        return back()->with('success', 'Pemenang berhasil ditetapkan');
    }
}
