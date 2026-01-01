<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Penawaran;


class Pengadaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'nama_pengadaan',
        'jenis_pengadaan',
        'jumlah',
        'satuan',
        'estimasi_anggaran',
        'spesifikasi',
        'alasan',
        'status',
        'uraian_pekerjaan',
    'lokasi_pekerjaan',
    'waktu_pelaksanaan',

    ];
    // 🔹 Relasi ke Unit (user dengan role = unit)
   public function unit()
{
    return $this->belongsTo(\App\Models\Unit::class, 'unit_id');
}



    // 🔹 Relasi ke evaluator (user dengan role = evaluator)
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // 🔹 Relasi ke vendor (vendor yang ikut tender)
    public function vendors()
    {
        return $this->belongsToMany(User::class, 'pengadaan_vendor', 'pengadaan_id', 'vendor_id')
                    ->withTimestamps();
    }
    
public function penawarans()
{
    return $this->hasMany(Penawaran::class);
}

public function kontraks()
{
    return $this->hasMany(\App\Models\Kontrak::class);
}


public function purchaseOrders()
{
    return $this->hasMany(PurchaseOrder::class);
}

}
