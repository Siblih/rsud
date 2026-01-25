<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penawarans', function (Blueprint $table) {
    $table->enum('status', ['pending', 'menang', 'kalah'])
          ->default('pending')
          ->change();
});

    }

    public function down(): void
    {
        Schema::table('penawarans', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }
};
