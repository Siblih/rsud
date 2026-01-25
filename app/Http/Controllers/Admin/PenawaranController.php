<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penawaran;
use App\Models\Pengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenawaranController extends Controller
{
    /**
     * ===============================
     * LIST PENAWARAN PER PENGADAAN
     * ===============================
     * Admin lihat semua penawaran vendor
     */
    public function index($pengadaanId)
    {
        $pengadaan = Pengadaan::with(['penawarans.vendor'])
            ->findOrFail($pengadaanId);

        return view('admin.penawaran.index', compact('pengadaan'));
    }

    /**
     * ===============================
     * DETAIL 1 PENAWARAN
     * ===============================
     */
    public function show($id)
    {
        $penawaran = Penawaran::with(['pengadaan', 'vendor'])
            ->findOrFail($id);

        return view('admin.penawaran.show', compact('penawaran'));
    }

    /**
     * ===============================
     * TETAPKAN PEMENANG TENDER
     * ===============================
     */
    public function tetapkanPemenang($id)
    {
        $penawaran = Penawaran::findOrFail($id);

        // Validasi pengadaan
        if ($penawaran->pengadaan->metode_pengadaan !== 'kompetisi') {
            abort(403, 'Pengadaan ini bukan metode tender');
        }

        // Gugurkan semua penawaran lain
        Penawaran::where('pengadaan_id', $penawaran->pengadaan_id)
            ->update(['status' => 'gugur']);

        // Set pemenang
        $penawaran->update(['status' => 'menang']);

        return redirect()
            ->back()
            ->with('success', 'Vendor berhasil ditetapkan sebagai pemenang');
    }

    /**
     * ===============================
     * BATALKAN PEMENANG (OPTIONAL)
     * ===============================
     */
    public function batalkanPemenang($id)
    {
        $penawaran = Penawaran::findOrFail($id);

        if ($penawaran->status !== 'menang') {
            return back()->with('error', 'Penawaran ini bukan pemenang');
        }

        $penawaran->update(['status' => 'menunggu']);

        return back()->with('success', 'Status pemenang dibatalkan');
    }

    /**
     * ===============================
     * DOWNLOAD FILE PENAWARAN
     * ===============================
     */
    public function downloadFile($id)
    {
        $penawaran = Penawaran::findOrFail($id);

        if (!$penawaran->file_penawaran || !Storage::disk('public')->exists($penawaran->file_penawaran)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download(
    storage_path('app/public/' . $penawaran->file_penawaran)
);

    }
}
