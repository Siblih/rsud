<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengadaan;
use App\Models\Kontrak;
use App\Models\PurchaseOrder;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengadaanAdminController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'paket');

        if ($activeTab === 'paket') {
            $pengadaans = Pengadaan::with('unit', 'penawarans.vendor.vendorProfile')
                ->when($request->status, fn($q)=>$q->where('status',$request->status))
                ->when($request->nama, fn($q)=>$q->where('nama_pengadaan','like','%'.$request->nama.'%'))
                ->when($request->unit, fn($q)=>$q->whereHas('unit', fn($u)=>$u->where('name','like','%'.$request->unit.'%')))
                ->latest()
                ->get();

            return response()->json($pengadaans);
        }

        if ($activeTab === 'kontrak') {
            $kontraks = Kontrak::with(['pengadaan','vendor.vendorProfile'])->latest()->get();
            return response()->json($kontraks);
        }

        if ($activeTab === 'po') {
            $poList = PurchaseOrder::with(['kontrak.pengadaan','vendor'])->latest()->get();
            return response()->json($poList);
        }

        if ($activeTab === 'BAST') {
            $bastList = Pengadaan::with(['unit','penawarans'=>fn($q)=>$q->where('status','menang')->with('vendor.vendorProfile'),'kontraks.purchaseOrders'])
                ->where('metode_pengadaan','kompetisi')
                ->whereHas('penawarans', fn($q)=>$q->where('status','menang'))
                ->latest()->get();

            return response()->json($bastList);
        }

        return response()->json([]);
    }

    public function show($id)
    {
        $pengadaan = Pengadaan::with(['unit','kontraks.vendor','kontraks.purchaseOrders'])->findOrFail($id);
        return response()->json($pengadaan);
    }

    public function setPemenang($penawaranId)
    {
        $penawaran = Penawaran::with('pengadaan')->findOrFail($penawaranId);

        DB::transaction(function () use ($penawaran) {
            Penawaran::where('pengadaan_id', $penawaran->pengadaan_id)->update(['status'=>'kalah']);
            $penawaran->update(['status'=>'menang']);
            $penawaran->pengadaan->update(['status'=>'disetujui','proses'=>'selesai']);
        });

        return response()->json(['message'=>'Pemenang berhasil ditetapkan']);
    }

    public function updateStatus(Request $request, $id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->status = $request->status;
        $pengadaan->save();
        return response()->json(['message'=>'Status updated','status'=>$pengadaan->status]);
    }
}
