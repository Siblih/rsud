<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorProfile;
use App\Http\Controllers\Controller;

class VendorProfileController extends Controller
{
    public function edit()
    {
        $profile = VendorProfile::firstOrCreate(
            ['user_id' => Auth::id()],
            ['company_name' => Auth::user()->name]
        );

        // 🚫 Jika sudah diverifikasi, redirect dengan pesan error
        if ($profile->verification_status === 'verified' || $profile->verification_status === 'disetujui') {
            return redirect()->route('vendor.profile.show')
                ->with('error', 'Profil sudah diverifikasi data tidak dapat diubah lagi.');
        }

        return view('vendor.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = VendorProfile::where('user_id', Auth::id())->firstOrFail();

        // 🚫 Cegah perubahan jika sudah diverifikasi
        if ($profile->verification_status === 'verified' || $profile->verification_status === 'disetujui') {
            return back()->with('error', 'Profil sudah diverifikasi data tidak dapat diubah lagi.');
        }

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

       $profile->update($data);

// redirect ke show profile
return redirect()->route('vendor.profile.show')
                 ->with('success', 'Profil vendor berhasil diperbarui!');

    }

    public function show()
    {
        $profile = VendorProfile::where('user_id', Auth::id())->first();

        if (!$profile || !$profile->company_name) {
            return redirect()->route('vendor.profile.edit')
                ->with('info', 'Lengkapi profil perusahaan Anda terlebih dahulu.');
        }

        return view('vendor.profile.show', compact('profile'));
    }
}
