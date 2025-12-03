<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class VendorController extends Controller
{
    public function dataVendor()
    {
        $vendors = User::where('role', 'vendor')
            ->whereHas('vendorProfile', function ($q) {
                $q->where('verification_status', 'verified');
            })
            ->get();

        return view('admin.vendor.data_vendor', compact('vendors'));
    }

    public function detail($id)
    {
        $vendor = User::with(['vendorProfile.vendorDocuments'])
            ->where('role', 'vendor')
            ->findOrFail($id);

        // Ambil dokumen per kolom
        $documents = $vendor->vendorProfile->vendorDocuments->first();


        return view('admin.vendor.detail', compact('vendor', 'documents'));
    }

    public function documents($id)
    {
        $vendor = User::with(['vendorProfile.vendorDocuments'])
            ->findOrFail($id);

        $documents = $vendor->vendorProfile->vendorDocuments;

        return view('admin.vendor.documents', compact('vendor', 'documents'));
    }
}
