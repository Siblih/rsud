<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LogActivity;


class PengadaanController extends Controller
{
    public function index()
    {
        $pengadaans = Pengadaan::where('unit_id', Auth::id())->latest()->get();
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
            'jumlah' => 'nullable|integer',
            'satuan' => 'nullable|string|max:100',
            'estimasi_anggaran' => 'nullable|numeric',
            'spesifikasi' => 'nullable|string',
            'alasan' => 'nullable|string',
        ]);

        $validated['unit_id'] = Auth::id();
        $validated['status'] = 'menunggu';

        Pengadaan::create($validated);

        return redirect()->route('unit.pengadaan.index')->with('success', 'Pengajuan pengadaan berhasil dikirim!');
    }
    public function edit($id)
{
    $pengadaan = Pengadaan::findOrFail($id);

    if ($pengadaan->status != 'menunggu') {
        return redirect()->route('unit.pengadaan.index')
                         ->with('error', 'Pengadaan tidak bisa diedit karena sudah diproses.');
    }

    return view('unit.pengadaan.edit', compact('pengadaan'));
}

public function update(Request $request, $id)
{
    $pengadaan = Pengadaan::findOrFail($id);

    if ($pengadaan->status != 'menunggu') {
        return redirect()->route('unit.pengadaan.index')
                         ->with('error', 'Pengadaan tidak bisa diubah karena sudah diproses.');
    }

    $validated = $request->validate([
        'nama_pengadaan' => 'required|string|max:255',
        'jenis_pengadaan' => 'nullable|string|max:255',
        'jumlah' => 'nullable|integer',
        'satuan' => 'nullable|string|max:100',
        'estimasi_anggaran' => 'nullable|numeric',
        'spesifikasi' => 'nullable|string',
        'alasan' => 'nullable|string',
    ]);

    $pengadaan->update($validated);

    return redirect()->route('unit.pengadaan.index')
                     ->with('success', 'Pengadaan berhasil diperbarui!');
}

public function destroy($id)
{
    $pengadaan = Pengadaan::findOrFail($id);

    if ($pengadaan->status != 'menunggu') {
        return redirect()->route('unit.pengadaan.index')
                         ->with('error', 'Pengadaan tidak bisa dihapus karena sudah diproses.');
    }

    $pengadaan->delete();

    return redirect()->route('unit.pengadaan.index')
                     ->with('success', 'Pengadaan berhasil dihapus!');
}

}
