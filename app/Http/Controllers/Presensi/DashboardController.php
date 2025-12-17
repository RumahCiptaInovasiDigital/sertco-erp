<?php

namespace App\Http\Controllers\Presensi;

use App\Models\Presensi;
use App\Models\DataKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('page.dashboard.index');
    }

    public function getData()
    {
        try {
            // Set locale to Indonesian
            Carbon::setLocale('id');

            // Get today's date
            $today = Carbon::today()->toDateString();

            // Total Karyawan Aktif
            $totalKaryawan = DataKaryawan::count();

            // Statistik Hari Ini - Single Query untuk performa lebih baik
            $statsToday = Presensi::whereDate('tanggal', $today)
                ->selectRaw("
                    SUM(CASE WHEN status IN ('good', 'late', 'overtime', 'onduty') THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status = 'good' THEN 1 ELSE 0 END) as tepat_waktu,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as terlambat,
                    SUM(CASE WHEN status IN ('leave', 'sick') THEN 1 ELSE 0 END) as izin,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent
                ")
                ->first();

            // Data untuk chart presensi minggu ini (7 hari terakhir) - Batch query
            $weeklyStats = Presensi::whereBetween('tanggal', [
                    Carbon::now()->subDays(6)->toDateString(),
                    Carbon::now()->toDateString()
                ])
                ->selectRaw("
                    DATE(tanggal) as date,
                    SUM(CASE WHEN status IN ('good', 'late', 'overtime', 'onduty') THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as terlambat,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent
                ")
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');

            $weeklyData = [
                'hadir' => [],
                'terlambat' => [],
                'absent' => []
            ];
            $weeklyLabels = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateStr = $date->toDateString();
                // Format: Sen, 04 Des
                $weeklyLabels[] = $date->isoFormat('ddd, DD MMM');

                $stat = $weeklyStats->get($dateStr);
                $weeklyData['hadir'][] = $stat->hadir ?? 0;
                $weeklyData['terlambat'][] = $stat->terlambat ?? 0;
                $weeklyData['absent'][] = $stat->absent ?? 0;
            }

            // Presensi berdasarkan Departemen (untuk pie chart)
            $departemenStats = Presensi::whereDate('tanggal', $today)
                ->join('data_karyawans', 'presensi.data_karyawan_id', '=', 'data_karyawans.id')
                ->join('departemens', 'data_karyawans.idDepartemen', '=', 'departemens.id_departemen')
                ->whereIn('presensi.status', ['good', 'late', 'overtime', 'onduty'])
                ->select('departemens.name', DB::raw('count(*) as total'))
                ->groupBy('departemens.name')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();

            // Aktivitas Terbaru (10 presensi terakhir hari ini)
            $aktivitasTerbaru = Presensi::with(['karyawan' => function($query) {
                    $query->select('id', 'fullName', 'nik', 'idJabatan', 'idDepartemen');
                }, 'karyawan.departemen:id_departemen,name', 'karyawan.jabatan:id_role,name'])
                ->whereDate('tanggal', $today)
                ->whereNotNull('jam_masuk')
                ->select('id', 'data_karyawan_id', 'jam_masuk', 'status', 'type_presensi')
                ->orderBy('jam_masuk', 'desc')
                ->limit(10)
                ->get()
                ->map(function($presensi) {
                    return [
                        'nama' => optional($presensi->karyawan)->fullName ?? '-',
                        'nik' => optional($presensi->karyawan)->nik ?? '-',
                        'jabatan' => optional(optional($presensi->karyawan)->jabatan)->name ?? '-',
                        'departemen' => optional(optional($presensi->karyawan)->departemen)->name ?? '-',
                        // Format waktu Indonesia: 09:30:45 WIB
                        'jam_masuk' => $presensi->jam_masuk ? Carbon::parse($presensi->jam_masuk)->format('H:i') . ' WIB' : '-',
                        'status' => $presensi->status ?? 'unknown',
                        'type_presensi' => $presensi->type_presensi ?? '-',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    // Format: Rabu, 04 Desember 2025
                    'tanggalHariIni' => Carbon::now()->isoFormat('dddd, DD MMMM YYYY'),
                    'totalKaryawan' => $totalKaryawan,
                    'hadirHariIni' => $statsToday->hadir ?? 0,
                    'tepatWaktuHariIni' => $statsToday->tepat_waktu ?? 0,
                    'terlambatHariIni' => $statsToday->terlambat ?? 0,
                    'izinHariIni' => $statsToday->izin ?? 0,
                    'absenHariIni' => $statsToday->absent ?? 0,
                    'weeklyData' => $weeklyData,
                    'weeklyLabels' => $weeklyLabels,
                    'departemenStats' => $departemenStats,
                    'aktivitasTerbaru' => $aktivitasTerbaru,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard getData error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function imagenull()
    {
        $path = public_path('noimage.png');
        if (!file_exists($path)) {
            abort(404);
        }
        $file = file_get_contents($path);
        $type = mime_content_type($path);
        return response($file, 200)->header("Content-Type", $type);
    }
}
