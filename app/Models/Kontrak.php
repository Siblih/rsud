<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'pengadaan_id',
        'nomor_kontrak',
        'nilai_kontrak',
        'tanggal_kontrak',
        'status',
        'file_kontrak',
        'po_signed',
        'bast_signed',
        'invoice',
        'faktur_pajak',
        'surat_permohonan',
        'status_pembayaran',

    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
        

    }

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class, 'pengadaan_id');
    }
    public function purchaseOrders()
{
    return $this->hasMany(\App\Models\PurchaseOrder::class);
}
public function payments()
{
    return $this->hasMany(Payment::class);
}


}
