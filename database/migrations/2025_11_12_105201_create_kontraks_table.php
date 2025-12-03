<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontraks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pengadaan_id')->constrained('pengadaans')->onDelete('cascade');
            $table->string('nomor_kontrak')->unique();
            $table->decimal('nilai_kontrak', 18, 2)->nullable();
            $table->date('tanggal_kontrak')->nullable();
            $table->string('status')->default('aktif'); // aktif, selesai, dibatalkan
            $table->string('file_kontrak')->nullable(); // path PDF kontrak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontraks');
    }
};
