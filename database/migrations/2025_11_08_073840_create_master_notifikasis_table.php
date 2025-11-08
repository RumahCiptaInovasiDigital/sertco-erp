<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_notifikasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('pesan');
            $table->enum('jenis_karyawan', ['all', 'selected'])->default('all');
            $table->enum('jenis_notifikasi', ['sekali', 'daily', 'weekly', 'monthly', 'yearly'])->default('sekali');
            $table->time('jam_notifikasi')->nullable();
            $table->string('hari_notifikasi')->nullable();
            $table->integer('tanggal_notifikasi')->nullable();
            $table->integer('bulan_notifikasi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_notifikasis');
    }
};
