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
        Schema::table('project_sheet_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('project_sheet_notes', 'image_path')) {
                $table->string('image_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('project_sheet_notes', 'image_path')) {
            Schema::table('project_sheet_notes', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
    }
};
