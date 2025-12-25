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
        Schema::create('resume_presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('karyawan_id')->constrained('data_karyawans', "id")->onDelete('cascade');
            $table->string('periode')->nullable();
            $table->integer('total_hari')->nullable();

            // Status presensi
            $table->integer('total_good')->default(0)->comment('Tepat waktu / hadir normal');
            $table->integer('total_late')->default(0)->comment('Terlambat');
            $table->integer('total_absent')->default(0)->comment('Alpha / tidak hadir');
            $table->integer('total_leave')->default(0)->comment('Cuti');
            $table->integer('total_sick')->default(0)->comment('Sakit');
            $table->integer('total_onduty')->default(0)->comment('Tugas luar / dinas');
            $table->integer('total_overtime')->default(0)->comment('Lembur');
            $table->integer('total_uncompleted')->default(0)->comment('Presensi tidak lengkap');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_presensi');
    }
};
