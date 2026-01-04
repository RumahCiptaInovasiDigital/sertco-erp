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
            $table->char('id_jenis_suplier')->index();
            $table->string('nama_suplier');
            $table->text('alamat_suplier');
            $table->string('cara_bayar');
            $table->string('syarat_pembayaran');
            $table->string('nama_kontak');
            $table->string('jabatan_kontak');
            $table->string('telp_suplier')->nullable();
            $table->string('hp_suplier');
            $table->string('email_suplier');
            $table->string('web_suplier')->nullable();
            $table->string('norek_suplier');
            $table->string('bank_suplier');
            $table->string('nama_pemilik_rek');
            $table->string('cabang_bank');
            $table->string('file_cp')->nullable();
            $table->string('file_npwp')->nullable();
            $table->string('file_akta')->nullable();
            $table->string('file_siup')->nullable();
            $table->string('file_tdp')->nullable();
            $table->string('file_domisili')->nullable();
            $table->string('file_sertifikat')->nullable();
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
