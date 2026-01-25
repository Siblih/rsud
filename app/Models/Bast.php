<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bast extends Model
{
    use HasFactory;

    /**
     * Nama tabel
     */
    protected $table = 'basts';

    /**
     * Field yang boleh diisi
     */
    protected $fillable = [
        'pengadaan_id',
        'penawaran_id',
        'po_id',
        'vendor_id',

        'nomor_bast',
        'tanggal_bast',

        'nilai_pekerjaan',
        'keterangan',

        'file_bast',
        'status', // draft | dikirim | disetujui | ditolak

        'dibuat_oleh',   // admin id
        'disetujui_oleh' // admin id
    ];

    /**
     * Cast otomatis
     */
    protected $casts = [
        'tanggal_bast' => 'date',
        'nilai_pekerjaan' => 'decimal:2',
    ];

    /* =====================================================
     | RELATIONSHIP
     ===================================================== */

    // 🔗 Pengadaan
    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class);
    }

    // 🔗 Penawaran MENANG
    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class);
    }

    // 🔗 Purchase Order
    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    // 🔗 Vendor
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // 🔗 Profil Vendor
    public function vendorProfile()
    {
        return $this->hasOneThrough(
            VendorProfile::class,
            User::class,
            'id',        // user.id
            'user_id',   // vendor_profiles.user_id
            'vendor_id', // basts.vendor_id
            'id'
        );
    }

    // 🔗 Admin pembuat BAST
    public function dibuatOleh()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    // 🔗 Admin penyetuju BAST
    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /* =====================================================
     | ACCESSOR (BIAR VIEW ENAK)
     ===================================================== */

    // 📌 Status Label
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft'     => 'Draft',
            'dikirim'   => 'Dikirim Vendor',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => '-',
        };
    }

    // 📌 Warna Status (Tailwind Friendly)
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'draft'     => 'gray',
            'dikirim'   => 'yellow',
            'disetujui' => 'green',
            'ditolak'   => 'red',
            default     => 'gray',
        };
    }

    // 📌 Nomor BAST fallback
    public function getNomorBastFixAttribute()
    {
        return $this->nomor_bast
            ?? 'BAST-' . date('Y') . '-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    // 📌 Nama Vendor cepat
    public function getNamaVendorAttribute()
    {
        return $this->vendorProfile->company_name ?? '-';
    }
}
