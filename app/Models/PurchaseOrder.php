<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'nomor_po',
        'kontrak_id',
        'vendor_id',
        'tanggal_po',
        'total',
        'file_po',
        'status'
    ];

    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function items()
    {
        return $this->hasMany(PoItem::class, 'po_id');
    }

    public function bast()
    {
        return $this->hasOne(Bast::class, 'po_id');
    }
}

