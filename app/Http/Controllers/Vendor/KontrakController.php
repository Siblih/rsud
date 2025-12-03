<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kontrak;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KontrakController extends Controller
{
    /**
     * 🔹 1. Tampilkan semua kontrak milik vendor login
     */
    public function index()
    {
        $vendorId = Auth::id();

        $kontraks = Kontrak::with('pengadaan')
            ->where('vendor_id', $vendorId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('vendor.kontrak.index', compact('kontraks'));
    }


    /**
     * 🔹 2. Tampilkan detail kontrak (TANPA form upload)
     */
    public function show($id)
    {
        $vendorId = Auth::id();

        $kontrak = Kontrak::with('pengadaan')
            ->where('vendor_id', $vendorId)
            ->where('id', $id)
            ->firstOrFail();

        return view('vendor.kontrak.show', compact('kontrak'));
    }


    /**
     * 🔹 3. Halaman Form Upload Dokumen Pembayaran (Halaman Terpisah)
     */
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
