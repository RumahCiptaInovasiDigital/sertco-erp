<?php

namespace App\Services\ProjectExecutionSheet;

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
    }
}
