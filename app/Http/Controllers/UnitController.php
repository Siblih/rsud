<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengadaan;

class UnitController extends Controller
{
    public function dashboard()
{
    $unit = Auth::user(); 
    $pengadaans = Pengadaan::where('unit_id', $unit->id)->latest()->get();


    $total = $pengadaans->count();
    $menunggu = $pengadaans->where('status', 'menunggu')->count();
    $disetujui = $pengadaans->where('status', 'disetujui')->count();
    $ditolak = $pengadaans->where('status', 'ditolak')->count();

    return view('unit.dashboard', compact('unit', 'pengadaans', 'total', 'menunggu', 'disetujui', 'ditolak'));
}

}
