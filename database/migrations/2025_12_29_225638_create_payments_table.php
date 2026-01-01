<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kontrak_id')
                ->constrained('kontraks')
                ->cascadeOnDelete();

            $table->decimal('nominal', 18, 2)->nullable();

            $table->string('invoice')->nullable();
            $table->string('bast')->nullable();
            $table->string('faktur_pajak')->nullable();
            $table->string('surat_permohonan')->nullable();

            $table->enum('status', [
                'draft',
                'diajukan_vendor',
                'diverifikasi_admin',
                'ditolak_admin',
                'disetujui_ppk',
                'ditolak_ppk',
                'diproses_keuangan',
                'dibayar'
            ])->default('draft');

            $table->text('reject_reason')->nullable();

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
