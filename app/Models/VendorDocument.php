<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDocument extends Model
{
    use HasFactory;

    protected $fillable = [
    'vendor_profile_id',
    'nib',
    'siup',
    'npwp',
    'akta_perusahaan',
    'domisili',
    'sertifikat_halal',
    'sertifikat_iso',
    'pengalaman',
    'document_name',
    'file_path',
];

  public function vendorProfile()
    {
        return $this->belongsTo(VendorProfile::class, 'vendor_profile_id');
    }


}
