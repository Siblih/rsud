<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // kategori produk: obat, alkes, umum
            $table->string('kategori')->default('umum'); 

            // khusus OBAT
            $table->string('izin_bpom')->nullable();          // Nomor NIE BPOM
            $table->string('sertifikat_cpob')->nullable();    // Sertifikat CPOB
            $table->string('surat_distributor')->nullable();  // Surat penunjukan distributor

            // khusus ALKES
            $table->string('no_akl')->nullable();             // AKL
            $table->string('no_akd')->nullable();             // AKD
            $table->string('no_pkrt')->nullable();            // PKRT
            $table->string('dokumen_tkdn')->nullable();       // File TKDN PDF
            $table->string('dokumen_garansi')->nullable();    // Garansi PDF
            $table->string('dokumen_uji_coba')->nullable();   // Uji coba RSUD

            // khusus UMUM
            $table->string('surat_penunjukan')->nullable();   // SPB / LOA
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'kategori','izin_bpom','sertifikat_cpob','surat_distributor',
                'no_akl','no_akd','no_pkrt','dokumen_tkdn','dokumen_garansi','dokumen_uji_coba',
                'surat_penunjukan'
            ]);
        });
    }
};
