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
        Schema::create('peminjaman_alat_approvals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('idPeminjamanAlat');
            $table->uuid('response_by')->nullable();
            $table->string('nikPeminjam');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali')->nullable();
            $table->enum('approved', ['0', '1', '2'])->default('0');
            $table->string('catatan_approved')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_alat_approvals');
    }
};
