<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->enum('tipe_produk', ['barang','jasa','digital'])->default('barang');
        
        // subfilter barang
        $table->boolean('is_dalam_negeri')->default(false);
        $table->boolean('is_umk')->default(false);
        $table->boolean('is_konsolidasi')->default(false);
        $table->boolean('is_tkdn_sertifikat')->default(false);

        // jasa
        $table->string('jenis_jasa')->nullable();

        // digital
        $table->string('jenis_digital')->nullable();
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn([
            'tipe_produk',
            'is_dalam_negeri',
            'is_umk',
            'is_konsolidasi',
            'is_tkdn_sertifikat',
            'jenis_jasa',
            'jenis_digital',
        ]);
    });
}

};
