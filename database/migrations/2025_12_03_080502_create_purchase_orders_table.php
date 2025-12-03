<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_po')->unique();
            $table->unsignedBigInteger('kontrak_id');
            $table->unsignedBigInteger('vendor_id');
            $table->date('tanggal_po');
            $table->decimal('total', 18, 2)->default(0);
            $table->string('file_po')->nullable();
            $table->string('status')->default('draft'); // draft, dikirim, selesai
            $table->timestamps();

            $table->foreign('kontrak_id')->references('id')->on('kontraks')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

