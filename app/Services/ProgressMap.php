<?php

namespace App\Services;

/**
 * Class ProgressMap.
 */
class ProgressMap
{
    public static function getProgressDescription($progress)
    {
        $progressDescriptions = [
            // Tahap 1
            100 => ['status' => 'Draft', 'progress' => 'Drafting oleh staff', 'class' => 'bg-gradient-warning'],
            101 => ['status' => 'Draft', 'progress' => 'Menunggu Approval dari Marketing', 'class' => 'bg-gradient-warning'],
            102 => ['status' => 'Draft', 'progress' => 'Approved by Marketing', 'class' => 'bg-gradient-warning'],
            111 => ['status' => 'Draft', 'progress' => 'Menunggu Approval dari T&O', 'class' => 'bg-gradient-warning'],
            112 => ['status' => 'Draft', 'progress' => 'Approved by T&O', 'class' => 'bg-gradient-warning'],
            // Tahap 2
        ];

        return $progressDescriptions[$progress] ?? ['status' => 'Unknown', 'progress' => 'Unknown', 'class' => 'bg-gradient-secondary'];
    }
}
