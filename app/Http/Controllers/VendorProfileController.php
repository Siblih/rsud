<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorProfile;

class VendorProfileController extends Controller
{
    public function edit()
    {
        $profile = VendorProfile::firstOrCreate(
            ['user_id' => Auth::id()],
            ['company_name' => Auth::user()->name]
        );

        return view('vendor.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'bidang_usaha' => 'nullable|string|max:255',
            'nib' => 'nullable|string|max:255',
            'siup' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $profile = VendorProfile::where('user_id', Auth::id())->first();
        $profile->update($data);

        return back()->with('success', 'Profil vendor berhasil diperbarui!');
    }
}
