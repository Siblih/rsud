<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Kontrak; 
use Illuminate\Support\Facades\Auth;


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

}
