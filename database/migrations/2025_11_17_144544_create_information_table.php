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
        Schema::create('information', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('mime_type')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('type', ['general', 'urgent', 'reminder', 'event'])->default('general');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('color')->nullable();
            $table->foreignUuid('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information');
    }
};
