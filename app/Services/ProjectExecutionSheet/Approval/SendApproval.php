<?php

namespace App\Services\ProjectExecutionSheet\Approval;

use App\Models\ProjectSheetApproval;

/**
 * Class SendApproval.
 */
class SendApproval
{
    public function handle($id_project, $id_user)
    {
        ProjectSheetApproval::create([
            'id_project' => $id_project,
            'request_by' => $id_user,
        ]);
    }
}
