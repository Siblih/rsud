<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');            // user/vendor owner
            $table->unsignedBigInteger('category_id')->nullable(); // kategori (relasi opsional)
            $table->string('name');
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->string('unit')->nullable();                 // satuan, ex: pcs/box
            $table->integer('stock')->nullable();
            $table->integer('tkdn')->nullable();                // persentase TKDN
            $table->string('izin_edar')->nullable();            // nomor izin edar / dokumen
            $table->integer('lead_time_days')->nullable();
            $table->json('photos')->nullable();                 // array of paths
            $table->string('brochure')->nullable();             // pdf path
            $table->enum('status', ['pending','verified','rejected'])->default('pending');
            $table->text('reject_reason')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            // category foreign key optional
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
