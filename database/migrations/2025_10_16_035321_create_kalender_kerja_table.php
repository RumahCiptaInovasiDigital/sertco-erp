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
        Schema::create('kalender_kerja', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->string('deskripsi');
            $table->enum('tipe', ['libur', 'kegiatan', 'info'])->default('info');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kalender_kerja');
    }
};