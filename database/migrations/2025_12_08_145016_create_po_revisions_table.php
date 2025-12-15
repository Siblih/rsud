<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('po_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('edited_by')->nullable(); // user id
            $table->text('notes')->nullable();
            $table->json('snapshot')->nullable(); // store JSON snapshot of PO (items, total, etc)
            $table->timestamps();

            $table->foreign('po_id')->references('id')->on('purchase_orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('po_revisions');
    }
};
