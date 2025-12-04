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
            $table->boolean('disetujui_mkt')->default(false);
            $table->boolean('ditolak_mkt')->default(false);
            $table->dateTime('response_mkt_at')->nullable();
            $table->uuid('response_mkt_by')->nullable();
            $table->text('note_mkt')->nullable();
            $table->boolean('disetujui_t&o')->default(false);
            $table->boolean('ditolak_t&o')->default(false);
            $table->dateTime('response_t&o_at')->nullable();
            $table->uuid('response_t&o_by')->nullable();
            $table->text('note_t&o')->nullable();
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
