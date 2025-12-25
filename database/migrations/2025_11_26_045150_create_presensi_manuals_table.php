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
        Schema::create('presensi_manuals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('karyawan_id');
            $table->date('tanggal');
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->text('lokasi');
            $table->text('alasan')->nullable();
            $table->uuid('approved_by');
            $table->text('catatan_approver')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_manuals');
    }
};
