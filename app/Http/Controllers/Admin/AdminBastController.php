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
    return Kontrak::with([
        'pengadaan',
        'vendor.vendorProfile'
    ])
    ->whereNotNull('bast_signed') // atau file lain
    ->latest()
    ->get();
}

public function show($id)
    {
        $kontrak = Kontrak::with([
            'pengadaan',
            'vendor.vendorProfile'
        ])->findOrFail($id);

        // ⬇️ FIELD HARUS SAMA DENGAN VENDOR
        $fields = [
            'po_signed'        => 'PO (Ditandatangani)',
            'bast_signed'      => 'BAST (Ditandatangani)',
            'invoice'          => 'Invoice',
            'faktur_pajak'     => 'Faktur Pajak',
            'surat_permohonan' => 'Surat Permohonan',
        ];

        return view('admin.bast.show', compact('kontrak', 'fields'));
    }



    /**
     * 🔁 INDEX (JIKA DIAKSES LANGSUNG, REDIRECT KE TAB)
     */
   // AdminBastController
public function index()
{
    $kontraks = Kontrak::with(['pengadaan', 'vendor.vendorProfile'])
        ->where(function ($q) {
            $q->whereNotNull('bast_signed')
              ->orWhereNotNull('invoice')
              ->orWhereNotNull('po_signed');
        })
        ->latest()
        ->get();

    return view('admin.bast.index', compact('kontraks'));
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
