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
        Schema::create('log_s_o_s', function (Blueprint $table) {
            $table->uuid('id_log_so')->primary();
            $table->char('id_so', 36)->index();
            $table->enum('status_so', ['draft', 'pending', 'on review', 'approved', 'rejected', 'finished'])->default('draft');
            $table->text('ket_log_so');
            $table->char('eksekutor', 36)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_s_o_s');
    }
};
