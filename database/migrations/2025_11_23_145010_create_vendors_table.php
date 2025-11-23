<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->uuid('id_vendor')->primary();
            $table->string('nama_vendor');
            $table->string('telp_vendor')->nullable();
            $table->text('alamat_vendor');
            $table->string('email_vendor');
            $table->string('norek_vendor')->nullable();
            $table->string('bank_vendor')->nullable();
            $table->string('nama_kontak_vendor')->nullable();
            $table->string('nohp_kontak_vendor')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
