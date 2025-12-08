<?php

namespace App\Services\ProjectExecutionSheet;

use App\Models\ProjectSheet;
use App\Models\ProjectSheetApproval;

/**
 * Class ProjectProgressService.
 */
class ProjectProgressService
{
    public function updateProgress(ProjectSheet $project, ProjectSheetApproval $approval, string $role, bool $isApprove)
    {
        // === MARKETING ===
        if ($role === 'mkt') {

            if ($isApprove) {

                $this->setProgress($project, 102);

                $this->setProgress($project, 111);

            } else {

                // Marketing reject → kembali ke draft (100)
                $this->setProgress($project, 100);

                // Reset T&O
                $approval->disetujui_to = false;
                $approval->ditolak_to = false;
                $approval->response_to_at = null;
                $approval->response_to_by = null;
                $approval->note_to = null;
                $approval->save();
            }
        }

        // === T&O ===
        if ($role === 'to') {

            if (!$approval->disetujui_mkt) {
                throw new \Exception("T&O tidak bisa approve sebelum Marketing.");
            }

            if ($isApprove) {

                // T&O approve → 112
                $this->setProgress($project, 112);
                
                $this->setProgress($project, 120);

                // Marketing approved + T&O approved → next stage
                if ($approval->disetujui_mkt && $approval->disetujui_to) {
                    $this->setProgress($project, 200);
                }

            } else {

                // T&O reject → fallback ke progress 102
                $this->setProgress($project, 102);
            }
        }
    }

    private function setProgress(ProjectSheet $project, int $progress)
    {
        $project->progress = $progress;
        $project->save();
        sleep(1);

        // LOG
        (new \App\Services\ProjectExecutionSheet\CreateProjectLogService())
            ->handle($project->id_project, $progress);
    }


}
