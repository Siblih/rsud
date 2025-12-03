<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;

class VendorNotifikasiController extends Controller
{
    public function index()
    {
        return view('vendor.notifikasi.index');
    }
}
