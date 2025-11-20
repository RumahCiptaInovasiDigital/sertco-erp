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
        Schema::create('supliers', function (Blueprint $table) {
            $table->uuid('id_suplier')->primary();
            $table->string('nama_suplier');
            $table->string('telp_suplier')->nullable();
            $table->text('alamat_suplier');
            $table->string('email_suplier');
            $table->string('norek_suplier')->nullable();
            $table->string('bank_suplier')->nullable();
            $table->string('nama_kontak')->nullable();
            $table->string('nohp_kontak')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supliers');
    }
};
