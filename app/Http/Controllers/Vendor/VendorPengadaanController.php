<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengadaan;
use App\Models\Penawaran;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;

class VendorPengadaanController extends Controller
{
    // 📦 Daftar pengadaan yang sudah disetujui
    public function index()
{
    $pengadaans = Pengadaan::with([
        'penawarans' => function ($q) {
            $q->where('vendor_id', auth()->id());
        }
    ])
    ->where('metode_pengadaan', 'kompetisi')
    ->latest()
    ->paginate(10);

    return view('vendor.pengadaan.index', compact('pengadaans'));
}


    // 🔍 Detail pengadaan
    public function show($id)
    {
        $pengadaan = Pengadaan::with(['unit'])

            // ================= TAMBAHAN LOGIKA KOMPETISI =================
           
            ->where('metode_pengadaan', 'kompetisi')
            
            // ============================================================

            ->findOrFail($id);

        // 🔹 Ambil penawaran vendor login (jika ada)
        $penawaran = Penawaran::where('pengadaan_id', $pengadaan->id)
            ->where('vendor_id', Auth::id())
            ->first();

        // 🔹 Fallback Kode Tender
        $pengadaan->kode_tender = $pengadaan->kode_tender
            ?? 'TDR-' . date('Y') . '-' . str_pad($pengadaan->id, 5, '0', STR_PAD_LEFT);

        // 🔹 Fallback Informasi Pekerjaan
        $pengadaan->uraian_pekerjaan = $pengadaan->uraian_pekerjaan
            ?? $pengadaan->nama_pengadaan;

        $pengadaan->lokasi_pekerjaan = $pengadaan->lokasi_pekerjaan
            ?? (($pengadaan->unit->name ?? '-') . ' RSUD BANGIL');

        $pengadaan->waktu_pelaksanaan = $pengadaan->waktu_pelaksanaan
            ?? \Carbon\Carbon::parse($pengadaan->batas_penawaran)
                ->translatedFormat('d M Y H:i');

        return view('vendor.pengadaan.show', compact('pengadaan', 'penawaran'));
    }

    // 📤 Vendor mengirim penawaran
    public function submitPenawaran(Request $request, $id)
    {
        $request->validate([
           'file_penawaran' => 'required|file|max:5120',
            'harga' => 'required|numeric|min:0',
        ]);

        // ================= TAMBAHAN CEK KOMPETISI =================
        $pengadaan = Pengadaan::where('id', $id)
            ->where('metode_pengadaan', 'kompetisi')
            ->firstOrFail();
        // ==========================================================

        // Upload file
        $path = $request->file('file_penawaran')->store('penawaran', 'public');

        Penawaran::create([
            'pengadaan_id' => $id,
            'vendor_id'    => Auth::id(),
            'file_penawaran' => $path,
            'harga'        => $request->harga,
            'status'       => 'pending',
        ]);

        return redirect()
            ->route('vendor.pengadaan.show', $id)
            ->with('success', 'Penawaran berhasil dikirim!');
    }
}
