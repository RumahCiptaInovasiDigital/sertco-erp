<?php

namespace App\Traits;

use App\Models\ProjectSheet;

trait GenerateProjectNo
{
    public function generateProjectNo()
    {
        $year = now()->format('y'); // 2 digit tahun, misal "25"
        $prefix = "SQ-";

        // Cari nomor terakhir yang sesuai pola "SQ-%-{year}"
        $latest = ProjectSheet::where('project_no', 'like', $prefix . "%-" . $year)
            ->orderBy('project_no', 'desc')
            ->withTrashed()
            ->value('project_no');

        // Tentukan urutan berikutnya
        $lastSeq = 0;

        if ($latest) {
            // Ambil nomor urut di posisi setelah "SQ-" sampai sebelum "-yy"
            // Format: SQ-001-25 → ambil "001"
            $parts = explode('-', $latest);
            $lastSeq = isset($parts[1]) ? (int) $parts[1] : 0;
        }

        $nextSeq = str_pad($lastSeq + 1, 3, '0', STR_PAD_LEFT);

        return "SQ-{$nextSeq}-{$year}";
    }
}
