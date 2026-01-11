<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorProfileController extends Controller
{
    public function show(Request $request)
    {
        $vendor = $request->user()->vendorProfile()->with('vendorDocuments')->first();

        return response()->json($vendor);
    }

    public function update(Request $request)
    {
        $vendor = $request->user()->vendorProfile;

        $vendor->update($request->only([
            'company_name',
            'bidang_usaha',
            'nib',
            'siup',
            'npwp',
            'alamat',
            'contact_person',
            'phone',
            'description',
        ]));

        return response()->json([
            'message' => 'Profil berhasil diperbarui'
        ]);
    }
}
