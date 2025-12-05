<?php

namespace App\Services\ProjectExecutionSheet;

use App\Models\ProjectSheet;
use App\Models\ProjectSheetLog;
use App\Services\ProgressMap;

/**
 * Class CreateProjectLogService.
 */
class CreateProjectLogService
{
    public function handle($id_project, $progress)
    {
        $statusMap = ProgressMap::getProgressDescription($progress);

        ProjectSheetLog::create([
            'id_project_sheet' => $id_project,
            'id_karyawan' => auth()->user()->id_user,
            'progress' => $progress,
            'keterangan' => $statusMap['progress'] ?? '',
        ]);

        $project = ProjectSheet::where('id_project', $id_project)->first();
        $project->status = $statusMap['db_status'] ?? '';
        $project->save();
    }
}
