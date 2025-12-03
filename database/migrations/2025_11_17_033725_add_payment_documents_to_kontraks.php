<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentDocumentsToKontraks extends Migration
{
    public function up()
    {
        Schema::table('kontraks', function (Blueprint $table) {

            $table->string('po_signed')->nullable(); // PO yang sudah ditandatangani
            $table->string('bast_signed')->nullable(); // BAST ditandatangani vendor & RSUD
            $table->string('invoice')->nullable(); 
            $table->string('faktur_pajak')->nullable();
            $table->string('surat_permohonan')->nullable();

            $table->string('status_pembayaran')->default('menunggu'); 
        });
    }

    public function down()
    {
        Schema::table('kontraks', function (Blueprint $table) {
            $table->dropColumn([
                'po_signed',
                'bast_signed',
                'invoice',
                'faktur_pajak',
                'surat_permohonan',
                'status_pembayaran'
            ]);
        });
    }
}
