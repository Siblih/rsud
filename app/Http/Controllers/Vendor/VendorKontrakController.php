<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Kontrak; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class VendorKontrakController extends Controller
{
    public function index()
{
    $kontraks = Kontrak::with(['pengadaan', 'vendor'])
        ->where('vendor_id', Auth::id())
        ->latest()
        ->get();

    return view('vendor.kontrak.index', compact('kontraks'));
}


    public function show($id)
    {
        $kontrak = Kontrak::with('pengadaan')
            ->where('vendor_id', Auth::id())
            ->findOrFail($id);

        return view('vendor.kontrak.show', compact('kontrak'));
    }
public function uploadForm($id)
    {
        $vendorId = Auth::id();

        $kontrak = Kontrak::with('pengadaan')
            ->where('vendor_id', $vendorId)
            ->where('id', $id)
            ->firstOrFail();

        return view('vendor.kontrak.upload', compact('kontrak'));
    }


    /**
     * 🔹 4. Proses Upload Dokumen Pembayaran
     */
    public function upload(Request $request, $id)
    {
        $vendorId = Auth::id();

        $kontrak = Kontrak::where('vendor_id', $vendorId)
            ->where('id', $id)
            ->firstOrFail();

        // Validasi form upload
        $request->validate([
            'po_signed'        => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'bast_signed'      => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'invoice'          => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'faktur_pajak'     => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
            'surat_permohonan' => 'nullable|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        // Field yang bisa diupload
        $fields = [
            'po_signed',
            'bast_signed',
            'invoice',
            'faktur_pajak',
            'surat_permohonan'
        ];

        // Loop upload file
        foreach ($fields as $field) {
            if ($request->hasFile($field)) {

                // Hapus file lama jika ada
                if ($kontrak->$field) {
                    Storage::disk('public')->delete($kontrak->$field);
                }

                // Upload file baru
                $path = $request->file($field)
                                ->store("kontrak/{$kontrak->id}", 'public');

                $kontrak->$field = $path;
            }
        }

        // Update status pembayaran otomatis
        $kontrak->status_pembayaran = 'diproses';
        $kontrak->save();

        return redirect()
            ->route('vendor.kontrak.show', $kontrak->id)
            ->with('success', 'Dokumen pembayaran berhasil diupload!');
    }
}
