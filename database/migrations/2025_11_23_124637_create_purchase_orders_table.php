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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->uuid('id_po')->primary();
            $table->string('no_po')->unique();
            $table->char('id_suplier', 36)->index();
            $table->dateTime('tanggal_po');
            $table->date('tanggal_dibutuhkan');
            $table->string('nik')->index();
            $table->text('deskripsi_po');
            $table->char('id_project', 36)->index()->nullable();
            $table->enum('status_po', ['pending', 'on review', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
