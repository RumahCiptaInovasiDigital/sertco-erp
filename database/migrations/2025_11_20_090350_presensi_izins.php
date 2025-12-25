<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('presensi_izins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('karyawan_id');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('jenis_izin', ['sakit', 'cuti', 'izin', 'tugas']);
            $table->text('keterangan');
            $table->string('file_pendukung')->nullable();
            $table->enum('status', ['pengajuan', 'proses', 'pending', 'disetujui', 'ditolak'])->default('pengajuan');
            $table->text('catatan_approver')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();


        });
    }

    public function down()
    {
        Schema::dropIfExists('presensi_izins');
    }
};
