<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('pengadaans', function (Blueprint $table) {
        $table->text('uraian_pekerjaan')->nullable();
        $table->string('lokasi_pekerjaan')->nullable();
        $table->string('waktu_pelaksanaan')->nullable();
    });
}

public function down(): void
{
    Schema::table('pengadaans', function (Blueprint $table) {
        $table->dropColumn([
            'uraian_pekerjaan',
            'lokasi_pekerjaan',
            'waktu_pelaksanaan'
        ]);
    });
}

};
