<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama unit, contoh: Farmasi, Gizi, Radiologi
            $table->string('kepala_unit')->nullable(); // Nama kepala unit (optional)
            $table->string('email_unit')->nullable(); // Email unit (optional)
            $table->text('keterangan')->nullable(); // Deskripsi tugas unit
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
