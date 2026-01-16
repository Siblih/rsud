<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'unit_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ================= RELATIONS =================

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

   public function vendorProfile()
{
    return $this->hasOne(VendorProfile::class, 'user_id');
}


    public function vendorDocuments()
    {
        return $this->hasManyThrough(
            VendorDocument::class,
            VendorProfile::class,
            'user_id',
            'vendor_profile_id',
            'id',
            'id'
        );
    }

    public function penawarans()
    {
        return $this->hasMany(Penawaran::class, 'vendor_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }
}
