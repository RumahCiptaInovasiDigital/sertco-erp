<?php

namespace App\Http\Controllers\Presensi;

use App\Models\DataKaryawan;
use App\Models\Presensi;
use App\Models\ResumePresensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ResumePresensiController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode', date('Y-m'));

        if ($request->ajax()) {
            $data = ResumePresensi::with('karyawan')
                ->where('periode', $periode);

            // Hitung Summary untuk dikirim bersama JSON DataTables
            $summaryData = (clone $data)->get();

            $totalHari = $summaryData->sum('total_hari');
            $totalHadir = $summaryData->sum('total_good') + $summaryData->sum('total_late') +
                          $summaryData->sum('total_overtime') + $summaryData->sum('total_onduty');

            $summary = [
                'total_karyawan'    => $summaryData->count(),
                'total_hadir'       => $totalHadir,
                'total_good'        => $summaryData->sum('total_good'),
                'total_late'        => $summaryData->sum('total_late'),
                'total_absent'      => $summaryData->sum('total_absent'),
                'total_leave'       => $summaryData->sum('total_leave'),
                'total_sick'        => $summaryData->sum('total_sick'),
                'total_onduty'      => $summaryData->sum('total_onduty'),
                'total_overtime'    => $summaryData->sum('total_overtime'),
                'total_uncompleted' => $summaryData->sum('total_uncompleted'),
                'persentase_global' => $totalHari > 0 ? ($totalHadir / $totalHari) * 100 : 0
            ];

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nik', function($row) {
                    return '<span class="badge badge-light">' . ($row->karyawan->nik ?? '-') . '</span>';
                })
                ->addColumn('nama_karyawan', function($row) {
                    return '<strong>' . ($row->karyawan->fullName ?? '-') . '</strong><br>' .
                           '<small class="text-muted">' . ($row->karyawan->jabatan->nama_jabatan ?? '-') . '</small>';
                })
                ->addColumn('total_hadir', function($row) {
                    return $row->total_good + $row->total_late + $row->total_overtime + $row->total_onduty;
                })
                ->addColumn('persentase', function($row) {
                    $totalHadir = $row->total_good + $row->total_late + $row->total_overtime + $row->total_onduty;
                    return $row->total_hari > 0 ? ($totalHadir / $row->total_hari) * 100 : 0;
                })
                ->addColumn('aksi', function($row) use ($periode) {
                    $detailUrl = route('resume-presensi.detail', ['karyawan_id' => $row->karyawan_id]) . '?periode=' . $periode;

                    return '
                        <div class="btn-group" role="group">
                            <a href="' . $detailUrl . '"
                               class="btn btn-sm btn-info"
                               title="Lihat Detail Presensi">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-primary btn-print"
                                    data-id="' . $row->karyawan_id . '"
                                    data-nama="' . ($row->karyawan->fullName ?? '-') . '"
                                    title="Cetak Laporan">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['nik', 'nama_karyawan','aksi'])
                ->with('summary', $summary)
                ->make(true);
        }

        return view('page.presensi.laporan-resume');
    }

    public function detail($karyawan_id, Request $request)
    {
        $periode = $request->input('periode', date('Y-m'));

        $resume = ResumePresensi::with(['karyawan.jabatan', 'karyawan.departemen'])
            ->where('karyawan_id', $karyawan_id)
            ->where('periode', $periode)
            ->firstOrFail();

        $startDate = Carbon::createFromFormat('Y-m', $periode)->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::createFromFormat('Y-m', $periode)->endOfMonth()->format('Y-m-d');

        $presensis = Presensi::with(['originOfficeMasuk', 'originOfficePulang'])
            ->where('data_karyawan_id', $karyawan_id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('page.presensi.detail-resume', compact('resume', 'presensis', 'periode'));
    }

    public function export(Request $request)
    {
        $periode = $request->input('periode', date('Y-m'));

        // Get data
        $data = ResumePresensi::with(['karyawan.jabatan', 'karyawan.departemen'])
            ->where('periode', $periode)
            ->orderBy('karyawan_id')
            ->get();

        // Nama file (Gunakan .xls agar Excel tidak menolak format HTML)
        $namaFile = 'Resume_Presensi_' . $periode . '.xls';

        // Render View ke dalam string HTML
        $html = view('page.presensi.export-excel', compact('data', 'periode'))->render();

        // Return response dengan Header Excel
        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $namaFile . '"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function sync(Request $request)
    {
        $request->validate([
            'periode' => 'required|date_format:Y-m',
        ]);

        $periode = $request->input('periode');
        $startDate = Carbon::createFromFormat('Y-m', $periode)->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::createFromFormat('Y-m', $periode)->endOfMonth()->format('Y-m-d');

        DB::beginTransaction();
        try {
            $karyawans = DataKaryawan::all();
            $syncedCount = 0;

            foreach ($karyawans as $karyawan) {
                $stats = Presensi::where('data_karyawan_id', $karyawan->id)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->selectRaw("
                        count(*) as total_hari,
                        SUM(CASE WHEN status = 'good' THEN 1 ELSE 0 END) as total_good,
                        SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as total_late,
                        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as total_absent,
                        SUM(CASE WHEN status = 'leave' THEN 1 ELSE 0 END) as total_leave,
                        SUM(CASE WHEN status = 'sick' THEN 1 ELSE 0 END) as total_sick,
                        SUM(CASE WHEN status = 'onduty' THEN 1 ELSE 0 END) as total_onduty,
                        SUM(CASE WHEN status = 'overtime' THEN 1 ELSE 0 END) as total_overtime,
                        SUM(CASE WHEN status = 'uncompleted' THEN 1 ELSE 0 END) as total_uncompleted
                    ")
                    ->first();

                ResumePresensi::updateOrCreate(
                    [
                        'karyawan_id' => $karyawan->id,
                        'periode' => $periode,
                    ],
                    [
                        'total_hari' => $stats->total_hari ?? 0,
                        'total_good' => $stats->total_good ?? 0,
                        'total_late' => $stats->total_late ?? 0,
                        'total_absent' => $stats->total_absent ?? 0,
                        'total_leave' => $stats->total_leave ?? 0,
                        'total_sick' => $stats->total_sick ?? 0,
                        'total_onduty' => $stats->total_onduty ?? 0,
                        'total_overtime' => $stats->total_overtime ?? 0,
                        'total_uncompleted' => $stats->total_uncompleted ?? 0,
                    ]
                );

                $syncedCount++;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Berhasil menyinkronkan data untuk $syncedCount karyawan pada periode $periode."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printKaryawan($karyawan_id, Request $request)
    {
        $periode = $request->input('periode', date('Y-m'));

        // Get resume data
        $resume = ResumePresensi::with(['karyawan.jabatan', 'karyawan.departemen'])
            ->where('karyawan_id', $karyawan_id)
            ->where('periode', $periode)
            ->firstOrFail();

        // Calculate start and end date
        $startDate = Carbon::createFromFormat('Y-m', $periode)->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::createFromFormat('Y-m', $periode)->endOfMonth()->format('Y-m-d');

        // Get presensi records
        $presensis = Presensi::with(['originOfficeMasuk', 'originOfficePulang'])
            ->where('data_karyawan_id', $karyawan_id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        // Return print view
        return view('page.presensi.print-karyawan', compact('resume', 'presensis', 'periode'));
    }
}

