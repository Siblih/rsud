<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'role',      // tambahkan ini
    'unit_id',   // juga tambahkan supaya unit user bisa diassign
];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function unit()
{
    return $this->belongsTo(Unit::class);
}
// app/Models/User.php
public function vendorProfile()
{
    return $this->hasOne(VendorProfile::class, 'user_id');
}

public function vendorDocuments()
{
    return $this->hasManyThrough(
        VendorDocument::class,
        VendorProfile::class,
        'user_id',           // foreign key di vendor_profiles
        'vendor_profile_id', // foreign key di vendor_documents
        'id',                // primary key di users
        'id'                 // primary key di vendor_profiles
    );
}

public function penawarans()
{
    return $this->hasMany(Penawaran::class, 'vendor_id');
}

}
