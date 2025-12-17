<?php

namespace App\Http\Controllers\Presensi;

use App\Models\BranchOffice;
use App\Models\DataKaryawan;
use App\Models\JadwalKerja;
use App\Models\ShiftKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class JadwalKerjaController extends Controller
{
    private $shiftsCache = null;


    // Cache for shifts and locations to prevent repeated queries
    private $locationsCache = null;

    public function index()
    {


        $data = [
            'title' => 'Jadwal Kerja',

        ];

        return view('page.jadwal.jadwal-kerja-new', $data);
    }

    public function data()
    {

        $this->shiftsCache = ShiftKerja::all()->keyBy('id');
        $this->locationsCache = BranchOffice::all()->keyBy('id');


        $query = JadwalKerja::with(['karyawan.jabatan']);

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('nama_lengkap', function ($row) {
                $nama = $row->karyawan?->fullName ?? 'N/A';
                $nik = $row->karyawan?->nik ?? '-';
                return '<strong>' . $nama . '</strong><br><small class="text-muted">NIK: ' . $nik . '</small>';
            })
            ->addColumn('jabatan', function ($row) {
                return $row->karyawan?->namaJabatan ?? '-';
            })
            ->addColumn('senin', function ($row) {
                return $this->getShiftBadgeOptimized($row, 1);
            })
            ->addColumn('selasa', function ($row) {
                return $this->getShiftBadgeOptimized($row, 2);
            })
            ->addColumn('rabu', function ($row) {
                return $this->getShiftBadgeOptimized($row, 3);
            })
            ->addColumn('kamis', function ($row) {
                return $this->getShiftBadgeOptimized($row, 4);
            })
            ->addColumn('jumat', function ($row) {
                return $this->getShiftBadgeOptimized($row, 5);
            })
            ->addColumn('sabtu', function ($row) {
                return $this->getShiftBadgeOptimized($row, 6);
            })
            ->addColumn('minggu', function ($row) {
                return $this->getShiftBadgeOptimized($row, 0);
            })
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-sm btn-warning edit-btn" data-id="' . $row->id . '" title="Edit Jadwal"><i class="fas fa-edit"></i></button> ';
                $deleteBtn = '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" title="Hapus"><i class="fas fa-trash"></i></button>';
                return $editBtn . $deleteBtn;
            })
            ->filterColumn('nama_lengkap', function ($query, $keyword) {
                $query->whereHas('karyawan', function ($q) use ($keyword) {
                    $q->where('fullName', 'like', "%{$keyword}%")
                      ->orWhere('nik', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('jabatan', function ($query, $keyword) {
                $query->whereHas('karyawan.jabatan', function ($q) use ($keyword) {
                    $q->where('nama_jabatan', 'like', "%{$keyword}%");
                });
            })
            ->orderColumn('nama_lengkap', function ($query, $order) {
                $query->join('data_karyawans', 'jadwal_kerja.id_karyawan', '=', 'data_karyawans.id')
                    ->orderBy('data_karyawans.fullName', $order)
                    ->select('jadwal_kerja.*');
            })
            ->orderColumn('jabatan', function ($query, $order) {
                $query->join('data_karyawans', 'jadwal_kerja.id_karyawan', '=', 'data_karyawans.id')
                    ->join('jabatans', 'data_karyawans.idJabatan', '=', 'jabatans.id_role')
                    ->orderBy('jabatans.nama_jabatan', $order)
                    ->select('jadwal_kerja.*');
            })
            ->rawColumns(['nama_lengkap', 'action'])
            ->make(true);
    }

    private function getShiftBadgeOptimized($jadwalKerja, $day)
    {
        // Check if jadwal_json is already an array (from model cast) or string
        if (is_array($jadwalKerja->jadwal_json)) {
            $jadwalData = $jadwalKerja->jadwal_json;
        } else if (is_string($jadwalKerja->jadwal_json)) {
            $jadwalData = json_decode($jadwalKerja->jadwal_json, true);
        } else {
            $jadwalData = null;
        }

        if (!is_array($jadwalData) || !isset($jadwalData[$day])) {
            return '-';
        }

        // Handle both old format (string) and new format (array)
        $shiftId = null;
        $lokasiId = null;

        if (is_string($jadwalData[$day])) {
            $shiftId = $jadwalData[$day];
        } else if (is_array($jadwalData[$day]) && isset($jadwalData[$day]['shift_id'])) {
            $shiftId = $jadwalData[$day]['shift_id'];
            $lokasiId = $jadwalData[$day]['lokasi'] ?? null;
        } else {
            return '-';
        }

        // Use cache instead of query
        $shift = $this->shiftsCache->get($shiftId);

        if (!$shift) {
            return '-';
        }

        $jamMasuk = $shift->jam_masuk_max ? \Carbon\Carbon::parse($shift->jam_masuk_max)->format('H:i') : '-';
        $jamPulang = $shift->jam_pulang_min ? \Carbon\Carbon::parse($shift->jam_pulang_min)->format('H:i') : '-';

        // Get location name if available (use cache)
        $lokasiName = '-';
        if ($lokasiId) {
            $lokasi = $this->locationsCache->get($lokasiId);
            if ($lokasi) {
                $lokasiName = $lokasi->name;
            }
        }

        // Return array/object structure for JavaScript rendering
        return [
            'shift' => $shift->nama_shift,
            'jam_masuk' => $jamMasuk,
            'jam_pulang' => $jamPulang,
            'type' => $shift->tipe ?? '-',
            'lokasi' => $lokasiName
        ];
    }

    public function getShiftCounts()
    {
        // Optimize with single query and in-memory processing
        $shifts = ShiftKerja::where('status', 'Aktif')->get()->keyBy('id');
        $shiftCounts = array_fill_keys($shifts->pluck('id')->toArray(), 0);

        // Get all schedules at once
        $jadwalKerjas = JadwalKerja::all(['id', 'jadwal_json']);

        foreach ($jadwalKerjas as $jadwal) {
            // Check if jadwal_json is already an array (from model cast) or string
            if (is_array($jadwal->jadwal_json)) {
                $jadwalData = $jadwal->jadwal_json;
            } else if (is_string($jadwal->jadwal_json)) {
                $jadwalData = json_decode($jadwal->jadwal_json, true);
            } else {
                $jadwalData = null;
            }

            if (!is_array($jadwalData)) {
                continue;
            }

            $uniqueShifts = [];
            foreach ($jadwalData as $daySchedule) {
                // Handle both old format (string) and new format (array)
                $shiftId = is_array($daySchedule) ? ($daySchedule['shift_id'] ?? null) : $daySchedule;

                if ($shiftId && isset($shiftCounts[$shiftId]) && !isset($uniqueShifts[$shiftId])) {
                    $shiftCounts[$shiftId]++;
                    $uniqueShifts[$shiftId] = true; // Mark as counted for this employee
                }
            }
        }

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

            // Array nama hari
            $hariNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

            // 1. Get all active employees
            $allKaryawan = DataKaryawan::all()->keyBy('id');

            // 2. Find the default shift (for all employees)
            $defaultShifts = ShiftKerja::where('status', 'Aktif')
                ->where(function ($query) {
                    $query->whereNull('berlaku_untuk')->orWhere('berlaku_untuk', '');
                })->get();

            // If there are multiple default shifts, use the last one
            $defaultShift = $defaultShifts->isNotEmpty() ? $defaultShifts->last() : null;

            // 3. Initialize assignments with the default shift for all 7 days (0=Sunday to 6=Saturday)
            $assignments = [];
            if ($defaultShift) {
                foreach ($allKaryawan as $karyawan) {
                    // Get default location from kantor or first branch office
                    $defaultLokasi = $karyawan->kantor ?? BranchOffice::first()?->id;

                    // Create default schedule for all 7 days
                    $assignments[$karyawan->id] = [];
                    for ($day = 0; $day <= 6; $day++) {
                        $assignments[$karyawan->id][$day] = [
                            'shift_id' => $defaultShift->id,
                            'hari' => $hariNames[$day],
                            'lokasi' => $defaultLokasi
                        ];
                    }
                }
            }

            // 4. Get specific shifts and override the default for specific roles
            $specificShifts = ShiftKerja::where('status', 'Aktif')
                ->whereNotNull('berlaku_untuk')
                ->where('berlaku_untuk', '!=', '')
                ->get();

            foreach ($specificShifts as $shift) {
                $roleIds = array_filter(array_map('trim', explode(',', $shift->berlaku_untuk)));
                if (empty($roleIds)) {
                    continue;
                }

                // Find employees with these roles and assign the specific shift
                $employeesInRoles = DataKaryawan::whereIn('idJabatan', $roleIds)->get();
                foreach ($employeesInRoles as $employee) {
                    // Get employee location
                    $lokasi = $employee->kantor ?? BranchOffice::first()?->id;

                    // Override all days with the specific shift
                    $assignments[$employee->id] = [];
                    for ($day = 0; $day <= 6; $day++) {
                        $assignments[$employee->id][$day] = [
                            'shift_id' => $shift->id,
                            'hari' => $hariNames[$day],
                            'lokasi' => $lokasi
                        ];
                    }
                }
            }

            // 5. Sync with the database using jadwal_kerja table
            $syncedKaryawanIds = [];
            foreach ($assignments as $karyawanId => $jadwalJson) {
                JadwalKerja::updateOrCreate(
                    ['id_karyawan' => $karyawanId],
                    ['jadwal_json' => json_encode($jadwalJson)]
                );
                $syncedKaryawanIds[] = $karyawanId;
            }

            // 6. Clean up old/unnecessary schedule entries
            JadwalKerja::whereNotIn('id_karyawan', $syncedKaryawanIds)->delete();

            DB::commit();

            $totalSyncedCount = count($syncedKaryawanIds);
            return response()->json(['success' => $totalSyncedCount . ' jadwal karyawan berhasil disinkronkan.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Gagal sinkronisasi: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_karyawan' => 'required|exists:data_karyawans,id|unique:jadwal_kerja,id_karyawan',
            'jadwal_json' => 'nullable|json',
        ], [
            'id_karyawan.unique' => 'Karyawan ini sudah memiliki jadwal kerja.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $hariNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwalJson = [];

        // Check if jadwal_json is provided (manual per-day setup)
        if ($request->has('jadwal_json') && $request->jadwal_json) {
            $jadwalJson = json_decode($request->jadwal_json, true);

            // Validate that we have all 7 days
            if (!is_array($jadwalJson) || count($jadwalJson) != 7) {
                return response()->json(['errors' => ['jadwal_json' => ['Jadwal harus memiliki 7 hari (0-6)']]], 422);
            }
        } else {
            // Legacy: use id_shift_kerja for all days
            $validator2 = Validator::make($request->all(), [
                'id_shift_kerja' => 'required|exists:shift_kerja,id',
                'lokasi' => 'nullable|exists:branch_offices,id',
            ]);

            if ($validator2->fails()) {
                return response()->json(['errors' => $validator2->errors()], 422);
            }

            // Get employee data for default location
            $karyawan = DataKaryawan::find($request->id_karyawan);
            $defaultLokasi = $request->lokasi ?? $karyawan->kantor ?? BranchOffice::first()?->id;

            // Create default schedule for all 7 days with the same shift
            for ($day = 0; $day <= 6; $day++) {
                $jadwalJson[$day] = [
                    'shift_id' => $request->id_shift_kerja,
                    'hari' => $hariNames[$day],
                    'lokasi' => $defaultLokasi
                ];
            }
        }

        $jadwal = JadwalKerja::create([
            'id_karyawan' => $request->id_karyawan,
            'jadwal_json' => json_encode($jadwalJson)
        ]);

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
            'jadwal_json' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validate that jadwal_json contains all 7 days (0-6)
        $jadwalData = json_decode($request->jadwal_json, true);
        if (!is_array($jadwalData) || count($jadwalData) != 7) {
            return response()->json(['errors' => ['jadwal_json' => ['Jadwal harus memiliki 7 hari (0-6)']]], 422);
        }

        $jadwal->jadwal_json = $request->jadwal_json;
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

    public function getKaryawanWithoutJadwal(Request $request)
    {
        $search = $request->q;

        // Get all karyawan IDs that already have jadwal
        $karyawanWithJadwal = JadwalKerja::pluck('id_karyawan')->toArray();

        $data = DataKaryawan::select(['id', 'nik', 'fullName'])
            ->whereNotIn('id', $karyawanWithJadwal)
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('fullName', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%');
                }
            })
            ->limit(20)
            ->get()
            ->map(function ($karyawan) {
                return [
                    'id' => $karyawan->id,
                    'text' => $karyawan->fullName . ' (NIK: ' . $karyawan->nik . ')'
                ];
            });

        return response()->json($data);
    }
}
