<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id','category_id','name','sku','description','price','unit','stock',
        'tkdn','izin_edar','lead_time_days','photos','brochure','status','reject_reason',
        'kategori','izin_bpom','sertifikat_cpob','surat_distributor','no_akl','no_akd','no_pkrt',
    'dokumen_tkdn','dokumen_garansi','dokumen_uji_coba', 'is_tkdn_sertifikat','is_umk','is_konsolidasi','is_dalam_negeri','tipe_produk','surat_penunjukan'

    ];

    protected $casts = [
        'photos' => 'array'
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id'); // jika ada Category model
    }
    

}
