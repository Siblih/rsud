<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM(
                'admin',
                'vendor',
                'unit',
                'ppk',
                'pejabat_pengadaan',
                'panitia_pengadaan',
                'auditor'
            ) NOT NULL DEFAULT 'vendor'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE users 
            MODIFY role ENUM('admin','vendor','unit') 
            NOT NULL DEFAULT 'vendor'
        ");
    }
};

