<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengadaan_id',
        'vendor_id',
        'file_penawaran',
        'harga',
        'status',
    ];

    // Relasi ke tabel pengadaan
    public function pengadaan()
{
    return $this->belongsTo(Pengadaan::class, 'pengadaan_id');
}


    // Relasi ke user (vendor)
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
