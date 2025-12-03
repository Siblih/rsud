<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengadaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade'); // unit yg mengajukan
            $table->string('nama_pengadaan');
            $table->string('jenis_pengadaan')->nullable(); // barang / jasa
            $table->integer('jumlah')->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('estimasi_anggaran', 15, 2)->nullable();
            $table->text('spesifikasi')->nullable();
            $table->text('alasan')->nullable();
            $table->enum('status', ['draft', 'menunggu', 'disetujui', 'ditolak'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengadaans');
    }
};
