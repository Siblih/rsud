<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'kontrak_id',
        'nominal',
        'status',
        'invoice',
        'bast',
        'faktur_pajak',
        'surat_permohonan',
        'reject_reason',
        'submitted_at',
        'verified_at',
        'approved_at',
        'paid_at',
    ];

    /**
     * Payment milik satu Kontrak
     */
    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class);
    }
}
