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
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('comment_id');
            $table->uuid('id_user');
            $table->timestamps();

            $table->unique(['comment_id', 'id_user']);

            $table->foreign('comment_id')
                ->references('id')->on('project_sheet_notes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_likes');
    }
};
