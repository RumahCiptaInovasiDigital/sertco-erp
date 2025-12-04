<?php

namespace App\Services\ProjectExecutionSheet;

use App\Services\ProgressMap;

/**
 * Class ProjectStatusService.
 */
class ProjectStatusService
{
    public function handle($code, $remaining_time, $expired)
    {
        $statusMap = ProgressMap::getProgressDescription($code);
    
        // if code not found → default
        $status = $statusMap ?? ['status' => 'Unknown', 'progress' => 'Unknown', 'class' => 'bg-gradient-secondary'];
    
        return '
            <div class="text-center">
                <button type="button" class="btn btn-block btn-sm '.$status['class'].'" style="cursor: default;">
                    '.$status['status'].'
                </button>
                <small class="badge bg-secondary font-weight-normal"><i>'. $status['progress'] .'</i></small>
            </div>
            ';
                // <small class="badge '.($expired ? 'text-danger' : 'text-muted').'">'.($expired ? 'Expired' : $remaining_time).'</small>
    }
}
