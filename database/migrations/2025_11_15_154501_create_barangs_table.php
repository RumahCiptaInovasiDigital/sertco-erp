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
        Schema::create('barangs', function (Blueprint $table) {
            $table->uuid('id_barang')->primary();
            $table->string('nama_barang');
            $table->string('kode_barang');
            $table->text('deskripsi_barang');
            $table->date('tanggal_perolehan');
            $table->date('last_maintenance')->nullable();
            $table->integer('qty_barang');
            $table->enum('status_barang', ['1','2','3','4','5','6','7'])->default('1'); // 1 = baik, 2 = rusak riangan, 3 =  rusak berat, 4 = digunakan, 5 = dipinjam, 6 = maintenance, 7 = hilang
            $table->enum('status_kepemilikan', ['1','2'])->default('1'); // 1 = sertco, 2 = karyawan
            $table->char('id_kategori_barang', 36);
            $table->char('id_satuan_barang', 36);
            $table->string('nik')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
