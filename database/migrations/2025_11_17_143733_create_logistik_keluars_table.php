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
        Schema::create('logistik_keluars', function (Blueprint $table) {
            $table->uuid('id_logistik_keluar')->primary();
            $table->string('no_logistik_keluar')->unique();
            $table->text('keterangan');
            $table->integer('total_item')->default(0);
            $table->integer('jumlah_barang_total')->default(0);
            $table->dateTime('tanggal_keluar');
            $table->string('nik')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistik_keluars');
    }
};
