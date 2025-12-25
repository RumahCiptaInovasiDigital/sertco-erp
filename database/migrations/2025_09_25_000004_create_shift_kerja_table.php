<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_kerja', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_shift');
            $table->time('jam_masuk_min');
            $table->time('jam_masuk_max');

            $table->time('jam_pulang_min');
            $table->time('jam_pulang_max');
            $table->enum('tipe',['WFO','WFA'])->default('WFO');
            $table->text('berlaku_untuk')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_kerja');
    }
};
