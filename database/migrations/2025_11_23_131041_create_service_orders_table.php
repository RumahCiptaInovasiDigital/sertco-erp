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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->uuid('id_so')->primary();
            $table->string('no_so')->unique();
            $table->char('id_vendor', 36)->index()->nullable();
            $table->dateTime('tanggal_so');
            $table->date('tanggal_dibutuhkan');
            $table->string('nik')->index();
            $table->string('jenis_pekerjaan');
            $table->text('deskripsi_so');
            $table->integer('estimasi_biaya_jasa')->default(0);
            $table->integer('estimasi_biaya_material')->default(0);
            $table->integer('total_estimasi')->default(0);
            $table->string('file_lampiran')->nullable();
            $table->char('id_project', 36)->index()->nullable();
            $table->enum('status_so', ['draft', 'pending', 'on review', 'approved', 'rejected', 'finished'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
