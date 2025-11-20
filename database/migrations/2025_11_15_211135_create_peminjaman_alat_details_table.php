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
        Schema::create('peminjaman_alat_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('idPeminjaman');
            $table->uuid('idAlat');
            $table->string('merkAlat');
            $table->string('tipeAlat');
            $table->string('snAlat');
            $table->string('kondisiSebelum');
            $table->string('kondisiSesudah')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_alat_details');
    }
};
