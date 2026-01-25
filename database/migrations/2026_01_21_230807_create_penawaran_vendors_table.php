<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penawaran_vendors', function (Blueprint $table) {
            $table->id();

            // RELASI
            $table->foreignId('pengadaan_id')
                  ->constrained('pengadaans')
                  ->cascadeOnDelete();

            $table->foreignId('vendor_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // DATA PENAWARAN
            $table->decimal('harga_penawaran', 15, 2);
            $table->text('spesifikasi')->nullable();
            $table->enum('status', ['menunggu', 'menang', 'gugur'])
                  ->default('menunggu');

            $table->timestamps();

            // 1 vendor cuma boleh 1 penawaran per pengadaan
            $table->unique(['pengadaan_id', 'vendor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penawaran_vendors');
    }
};
