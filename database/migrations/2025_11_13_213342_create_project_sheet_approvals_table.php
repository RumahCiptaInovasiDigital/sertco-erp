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
        Schema::create('project_sheet_approvals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_project');
            $table->uuid('request_by');
            $table->string('response_by')->nullable();
            $table->timestamp('response_at')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_rejected')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_sheet_approvals');
    }
};
