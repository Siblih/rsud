<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('no_pembayaran')->unique();
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);

            $table->enum('metode_bayar', [
                'transfer',
                'tunai',
                'giro'
            ]);

            $table->enum('status', [
                'pending',
                'sebagian',
                'lunas'
            ])->default('pending');

            $table->text('keterangan')->nullable();
            $table->string('bukti_bayar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};

