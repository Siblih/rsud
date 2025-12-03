<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bast extends Model
{
    protected $fillable = [
        'po_id',
        'file_bast',
        'tanggal_bast',
        'catatan'
    ];

    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }
}
