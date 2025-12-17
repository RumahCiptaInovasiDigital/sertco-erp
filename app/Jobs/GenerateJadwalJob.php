<?php

namespace App\Jobs;

use App\Models\DataKaryawan;
use App\Models\ShiftKerja;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateJadwalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tanggalAwal;

    // Set timeout job agar tidak error jika lebih dari 60 detik (misal 5 menit)
    public $timeout = 300;

    /**
     * Create a new job instance.
     * Kita terima parameter tanggal dari Controller
     */
    public function __construct($tanggalAwal)
    {
        $this->tanggalAwal = $tanggalAwal;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        set_time_limit(300);
        ini_set('memory_limit', '-1');
        $start = Carbon::parse($this->tanggalAwal);

        // Ambil semua karyawan yang memiliki jadwal
        $karyawanList = DataKaryawan::whereHas('jadwalKerja')->with('jadwalKerja')->get();

        DB::beginTransaction();
        try {
            // Loop 7 Hari
            for ($i = 0; $i < 7; $i++) {
                $tanggalLoop = $start->copy()->addDays($i);
                $tanggalLoopStr = $tanggalLoop->format('Y-m-d');

                // Get day of week (0=Sunday, 1=Monday, ..., 6=Saturday)
                $dayOfWeek = $tanggalLoop->dayOfWeek;

                foreach ($karyawanList as $karyawan) {
                    if (!$karyawan->jadwalKerja) continue;

                    // Get shift for this specific day from JSON
                    $jadwalHariIni = $karyawan->jadwalKerja->getShiftForDay($dayOfWeek);

                    if (!$jadwalHariIni || !isset($jadwalHariIni['shift_id'])) {
                        // Skip if no shift defined for this day
                        continue;
                    }

                    // Get shift details
                    $shift = ShiftKerja::find($jadwalHariIni['shift_id']);
                    if (!$shift) continue;

                    // Get location from jadwal
                    $lokasiId = $jadwalHariIni['lokasi'] ?? null;

                    Presensi::updateOrCreate(
                        [
                            'data_karyawan_id' => $karyawan->id,
                            'tanggal'          => $tanggalLoopStr,
                        ],
                        [
                            'shift_kerja_id'                => $shift->id,
                            'jam_harus_masuk_awal'          => $shift->jam_masuk_min,
                            'jam_harus_masuk_akhir'         => $shift->jam_masuk_max,
                            'jam_harus_pulang_awal'         => $shift->jam_pulang_min,
                            'jam_harus_pulang_akhir'        => $shift->jam_pulang_max,
                            'type_presensi'                 => $shift->tipe,
                            'origin_branchoffice_masuk_id'  => $lokasiId,
                            'origin_branchoffice_pulang_id' => $lokasiId,
                            'status'                        => null,
                        ]
                    );
                }
            }

            DB::commit();
            Log::info("Sukses generate jadwal mulai tanggal: " . $this->tanggalAwal);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal generate jadwal: " . $e->getMessage());
            // Optional: throw error agar Job dianggap failed di database
            throw $e;
        }
    }
}
