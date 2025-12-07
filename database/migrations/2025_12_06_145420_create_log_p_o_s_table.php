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
        Schema::create('log_p_o_s', function (Blueprint $table) {
            $table->uuid('id_log_po')->primary();
            $table->char('id_po', 36)->index();
            $table->enum('status_po', ['draft', 'pending', 'on review', 'approved', 'rejected', 'finished'])->default('draft');
            $table->text('ket_log_po')->default('Draft Purchase Order dibuat');
            $table->char('eksekutor', 36)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_p_o_s');
    }
};
