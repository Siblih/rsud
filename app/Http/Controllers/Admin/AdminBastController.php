<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use App\Models\Penawaran;
use App\Models\Kontrak; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminBastController extends Controller
{
    /**
     * 📦 DATA BAST (UNTUK TAB BAST)
     * ❗ BUKAN HALAMAN SENDIRI
     * ❗ DIPAKAI OLEH ADMIN PENGADAAN TAB
     */
    public function getBastList()
    {
        return Pengadaan::with([
                'unit',
                'penawarans' => function ($q) {
                    $q->where('status', 'menang');
                },
                'penawarans.vendor.vendorProfile'
            ])
            ->where('metode_pengadaan', 'kompetisi')
            ->whereHas('penawarans', function ($q) {
                $q->where('status', 'menang');
            })
            ->latest()
            ->get();
    }
public function show($id)
{
    $kontrak = Kontrak::with(['pengadaan'])->findOrFail($id);

    $fields = [
        'bast_file'          => 'Berita Acara Serah Terima (BAST)',
        'invoice'            => 'Invoice',
        'kwitansi'           => 'Kwitansi',
        'faktur_pajak'       => 'Faktur Pajak',
        'surat_jalan'        => 'Surat Jalan',
    ];

    return view('admin.bast.upload', compact(
        'kontrak',
        'fields'
    ));
}


    /**
     * 🔁 INDEX (JIKA DIAKSES LANGSUNG, REDIRECT KE TAB)
     */
    public function index()
    {
        return redirect()->route('admin.pengadaan.index', [
            'tab' => 'BAST'
        ]);
    }

    /**
     * 🔍 SHOW BAST (HALAMAN DETAIL)
     */
    public function upload(Request $request, $id)
    {
        $kontrak = Kontrak::findOrFail($id);

        // Validasi form upload (PERSIS)
        $request->validate([
            'po_signed'        => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'bast_signed'      => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'invoice'          => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'faktur_pajak'     => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'surat_permohonan' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $fields = [
            'po_signed',
            'bast_signed',
            'invoice',
            'faktur_pajak',
            'surat_permohonan'
        ];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {

                if ($kontrak->$field) {
                    Storage::disk('public')->delete($kontrak->$field);
                }

                $path = $request->file($field)
                    ->store("kontrak/{$kontrak->id}", 'public');

                $kontrak->$field = $path;
            }
        }

        $kontrak->status_pembayaran = 'diproses';
        $kontrak->save();

        return redirect()
            ->route('admin.bast.show', $kontrak->id)
            ->with('success', 'Dokumen pembayaran berhasil diupload!');
    }

}
