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
        Schema::create('jadwal_kerja', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_karyawan');
            $table->text('jadwal_json')->nullable();
            $table->foreignUuid("id_shift_kerja")->nullable()->constrained("shift_kerja")->onDelete("set null");
            $table->timestamps();
            $table->foreign('id_karyawan')->references('id')->on('data_karyawans')->onDelete('cascade');
            $table->unique('id_karyawan');
            $table->text('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_kerja');
    }
};
