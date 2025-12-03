<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Kontrak;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $status      = $request->status;
        $nama        = $request->nama;            // filter nama pengadaan
        $unit        = $request->unit;            // filter unit
        $activeTab   = $request->activeTab;       // tab pengadaan/kontrak

        // =============================
        // TAB 1 : PENGADAAN
        // =============================
        $query = Pengadaan::with('unit')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($nama) {
            $query->where('nama_pengadaan', 'like', '%'.$nama.'%');
        }

        if ($unit) {
            $query->whereHas('unit', function ($q) use ($unit) {
                $q->where('name', 'like', '%'.$unit.'%');
            });
        }

        $pengadaans = $query->get();


        // =============================
        // TAB 2 : KONTRAK (dengan filter tambahan)
        // =============================
        $kontraks = Kontrak::with(['pengadaan', 'vendor'])->latest();

        // Filter status kontrak
        if ($request->status) {
            $kontraks->where('status', $request->status);
        }

        // Filter nomor kontrak
        if ($request->nomor_kontrak) {
            $kontraks->where('nomor_kontrak', 'like', '%' . $request->nomor_kontrak . '%');
        }

        // Filter nama pengadaan
        if ($request->pengadaan_nama) {
            $kontraks->whereHas('pengadaan', function ($q) use ($request) {
                $q->where('nama_pengadaan', 'like', '%' . $request->pengadaan_nama . '%');
            });
        }

        // Filter nama vendor
        if ($request->vendor_nama) {
            $kontraks->whereHas('vendor', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->vendor_nama . '%');
            });
        }

        $kontraks = $kontraks->get();
$poList = \App\Models\PurchaseOrder::with(['kontrak', 'vendor'])->latest()->get();


        return view('admin.pengadaan.index', compact(
            'pengadaans',
            'kontraks',
            'status',
            'nama',
            'poList',  
            'unit',
            'activeTab'
        ));
    }


    public function show($id)
    {
        $pengadaan = Pengadaan::with('unit')->findOrFail($id);
        return view('admin.pengadaan.show', compact('pengadaan'));
    }

    public function updateStatus(Request $request, $id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->status = $request->status;
        $pengadaan->save();

        return redirect()->route('admin.pengadaan.index')
            ->with('success', 'Status pengadaan berhasil diperbarui!');
    }
}
