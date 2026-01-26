<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    /**
     * =========================
     * TABLE
     * =========================
     */
    protected $table = 'pembayarans';

    /**
     * =========================
     * MASS ASSIGNMENT
     * =========================
     */
    protected $fillable = [
        'purchase_order_id',
        'no_pembayaran',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_bayar',
        'status',
        'keterangan',
        'bukti_bayar',
    ];

    /**
     * =========================
     * CASTING
     * =========================
     */
    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar'  => 'decimal:2'
    ];

    /**
     * =========================
     * RELATIONS
     * =========================
     */

    // Pembayaran → PO
    public function purchaseOrder()
{
    return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
}


    // Pembayaran → Kontrak (via PO)
    public function kontrak()
    {
        return $this->hasOneThrough(
            Kontrak::class,
            PurchaseOrder::class,
            'id',               // FK di purchase_orders
            'id',               // FK di kontraks
            'purchase_order_id',
            'kontrak_id'
        );
    }

    // Pembayaran → Pengadaan (via PO → Kontrak)
    public function pengadaan()
    {
        return $this->hasOneThrough(
            Pengadaan::class,
            Kontrak::class,
            'id',
            'id',
            'kontrak_id',
            'pengadaan_id'
        );
    }

    /**
     * =========================
     * SCOPES
     * =========================
     */

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeBelumLunas($query)
    {
        return $query->whereIn('status', ['pending', 'sebagian']);
    }
}
