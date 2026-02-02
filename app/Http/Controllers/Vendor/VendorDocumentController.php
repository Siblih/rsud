<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Models\VendorDocument;
use App\Models\VendorProfile;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class VendorDocumentController extends Controller
{
    public function create()
    {
        $vendorProfile = VendorProfile::where('user_id', Auth::id())->first();

        if (!$vendorProfile) {
            return redirect()->route('vendor.profile.edit')
                ->with('error', 'Lengkapi profil vendor terlebih dahulu.');
        }

        // Ambil dokumen yang sudah pernah diupload
        $documents = VendorDocument::where('vendor_profile_id', $vendorProfile->id)->first();

        // Sudah diverifikasi → tidak bisa ubah
        if ($vendorProfile->verification_status === 'verified') {
            return redirect()->route('vendor.documents.index')
                ->with('error', 'Akun anda sudah diverifikasi. Dokumen tidak dapat diubah lagi.');
        }

        // Kirim juga status rejected bila ditolak
        $rejected = $vendorProfile->verification_status === 'rejected';
        $reason   = $vendorProfile->reject_reason;

        return view('vendor.documents.create', compact('documents', 'rejected', 'reason'));
    }


    public function store(Request $request)
    {
        $vendorProfile = VendorProfile::where('user_id', Auth::id())->firstOrFail();

        if ($vendorProfile->verification_status === 'verified') {
            return back()->with('error', 'Akun sudah diverifikasi. Dokumen tidak dapat diubah.');
        }

        $fields = [
            'nib', 'siup', 'npwp', 'akta_perusahaan',
            'domisili', 'sertifikat_halal', 'sertifikat_iso', 'pengalaman'
        ];

        // Ambil dokumen lama bila ada
        $existing = VendorDocument::where('vendor_profile_id', $vendorProfile->id)->first();

        $data = ['vendor_profile_id' => $vendorProfile->id];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {

                // Hapus file lama
                if ($existing && $existing->$field) {
                    \Storage::disk('public')->delete($existing->$field);
                }

                // Upload baru
                $data[$field] = $request->file($field)->store('vendor_documents', 'public');
            } else {
                // Kalau tidak upload ulang → pertahankan yang lama
                if ($existing) {
                    $data[$field] = $existing->$field;
                }
            }
        }

        VendorDocument::updateOrCreate(
            ['vendor_profile_id' => $vendorProfile->id],
            $data
        );

        // Jika sebelumnya rejected → ubah ke pending
        if ($vendorProfile->verification_status === 'rejected') {
            $vendorProfile->update([
                'verification_status' => 'pending',
                'reject_reason' => null
            ]);
        }

        return redirect()->route('vendor.documents.index')
            ->with('success', 'Dokumen berhasil disimpan!');
    }


    public function index()
    {
        $vendorProfile = auth()->user()->vendorProfile;

        if (!$vendorProfile) {
            return redirect()->route('vendor.profile.edit')
                ->with('error', 'Lengkapi profil vendor terlebih dahulu.');
        }

        $documents = $vendorProfile->vendorDocuments()->first();

        return view('vendor.documents.index', compact('documents', 'vendorProfile'));
    }
}
