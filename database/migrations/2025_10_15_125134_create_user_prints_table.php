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
        Schema::create('user_prints', function (Blueprint $table) {
            $table->uuid('id_user_print')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('model');
            $table->integer('print_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_prints');
    }
};
