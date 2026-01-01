<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanController extends Controller
{
    public function index()
    {
        $pengadaans = Pengadaan::where('unit_id', Auth::id())
            ->latest()
            ->get();

        return view('unit.pengadaan.index', compact('pengadaans'));
    }

    public function create()
    {
        return view('unit.pengadaan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengadaan' => 'required|string|max:255',
            'jenis_pengadaan' => 'nullable|string|max:255',

            // BARANG
            'jumlah' => 'nullable|integer',
            'satuan' => 'nullable|string|max:100',
            'spesifikasi' => 'nullable|string',

            // JASA
            'uraian_pekerjaan' => 'nullable|string',
            'lokasi_pekerjaan' => 'nullable|string|max:255',
            'waktu_pelaksanaan' => 'nullable|string|max:255',

            'estimasi_anggaran' => 'nullable|numeric',
            'alasan' => 'nullable|string',
        ]);

        $validated['unit_id'] = Auth::id();
        $validated['status']  = 'menunggu';

        Pengadaan::create($validated);

        return redirect()
            ->route('unit.pengadaan.index')
            ->with('success', 'Pengajuan pengadaan berhasil dikirim!');
    }

    public function edit($id)
    {
        $pengadaan = Pengadaan::findOrFail($id);

        if ($pengadaan->status !== 'menunggu') {
            return redirect()
                ->route('unit.pengadaan.index')
                ->with('error', 'Pengadaan tidak bisa diedit karena sudah diproses.');
        }

        return view('unit.pengadaan.edit', compact('pengadaan'));
    }

    public function update(Request $request, Pengadaan $pengadaan)
    {
        // ❗ hanya boleh edit kalau status menunggu
        if ($pengadaan->status !== 'menunggu') {
            abort(403, 'Pengadaan tidak dapat diubah');
        }

        $validated = $request->validate([
            'nama_pengadaan' => 'required|string|max:255',
            'jenis_pengadaan' => 'nullable|string',

            // BARANG
            'jumlah' => 'nullable|integer',
            'satuan' => 'nullable|string|max:100',
            'spesifikasi' => 'nullable|string',

            // JASA
            'uraian_pekerjaan' => 'nullable|string',
            'lokasi_pekerjaan' => 'nullable|string',
            'waktu_pelaksanaan' => 'nullable|string',

            'estimasi_anggaran' => 'nullable|numeric',
            'alasan' => 'nullable|string',
        ]);

        /**
         * ✅ INTI PERBAIKAN:
         * - hanya update field yang benar-benar diisi
         * - field kosong TIDAK menimpa data lama
         */
        $data = array_filter(
            $validated,
            fn ($value) => $value !== null && $value !== ''
        );

        /**
         * 🔄 Bersihkan field silang barang ↔ jasa
         */
        if (($data['jenis_pengadaan'] ?? $pengadaan->jenis_pengadaan) === 'barang') {
            $data['uraian_pekerjaan']  = null;
            $data['lokasi_pekerjaan']  = null;
            $data['waktu_pelaksanaan'] = null;
        }

        if (($data['jenis_pengadaan'] ?? $pengadaan->jenis_pengadaan) === 'jasa') {
            $data['jumlah']      = null;
            $data['satuan']      = null;
            $data['spesifikasi'] = null;
        }

        $pengadaan->update($data);

        return redirect()
            ->route('unit.pengadaan.index')
            ->with('success', 'Pengadaan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pengadaan = Pengadaan::findOrFail($id);

        if ($pengadaan->status !== 'menunggu') {
            return redirect()
                ->route('unit.pengadaan.index')
                ->with('error', 'Pengadaan tidak bisa dihapus karena sudah diproses.');
        }

        $pengadaan->delete();

        return redirect()
            ->route('unit.pengadaan.index')
            ->with('success', 'Pengadaan berhasil dihapus!');
    }
}
