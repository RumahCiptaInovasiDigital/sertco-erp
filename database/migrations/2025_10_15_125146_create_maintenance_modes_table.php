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
        Schema::create('maintenance_modes', function (Blueprint $table) {
            $table->uuid('id_maintenance')->primary();
            $table->string('compCode');
            $table->boolean('maintenance')->default(false);
            $table->string('reason')->nullable();
            $table->string('url_hris')->nullable();
            $table->integer('idle_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_modes');
    }
};
