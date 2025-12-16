<?php

namespace App\Http\Controllers\Presensi;

use App\Models\DataKaryawan;
use App\Models\JadwalKerja;
use App\Models\ShiftKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Exceptions\Exception;
use Yajra\DataTables\Facades\DataTables;

class JadwalKerjaController_backup extends Controller
{
    public function index()
    {
        $shifts = ShiftKerja::withCount('jadwalKerja as karyawan_count')->where('status', 'Aktif')->get();

        $data = [
            'title' => 'Jadwal Kerja',
            'shifts' => $shifts
        ];

        return view('page.jadwal.jadwal-kerja', $data);
    }


    public function getDataTable(Request $request)
    {
        // 1. Query Utama
        $data = JadwalKerja::query()
            ->with(['shift', 'karyawan'])
            ->select('jadwal_kerja.*');

        return DataTables::of($data)
            ->addIndexColumn()

            // --- DEFINISI KOLOM (DISPLAY) ---
            ->addColumn('shift', function ($row) {
                return $row->shift->nama_shift ?? '-';
            })
            ->addColumn('karyawan', function ($row) {
                $nikBadge = isset($row->karyawan->nik) ? '<br> <span class="badge badge-primary">' . $row->karyawan->nik . '</span>' : '';
                return ($row->karyawan->fullName ?? '-') . $nikBadge;
            })
            ->addColumn('jabatan', function ($row) {
                return $row->karyawan->namaJabatan ?? '-';
            })
            ->addColumn('jam_kerja', function ($row) {
                if ($row->shift) {
                    return substr($row->shift->jam_masuk_max, 0, 5) . ' - ' . substr($row->shift->jam_pulang_min, 0, 5);
                }
                return '-';
            })
            ->addColumn('tipe', function ($row) {
                if ($row->shift) {
                    return '<span class="badge ' . ($row->shift->tipe == 'WFO' ? 'badge-success' : 'badge-danger') . '">' . $row->shift->tipe . '</span>';
                }
                return '-';
            })
            ->addColumn('tanggal', function ($row) {
                return Carbon::parse($row->tanggal)->translatedFormat('d F Y');
            })
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-warning btn-sm edit-btn" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                    <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete-btn" title="Hapus"><i class="fas fa-trash"></i></a>';
            })

            // --- FILTER (PENCARIAN) ---
            ->filterColumn('karyawan', function($query, $keyword) {
                $query->whereHas('karyawan', function($q) use ($keyword) {
                    $q->where('fullName', 'like', "%{$keyword}%")
                        ->orWhere('nik', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('jabatan', function($query, $keyword) {
                $query->whereHas('karyawan', function($q) use ($keyword) {
                    $q->where('namaJabatan', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('shift', function($query, $keyword) {
                $query->whereHas('shift', function($q) use ($keyword) {
                    $q->where('nama_shift', 'like', "%{$keyword}%");
                });
            })
            // Tambahkan filter untuk tipe juga (opsional, biar search bar bisa cari WFO/WFA)
            ->filterColumn('tipe', function($query, $keyword) {
                $query->whereHas('shift', function($q) use ($keyword) {
                    $q->where('tipe', 'like', "%{$keyword}%");
                });
            })

            // --- ORDER COLUMN (SORTING) ---
            ->orderColumn('karyawan', function ($query, $order) {
                $query->join('data_karyawans as dk', 'jadwal_kerja.id_karyawan', '=', 'dk.id')
                    ->orderBy('dk.fullName', $order)
                    ->select('jadwal_kerja.*');
            })
            ->orderColumn('jabatan', function ($query, $order) {
                $query->join('data_karyawans as dk2', 'jadwal_kerja.id_karyawan', '=', 'dk2.id')
                    ->orderBy('dk2.namaJabatan', $order)
                    ->select('jadwal_kerja.*');
            })
            ->orderColumn('shift', function ($query, $order) {
                $query->join('shift_kerja as sk', 'jadwal_kerja.id_shift_kerja', '=', 'sk.id')
                    ->orderBy('sk.nama_shift', $order)
                    ->select('jadwal_kerja.*');
            })

            // --- PERBAIKAN: TAMBAHKAN ORDER UNTUK TIPE ---
            ->orderColumn('tipe', function ($query, $order) {
                // Kita join ke tabel shift_kerja (alias sk_tipe) dan order berdasarkan kolom 'tipe'
                $query->join('shift_kerja as sk_tipe', 'jadwal_kerja.id_shift_kerja', '=', 'sk_tipe.id')
                    ->orderBy('sk_tipe.tipe', $order)
                    ->select('jadwal_kerja.*');
            })
            // ----------------------------------------------

            ->rawColumns(['action', 'karyawan', 'tipe'])
            ->blacklist(['jam_kerja', 'action'])
            ->make(true);
    }

    public function getShiftCounts()
    {
        $shifts = ShiftKerja::withCount('jadwalKerja')->where('status', 'Aktif')->get();
        $shiftCounts = $shifts->pluck('jadwal_kerja_count', 'id');
        return response()->json($shiftCounts);
    }

    public function destroy($id)
    {
        $jadwalKerja = JadwalKerja::find($id);

        if (!$jadwalKerja) {
            return response()->json(['error' => 'Jadwal kerja tidak ditemukan.'], 404);
        }

        try {
            $jadwalKerja->delete();
            return response()->json(['success' => 'Jadwal kerja berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus jadwal kerja: ' . $e->getMessage()], 500);
        }
    }
    public function syncSchedules()

    {

        try {

            DB::beginTransaction();


            // 1. Get all employees

            $allKaryawan = DataKaryawan::all()->keyBy('id');


            // 2. Find the default shift(s) (for all employees)

            $defaultShifts = ShiftKerja::where('status', 'Aktif')->where(function ($query) {

                $query->whereNull('berlaku_untuk')->orWhere('berlaku_untuk', '');
            })->get();


            // If there are multiple default shifts, we'll use the last one.

            $defaultShiftId = $defaultShifts->isNotEmpty() ? $defaultShifts->last()->id : null;


            // 3. Initialize assignments with the default shift

            $assignments = [];

            if ($defaultShiftId) {

                foreach ($allKaryawan as $karyawan) {

                    $assignments[$karyawan->id] = $defaultShiftId;
                }
            }


            // 4. Get specific shifts and override the default

            $specificShifts = ShiftKerja::where('status', 'Aktif')->whereNotNull('berlaku_untuk')->where('berlaku_untuk', '!=', '')->get();


            foreach ($specificShifts as $shift) {

                $roleIds = array_filter(array_map('trim', explode(',', $shift->berlaku_untuk)));

                if (empty($roleIds)) {

                    continue;
                }


                // Find employees with these roles and assign the specific shift

                $employeesInRoles = DataKaryawan::whereIn('idJabatan', $roleIds)->get();

                foreach ($employeesInRoles as $employee) {

                    $assignments[$employee->id] = $shift->id;
                }
            }


            // 5. Sync with the database

            $syncedKaryawanIds = [];

            foreach ($assignments as $karyawanId => $shiftId) {

                JadwalKerja::updateOrCreate(

                    ['id_karyawan' => $karyawanId],

                    ['id_shift_kerja' => $shiftId]

                );

                $syncedKaryawanIds[] = $karyawanId;
            }


            // 6. Clean up old/unnecessary schedule entries

            JadwalKerja::whereNotIn('id_karyawan', $syncedKaryawanIds)->delete();


            DB::commit();

            $totalSyncedCount = count($syncedKaryawanIds);

            return response()->json(['success' => $totalSyncedCount . ' jadwal karyawan berhasil disinkronkan.']);
        } catch (Exception $e) {

            DB::rollBack();

            return response()->json(['error' => 'Gagal sinkronisasi: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_karyawan' => 'required|exists:data_karyawans,id|unique:jadwal_kerja,id_karyawan',
            'id_shift_kerja' => 'required|exists:shift_kerja,id',
        ], [
            'id_karyawan.unique' => 'Karyawan ini sudah memiliki jadwal kerja.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jadwal = JadwalKerja::create($request->all());

        return response()->json(['success' => 'Jadwal kerja berhasil dibuat.', 'jadwal' => $jadwal]);
    }

    public function edit($id)
    {
        $jadwal = JadwalKerja::with('karyawan:id,fullName,nik')->findOrFail($id);
        return response()->json($jadwal);
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalKerja::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_shift_kerja' => 'required|exists:shift_kerja,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jadwal->id_shift_kerja = $request->id_shift_kerja;
        $jadwal->save();

        return response()->json(['success' => 'Jadwal kerja berhasil diperbarui.']);
    }

    public function getKaryawanForSelect(Request $request)
    {
        $search = $request->q;

        $data = DataKaryawan::select(['id', 'nik', 'fullName'])
            ->where(function ($query) use ($search) {
                $query->where('fullName', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%');
            })
            ->limit(20)
            ->get()
            ->map(function ($karyawan) {
                return [
                    'id' => $karyawan->id,
                    'text' => $karyawan->fullName . ' (' . $karyawan->nik . ')'
                ];
            });

        return response()->json($data);
    }
}
