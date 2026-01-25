<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenawaranItem extends Model
{
    protected $fillable = [
        'penawaran_id',
        'product_id',
        'qty',
        'harga',
        'total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

