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
        Schema::create('project_sheet_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_project_sheet');
            $table->uuid('id_karyawan');
            $table->integer('progress');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_sheet_logs');
    }
};
