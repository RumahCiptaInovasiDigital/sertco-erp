<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('project_sheet_approvals', function (Blueprint $table) {
            // Add new safe columns if they don't exist
            if (!Schema::hasColumn('project_sheet_approvals', 'disetujui_to')) {
                $table->boolean('disetujui_to')->default(false)->after('note_mkt');
            }
            if (!Schema::hasColumn('project_sheet_approvals', 'ditolak_to')) {
                $table->boolean('ditolak_to')->default(false)->after('disetujui_to');
            }
            if (!Schema::hasColumn('project_sheet_approvals', 'response_to_at')) {
                $table->dateTime('response_to_at')->nullable()->after('ditolak_to');
            }
            if (!Schema::hasColumn('project_sheet_approvals', 'response_to_by')) {
                $table->uuid('response_to_by')->nullable()->after('response_to_at');
            }
            if (!Schema::hasColumn('project_sheet_approvals', 'note_to')) {
                $table->text('note_to')->nullable()->after('response_to_by');
            }
        });

        // If original columns named with ampersand exist, try copying their values, then drop them.
        // Use raw queries and backticks to reference weird column names safely.
        try {
            $has_weird = DB::select("SHOW COLUMNS FROM `project_sheet_approvals` LIKE 'disetujui_t&o'");
            if (!empty($has_weird)) {
                // Copy values from weird columns to new safe columns
                DB::statement("
                    UPDATE `project_sheet_approvals` SET
                        `disetujui_to` = IFNULL(`disetujui_t&o`, 0),
                        `ditolak_to` = IFNULL(`ditolak_t&o`, 0),
                        `response_to_at` = `response_t&o_at`,
                        `response_to_by` = `response_t&o_by`,
                        `note_to` = `note_t&o`
                ");
                // Drop old weird columns (if you want to keep them, comment next lines)
                Schema::table('project_sheet_approvals', function (Blueprint $table) {
                    $table->dropColumn(['disetujui_t&o','ditolak_t&o','response_t&o_at','response_t&o_by','note_t&o']);
                });
            }
        } catch (\Throwable $e) {
            // If any DB engine rejects weird names, skip destructive operations and log
            // You may want to inspect DB manually in that case.
            \Log::warning('Could not automatically migrate weird t&o columns: '.$e->getMessage());
        }
    }

    public function down(): void
    {
        Schema::table('project_sheet_approvals', function (Blueprint $table) {
            $table->dropColumn(['disetujui_to','ditolak_to','response_to_at','response_to_by','note_to']);
        });
    }
};
