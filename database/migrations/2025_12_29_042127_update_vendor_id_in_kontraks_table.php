<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kontraks', function (Blueprint $table) {
            // Hapus foreign key lama
            $table->dropForeign(['vendor_id']);

            // Ubah reference ke vendor_profiles
            $table->foreign('vendor_id')
                  ->references('id')
                  ->on('vendor_profiles')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('kontraks', function (Blueprint $table) {
            // Hapus foreign key vendor_profiles
            $table->dropForeign(['vendor_id']);

            // Kembalikan ke users
            $table->foreign('vendor_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
