<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penawarans', function (Blueprint $table) {
            $table->string('file_penawaran')
                  ->nullable()
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('penawarans', function (Blueprint $table) {
            $table->string('file_penawaran')
                  ->nullable(false)
                  ->change();
        });
    }
};
