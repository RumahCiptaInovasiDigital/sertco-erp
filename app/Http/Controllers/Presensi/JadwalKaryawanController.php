<?php

namespace App\Http\Controllers\Presensi;

use App\Jobs\GenerateJadwalJob;
use App\Models\DataKaryawan;
use App\Models\Presensi;
use App\Models\ShiftKerja;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JadwalKaryawanController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Jadwal Karyawan', 'subtitle' => 'Master',

        ];

        return view('page.jadwal.jadwal-karyawan', $data);
    }

    public function getData(Request $request)
    {
        // 1. Tentukan Tanggal Awal
        // Jika ada request tanggal_awal (dari filter), pakai itu.
        // Jika tidak, default ke hari ini.
        if ($request->filled('tanggal_awal')) {
            $start = Carbon::parse($request->tanggal_awal);
        } else {
            $start = now();
        }

        // 2. KUNCI: Tanggal Akhir SELALU +7 hari dari Tanggal Awal (Total 7 hari)
        $end = $start->copy()->addDays(6);

        // 3. Buat Array Tanggal untuk Header (dikirim ke JS)
        $headerDates = [];
        $tempDate = $start->copy();

        for ($i = 0; $i < 7; $i++) {
            // Kita simpan format d-m (misal: 25-11) untuk label header
            $headerDates[] = $tempDate->format('d-m');
            $tempDate->addDay();
        }

        // 4. Query Data dengan Eager Loading Presensi
        // Format tanggal database biasanya Y-m-d
        $dbStart = $start->format('Y-m-d');
        $dbEnd = $end->format('Y-m-d');

        $presensi = DataKaryawan::with(['presensi' => function ($query) use ($dbStart, $dbEnd) {
            $query->whereBetween('tanggal', [$dbStart, $dbEnd])
                  ->with(['originOfficeMasuk', 'originOfficePulang']);
        }])->get();

        return DataTables::of($presensi)->addIndexColumn()
            // Kolom Nama Lengkap + NIK
            ->addColumn('fullName', function ($row) {
                return '<div class="font-weight-bold">' . $row->fullName . '</div>' . '<span class="small text-muted">' . $row->nik . '</span>';
            })
            // Kolom Aksi
            ->addColumn('aksi', function ($row) {
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-info" title="Detail"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></button>
                        </div>';
            })
            // Mendaftarkan kolom yang mengandung HTML
            ->rawColumns(['fullName', 'aksi'])

            // 5. Kirim data tambahan (Header Dates) ke JSON response
            ->with(['headerDates' => $headerDates])->make(true);
    }

    public function generateJadwal(Request $request)
    {
        // 1. Validasi
        $request->validate(['tanggal_awal' => 'required|date',]);

        GenerateJadwalJob::dispatch($request->tanggal_awal)->onConnection('sync');

        // 3. Langsung return response tanpa menunggu selesai
        return response()->json(['status' => 'success', 'message' => 'Permintaan generate jadwal sedang diproses di latar belakang. Silakan cek tabel beberapa saat lagi.']);

    }

    public function shiftKerja($id)
    {
        $presensi = Presensi::where('id', $id)
            ->with(['karyawan', 'originOfficeMasuk', 'originOfficePulang'])
            ->first();
        $shifts = ShiftKerja::with('jenis_kerja')->get();
        $branchOffices = \App\Models\BranchOffice::all();

        $data = [
            'presensi' => $presensi,
            'shifts' => $shifts,
            'branchOffices' => $branchOffices,
        ];
        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }

    public function simpanShift(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'shift_kerja_id' => 'required',
            'origin_branchoffice_masuk_id' => 'nullable',
            'origin_branchoffice_pulang_id' => 'nullable',
        ]);
        try {
            $shift = ShiftKerja::where('id', $request->shift_kerja_id)->first();

            $presensi = Presensi::where('id', $request->id)->firstOrFail();
            $presensi->shift_kerja_id = $request->shift_kerja_id;
            $presensi->type_presensi = $shift->tipe;
            $presensi->jam_harus_masuk_awal = $shift->jam_masuk_min;
            $presensi->jam_harus_masuk_akhir = $shift->jam_masuk_max;
            $presensi->jam_harus_pulang_awal = $shift->jam_pulang_min;
            $presensi->jam_harus_pulang_akhir = $shift->jam_pulang_max;

            // Update lokasi jika ada
            if ($request->filled('origin_branchoffice_masuk_id')) {
                $presensi->origin_branchoffice_masuk_id = $request->origin_branchoffice_masuk_id;
            }
            if ($request->filled('origin_branchoffice_pulang_id')) {
                $presensi->origin_branchoffice_pulang_id = $request->origin_branchoffice_pulang_id;
            }

            $presensi->updated_at = now();
            $presensi->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal berhasil diubah untuk karyawan: ' . $presensi->karyawan->fullName
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


}
