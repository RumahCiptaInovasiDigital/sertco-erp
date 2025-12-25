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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_credential_nik', 50)->unique();
            $table->foreign('user_credential_nik')
                ->references('nik')->on('user_credentials')
                ->onDelete('cascade');
            $table->string('device_id', 255);
            $table->string('device_name', 255)->nullable();
            $table->string('device_type', 255)->nullable();
            $table->string('fcm_token', 255)->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->json('coordinate')->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->json('history')->nullable();
            $table->json('register_new')->nullable();
            $table->dateTime('activate_at')->nullable();
            $table->dateTime('blocked_at')->nullable();
            $table->string('reason_blocked',512)->nullable();
            $table->string('news',512)->nullable();
            $table->foreignUuid('validator_id')
                ->nullable()
                ->constrained('users', 'id_user')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};
