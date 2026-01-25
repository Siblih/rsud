<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use App\Models\Pengadaan;
use App\Models\VendorProfile;

class KontrakController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        $query = Kontrak::with('pengadaan');

        if ($status) {
            $query->where('status', $status);
        }

        $kontraks = $query->latest()->get();

        return view('admin.kontrak.index', compact('kontraks', 'status'));
    }

    public function show($id)
    {
        $kontrak = Kontrak::with('pengadaan')->findOrFail($id);
        return view('admin.kontrak.show', compact('kontrak'));
    }

    /**
     * CREATE KONTRAK DARI VENDOR PEMENANG
     */
    public function create($pengadaanId)
    {
        $pengadaan = Pengadaan::findOrFail($pengadaanId);

        // Ambil penawaran pemenang
        $pemenang = Penawaran::with('vendor.vendorProfile')
            ->where('pengadaan_id', $pengadaanId)
            ->where('status', 'menang')
            ->first();

        if (!$pemenang) {
            return back()->with('error', 'Belum ada vendor pemenang');
        }

        return view('admin.kontrak.create', compact(
            'pengadaan',
            'pemenang'
        ));
    }

    /**
     * SIMPAN KONTRAK
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'pengadaan_id'    => 'required|exists:pengadaans,id',
                'vendor_id'       => 'required|exists:users,id',
                'nomor_kontrak'   => 'required|string',
                'nilai_kontrak'   => 'required|numeric',
                'tanggal_kontrak' => 'required|date',
                'status'          => 'required|string',
                'file_kontrak'    => 'nullable|file|max:5120',
            ]);

            if ($request->hasFile('file_kontrak')) {
                $data['file_kontrak'] = $request
                    ->file('file_kontrak')
                    ->store('kontrak_files', 'public');
            }

            Kontrak::create($data);

            return redirect('/admin/pengadaan?tab=kontrak')
                ->with('success', 'Kontrak berhasil dibuat');

        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getTrace());
        }
    }
}
