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
        $pengadaans = Pengadaan::with(['penawaran' => function ($q) {
                $q->where('vendor_id', auth()->id());
            }])
            ->where('status', 'disetujui')   // STATUS YANG BENAR
            ->latest()
            ->paginate(10);

        return view('vendor.pengadaan.index', compact('pengadaans'));
    }

    // 🔍 Detail pengadaan
    public function show($id)
    {
        $pengadaan = Pengadaan::where('status', 'disetujui') // filter lagi
            ->findOrFail($id);

        $penawaran = Penawaran::where('pengadaan_id', $id)
            ->where('vendor_id', Auth::id())
            ->first();

        return view('vendor.pengadaan.show', compact('pengadaan', 'penawaran'));
    }

    // 📤 Vendor mengirim penawaran
    public function submitPenawaran(Request $request, $id)
    {
        $request->validate([
            'file_penawaran' => 'required|mimes:pdf,doc,docx|max:2048',
            'harga' => 'required|numeric|min:0',
        ]);

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
