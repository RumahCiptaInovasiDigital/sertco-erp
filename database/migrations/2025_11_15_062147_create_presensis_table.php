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
        Schema::create('presensi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('data_karyawan_id')->constrained('data_karyawans');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->string('ip_masuk')->nullable();
            $table->enum('type_presensi', ['WFO', 'WFA'])->default('WFO');
            $table->string('device_masuk')->nullable();
            $table->string('koordinat_masuk')->nullable();
            $table->time('jam_harus_masuk_awal');
            $table->time('jam_harus_masuk_akhir');
            $table->time('jam_pulang')->nullable();
            $table->string('ip_pulang')->nullable();
            $table->string('device_pulang')->nullable();
            $table->string('koordinat_pulang')->nullable();
            $table->foreignUuid('shift_kerja_id')->constrained('shift_kerja');
            $table->time('jam_harus_pulang_awal');
            $table->time('jam_harus_pulang_akhir');
            $table->foreignUuid('origin_branchoffice_masuk_id')->nullable()->constrained('branch_offices');
            $table->foreignUuid('branchoffice_masuk_id')->nullable()->constrained('branch_offices');
            $table->foreignUuid('origin_branchoffice_pulang_id')->nullable()->constrained('branch_offices');
            $table->foreignUuid('branchoffice_pulang_id')->nullable()->constrained('branch_offices');
            $table->enum('status', ['good', 'late', 'uncompleted', 'leave', 'sick', 'absent', 'overtime', 'onduty'])->nullable();
            $table->text('keterangan')->nullable();
            $table->double('total_jam_kerja')->nullable();
            $table->timestamps();
            $table->unique(['data_karyawan_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
