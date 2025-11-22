<?php

namespace App\Services\ProjectExecutionSheet;

/**
 * Class ProjectStatusService.
 */
class ProjectStatusService
{
    public function handle($code)
    {
        $statusMap = [
            // Tahap 1
            100 => ['status' => 'Draft', 'progress' => 'Drafting oleh staff', 'class' => 'bg-gradient-warning'],
            101 => ['status' => 'Draft', 'progress' => 'Menunggu Approval dari Marketing', 'class' => 'bg-gradient-warning'],
            102 => ['status' => 'Draft', 'progress' => 'Approved by Marketing', 'class' => 'bg-gradient-warning'],
            111 => ['status' => 'Draft', 'progress' => 'Menunggu Approval dari T&O', 'class' => 'bg-gradient-warning'],
            112 => ['status' => 'Draft', 'progress' => 'Approved by T&O', 'class' => 'bg-gradient-warning'],
            // Tahap 2
        ];
    
        // if code not found → default
        $status = $statusMap[$code] ?? ['status' => 'Unknown', 'progress' => 'Unknown', 'class' => 'bg-gradient-secondary'];
    
        return '
            <div class="text-center">
                <button type="button" class="btn btn-block btn-sm '.$status['class'].'">
                    '.$status['status'].'
                </button>
                <small class="badge bg-secondary font-weight-normal"><i>'. $status['progress'] .'</i></small>
            </div>
        ';
    }
}
