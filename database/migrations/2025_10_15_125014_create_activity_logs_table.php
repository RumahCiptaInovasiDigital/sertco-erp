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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id_log')->primary();
            $table->string('perusahaan');
            $table->string('user');
            $table->string('tindakan'); // Create, Update, Delete
            $table->text('catatan')->nullable();
            $table->json('new_data')->nullable();
            $table->json('last_data')->nullable();
            $table->timestamp('waktu')->useCurrent();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
