<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'bidang_usaha',
        'nib',
        'siup',
        'npwp',
        'alamat',
        'contact_person',
        'phone',
        'description',
        'verification_status',
        'verified_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function vendorDocuments()
{
    return $this->hasMany(VendorDocument::class, 'vendor_profile_id');
}



}
