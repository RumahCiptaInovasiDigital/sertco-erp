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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('notification_id');
            $table->uuid('karyawan_id');

            // Log pengiriman
            $table->timestamp('sent_at')->nullable();  // kapan dikirim
            $table->boolean('is_sent')->default(false);

            // Status baca
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('url')->nullable();

            $table->timestamps();

            $table->foreign('notification_id')->references('id')->on('master_notifikasis')->onDelete('cascade');
            $table->foreign('karyawan_id')->references('id')->on('data_karyawans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
