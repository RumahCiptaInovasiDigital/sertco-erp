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
        Schema::create('project_sheet_notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('project_no'); 
            $table->text('comment');
            $table->uuid('id_user');
    
            // komentar
            // null = top-level comment
            // diisi = reply ke komentar lain
            $table->uuid('parent_id')->nullable()->index();
        
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('parent_id')->references('id')->on('project_sheet_notes')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_sheet_notes');
    }
};
