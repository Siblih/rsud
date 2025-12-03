<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendor_documents', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel vendor_profiles
            $table->unsignedBigInteger('vendor_profile_id');
            $table->foreign('vendor_profile_id')
                ->references('id')
                ->on('vendor_profiles')
                ->onDelete('cascade');

            // Dokumen utama
            $table->string('nib')->nullable();
            $table->string('siup')->nullable();
            $table->string('npwp')->nullable();
            $table->string('akta_perusahaan')->nullable();
            $table->string('domisili')->nullable();
            $table->string('sertifikat_halal')->nullable();
            $table->string('sertifikat_iso')->nullable();
            $table->string('pengalaman')->nullable();

            // Kalau kamu ingin menyimpan nama dokumen generik
            $table->string('document_name')->nullable();
            $table->string('file_path')->nullable();

            $table->timestamps();

            // Pastikan engine mendukung foreign key
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_documents');
    }
};
