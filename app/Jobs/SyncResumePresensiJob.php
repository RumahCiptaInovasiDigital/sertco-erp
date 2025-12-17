<?php

namespace App\Jobs;

use App\Models\DataKaryawan;
use App\Models\Presensi;
use App\Models\ResumePresensi;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncResumePresensiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $periode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Set memory limit agar tidak crash jika data besar
        ini_set('memory_limit', '-1');
        set_time_limit(0);



        $startDate = Carbon::createFromFormat('Y-m', $this->periode)->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::createFromFormat('Y-m', $this->periode)->endOfMonth()->format('Y-m-d');

        Log::info("Memulai Sync Resume Presensi untuk Periode: " . $this->periode);

        // Gunakan chunkById untuk performa lebih baik jika karyawan ribuan
        DataKaryawan::chunkById(100, function ($karyawans) use ($startDate, $endDate) {

            foreach ($karyawans as $karyawan) {
                try {
                    // Hitung statistik
                    $stats = Presensi::where('data_karyawan_id', $karyawan->id)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->selectRaw("
                            count(*) as total_hari,
                            SUM(CASE WHEN status IN ('good', 'late', 'overtime', 'onduty') THEN 1 ELSE 0 END) as total_hadir,
                            SUM(CASE WHEN status = 'good' THEN 1 ELSE 0 END) as total_tepat_waktu,
                            SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as total_terlambat,
                            SUM(CASE WHEN status = 'leave' THEN 1 ELSE 0 END) as total_izin,
                            SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as total_sakit,
                            SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as total_alpha
                        ")
                        ->first();

                    // Simpan ke tabel ResumePresensi
                    ResumePresensi::updateOrCreate(
                        [
                            'karyawan_id' => $karyawan->id, // Pastikan nama kolom foreign key benar (karyawan_id atau data_karyawan_id)
                            'periode'     => $this->periode,
                        ],
                        [
                            'total_hari'        => $stats->total_hari ?? 0,
                            'total_presensi'    => $stats->total_hadir ?? 0,
                            'total_tepat_waktu' => $stats->total_tepat_waktu ?? 0,
                            'total_terlambat'   => $stats->total_terlambat ?? 0,
                            'total_izin'        => $stats->total_izin ?? 0,
                            'total_sakit'       => $stats->total_sakit ?? 0,
                            'total_alpha'       => $stats->total_alpha ?? 0,
                        ]
                    );
                } catch (\Exception $e) {
                    // Log error per karyawan agar job tidak berhenti total
                    Log::error("Gagal sync karyawan ID {$karyawan->id}: " . $e->getMessage());
                }
            }
        });

        Log::info("Selesai Sync Resume Presensi Periode: " . $this->periode);
    }
}
