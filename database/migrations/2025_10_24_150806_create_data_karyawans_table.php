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
        Schema::create('data_karyawans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('nik')->unique();
            $table->string('name');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('employeetypegroup');
            $table->date('joindate');
            $table->date('empdatestart');
            $table->date('empdateend');
            $table->string('jabatan_id');
            $table->string('departemen_id');
            $table->string('email');
            $table->string('handphone');
            $table->integer('superiornik');
            $table->string('superiorname');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_karyawans');
    }
};
