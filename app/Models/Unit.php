<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kepala_unit',
        'email_unit',
        'keterangan',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function pengadaans()
    {
        return $this->hasMany(Pengadaan::class,'unit_id'); // nanti kita sambungkan ke tabel pengadaan
    }
}
