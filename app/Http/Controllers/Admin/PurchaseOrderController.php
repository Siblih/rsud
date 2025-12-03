<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Kontrak;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    // List semua PO
    public function index()
    {
        $poList = PurchaseOrder::with(['kontrak', 'vendor'])
                    ->latest()
                    ->get();

        return view('admin.po.index', compact('poList'));
    }

    // Form buat PO baru
    public function create($kontrak_id)
{
    $kontrak = Kontrak::with('vendor')->findOrFail($kontrak_id);

    // Ambil PO terakhir untuk kontrak ini
    $lastPO = PurchaseOrder::where('kontrak_id', $kontrak_id)
                ->orderBy('id', 'desc')
                ->first();

    $nextNumber = $lastPO ? $lastPO->id + 1 : 1;

    // Generate nomor PO otomatis
    $nomorPO = 'PO-' . $kontrak->nomor_kontrak . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    return view('admin.po.create', compact('kontrak', 'nomorPO'));
}


    // Simpan PO baru
    public function store(Request $request, $kontrak_id)
    {
        $request->validate([
            'tanggal_po' => 'required|date',
            'file_po' => 'required|mimes:pdf,png,jpg,jpeg|max:5000'
        ]);

        $kontrak = Kontrak::findOrFail($kontrak_id);

        // ========================================
        // 1. Generate Nomor PO otomatis
        // ========================================
        $lastPO = PurchaseOrder::where('kontrak_id', $kontrak_id)
                    ->orderBy('id', 'desc')
                    ->first();

        $nextNumber = $lastPO ? $lastPO->id + 1 : 1;

        // Format nomor PO: PO-[NOMOR_KONTRAK]-0001
        $nomorPO = 'PO-' . $kontrak->nomor_kontrak . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // ========================================
        // 2. Upload file PO
        // ========================================
        $path = $request->file('file_po')->store("kontrak/{$kontrak_id}/po", 'public');

        // ========================================
        // 3. Simpan ke database
        // ========================================
        PurchaseOrder::create([
            'kontrak_id' => $kontrak_id,
            'vendor_id' => $kontrak->vendor_id,
            'nomor_po' => $nomorPO,
            'tanggal_po' => $request->tanggal_po,
            'status' => 'approved',
            'file_po' => $path
        ]);

        return redirect()->route('admin.po.index')
            ->with('success', 'Purchase Order berhasil dibuat!');
    }
}
