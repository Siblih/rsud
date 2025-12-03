<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('vendor_profiles', function (Blueprint $table) {
        $table->text('reject_reason')->nullable()->after('verification_status');
    });
}

public function down()
{
    Schema::table('vendor_profiles', function (Blueprint $table) {
        $table->dropColumn('reject_reason');
    });
}

};
