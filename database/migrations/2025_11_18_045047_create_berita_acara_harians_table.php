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
        Schema::create('berita_acara_harians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('karyawan_id')->constrained('data_karyawans')->onDelete('cascade');
            $table->date('tanggal');
            $table->text('uraian_kegiatan')->nullable();
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('lokasi',255)->nullable();
            $table->string('hasil_yang_dicapai')->nullable();
            $table->string("path_file_lampiran")->nullable();
            $table->string("origin_filename")->nullable();
            $table->string("mimetype")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara_harians');
    }
};
