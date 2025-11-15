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
        Schema::create('data_peralatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('merk');
            $table->string('tipe');
            $table->string('serial_number');
            $table->date('last_calibration');
            $table->date('due_calibration');
            $table->string('lokasi');
            $table->string('kondisi_alat');
            $table->string('status_alat');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_peralatans');
    }
};
