<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

class VendorRiwayatController extends Controller
{
    public function index()
    {
        return view('vendor.riwayat.index');
    }
}
