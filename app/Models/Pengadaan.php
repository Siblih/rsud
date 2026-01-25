<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Penawaran;
use App\Models\PurchaseOrder;
use App\Models\User;

class Pengadaan extends Model
{
    use HasFactory;

    /* =========================
     | FILLABLE
     ========================= */
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
        'approved_at', // 🔥 sumber utama timeline
        'metode_pengadaan', 
    ];

    /* =========================
     | CASTS (WAJIB)
     ========================= */
    protected $casts = [
        'approved_at' => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /* =========================
     | RELATIONS (PUNYAMU)
     ========================= */

    // 🔹 Unit pengaju
    public function unit()
    {
        return $this->belongsTo(\App\Models\Unit::class, 'unit_id');
    }

    // 🔹 Evaluator
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // 🔹 Vendor peserta
    public function vendors()
    {
        return $this->belongsToMany(
            User::class,
            'pengadaan_vendor',
            'pengadaan_id',
            'vendor_id'
        )->withTimestamps();
    }

    // 🔹 Penawaran vendor
    public function penawarans()
    {
        return $this->hasMany(Penawaran::class);
    }

    // 🔹 Kontrak
    public function kontraks()
    {
        return $this->hasMany(\App\Models\Kontrak::class);
    }

    // 🔹 Purchase Order
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /* =========================
     | BUSINESS LOGIC (INTI)
     ========================= */

    /**
     * 🔥 Tanggal dasar timeline
     * Prioritas:
     * 1. approved_at
     * 2. created_at
     */
    public function getBaseTanggalAttribute()
    {
        return $this->approved_at ?? $this->created_at;
    }

    /**
     * 🔥 Batas penawaran
     * = 7 hari setelah approve
     */
    public function getBatasPenawaranAttribute()
    {
        if (!$this->base_tanggal) {
            return null;
        }

        return $this->base_tanggal->copy()->addDays(7);
    }

    /**
     * 🔥 Waktu pelaksanaan
     * = 3 hari setelah penawaran ditutup
     * (total 10 hari dari approve)
     */
    public function getWaktuPelaksanaanFixAttribute()
    {
        if (!$this->base_tanggal) {
            return null;
        }

        return $this->base_tanggal->copy()->addDays(10);
    }

    /**
     * 🔥 Kode tender fallback (konsisten WEB & API)
     */
    public function getKodeTenderFixAttribute()
    {
        return $this->kode_tender
            ?? 'TDR-' . date('Y') . '-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }
    public function penawaran()
{
    return $this->hasMany(Penawaran::class);
}

}
