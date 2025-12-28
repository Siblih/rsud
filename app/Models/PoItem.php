<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoItem extends Model
{
    protected $fillable = [
    'po_id',
    'nama_item',
    'spesifikasi',
    'qty',
    'satuan',
    'harga',
    'total'
];


    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }
}
