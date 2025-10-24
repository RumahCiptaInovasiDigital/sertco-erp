<?php

namespace App\Services\System;

use App\Models\ActivityLog;

/**
 * Class LogActivityService.
 */
class LogActivityService
{
    public function handle(array $data)
    {
        // Pastikan array memiliki key yang diharapkan
        $perusahaan = $data['perusahaan'] ?? 'Unknown';
        $user = $data['user'] ?? 'Unknown';
        $tindakan = $data['tindakan'] ?? 'Unknown';
        $catatan = $data['catatan'] ?? '';
        $new_data = $data['new_data'] ?? null;
        $last_data = $data['last_data'] ?? null;

        // Simpan ke database
        ActivityLog::create([
            'perusahaan' => $perusahaan,
            'user' => $user,
            'tindakan' => $tindakan,
            'catatan' => $catatan,
            'new_data' => $new_data,
            'last_data' => $last_data,
            'waktu' => now(),
        ]);
    }
}
