<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('bidang_usaha')->nullable();
            $table->string('nib')->nullable();
            $table->string('siup')->nullable();
            $table->string('npwp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_profiles');
    }
};
