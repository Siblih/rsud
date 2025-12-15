<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'file_pdf')) {
                $table->string('file_pdf')->nullable()->after('file_po');
            }
            if (!Schema::hasColumn('purchase_orders', 'vendor_signature_path')) {
                $table->string('vendor_signature_path')->nullable()->after('file_pdf');
            }
            if (!Schema::hasColumn('purchase_orders', 'signed_by_vendor')) {
                $table->unsignedBigInteger('signed_by_vendor')->nullable()->after('vendor_signature_path');
            }
            if (!Schema::hasColumn('purchase_orders', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('signed_by_vendor');
            }
            if (!Schema::hasColumn('purchase_orders', 'revision_history')) {
                $table->json('revision_history')->nullable()->after('signed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['file_pdf','vendor_signature_path','signed_by_vendor','signed_at','revision_history']);
        });
    }
};
