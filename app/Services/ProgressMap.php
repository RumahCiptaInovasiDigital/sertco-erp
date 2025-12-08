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
            100 => ['status' => 'Draft', 'db_status' => 'draft', 'progress' => 'Drafting oleh staff', 'class' => 'bg-gradient-warning'],
            101 => ['status' => 'Draft', 'db_status' => 'draft', 'progress' => 'Menunggu Approval dari Marketing', 'class' => 'bg-gradient-warning'],
            102 => ['status' => 'Draft', 'db_status' => 'draft', 'progress' => 'Approved by Marketing', 'class' => 'bg-gradient-warning'],
            111 => ['status' => 'Draft', 'db_status' => 'draft', 'progress' => 'Menunggu Approval dari T&O', 'class' => 'bg-gradient-warning'],
            112 => ['status' => 'Draft', 'db_status' => 'draft', 'progress' => 'Approved by T&O', 'class' => 'bg-gradient-warning'],
            120 => ['status' => 'Draft', 'db_status' => 'draft', 'progress' => 'Berhasil Disetujui', 'class' => 'bg-gradient-info'],
            // Tahap 2
            200 => ['status' => 'Proses', 'db_status' => 'progress', 'progress' => 'Silahkan Create & Submit ARS Migas, ITP, beserta lampirannya', 'class' => 'bg-gradient-info'],
        ];

        return $progressDescriptions[$progress] ?? ['status' => 'Unknown', 'progress' => 'Unknown', 'class' => 'bg-gradient-secondary'];
    }
}
