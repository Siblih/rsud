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
        Schema::table('po_items', function (Blueprint $table) {
    $table->string('spesifikasi')->nullable()->after('nama_item');
    $table->string('satuan')->nullable()->after('qty');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_items', function (Blueprint $table) {
            //
        });
    }
};
