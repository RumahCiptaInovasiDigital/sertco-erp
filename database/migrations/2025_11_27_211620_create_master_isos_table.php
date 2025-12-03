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
        Schema::create('master_isos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('petugas')->nullable();
            $table->date('tgl_audit')->nullable();
            $table->string('fileIso')->nullable();
            $table->string('linkIso')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_isos');
    }
};
