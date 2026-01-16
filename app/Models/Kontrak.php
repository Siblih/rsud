<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    /**
     * CASTING (INI YANG PENTING 🔥)
     */
    protected $casts = [
        'tanggal_kontrak' => 'date',
        'nilai_kontrak' => 'decimal:2',
        'po_signed' => 'boolean',
        'bast_signed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * RELATIONSHIPS
     */
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

    /**
     * ACCESSOR (OPSIONAL TAPI KEREN)
     * Biar konsisten di API / Blade / Flutter
     */
    public function getTanggalKontrakFormattedAttribute()
    {
        return $this->tanggal_kontrak
            ? $this->tanggal_kontrak->format('d M Y')
            : '-';
    }
}
