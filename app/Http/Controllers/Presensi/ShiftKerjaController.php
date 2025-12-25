<?php

namespace App\Http\Controllers\Presensi;

use App\Models\ShiftKerja;
use App\Models\Role;
use App\Models\JenisKerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ShiftKerjaController extends Controller
{
    public function index()
    {
        $title = 'Master Shift Kerja';
        $roles = Role::all();
        $jenisKerja = JenisKerja::where('status', 'active')->get();
        return view('page.master.shiftkerja', compact('title', 'roles', 'jenisKerja'));
    }

    public function getShiftKerja(Request $request)
    {
        if ($request->ajax()) {
            $data = ShiftKerja::with('jenis_kerja')->orderBy('berlaku_untuk', 'asc')->get();
            $roleMap = Role::pluck('name', 'id_role')->toArray();

            return DataTables::of($data)->addIndexColumn()->addColumn('berlaku_untuk_badge', function ($row) use ($roleMap) {
                if (!$row->berlaku_untuk) {
                    return '<span class="badge badge-secondary">Semua</span>';
                }
                $roleIds = explode(',', $row->berlaku_untuk);
                $badges = '';
                foreach ($roleIds as $id) {
                    if (isset($roleMap[$id])) {
                        $badges .= '<span class="badge badge-primary mr-1">' . $roleMap[$id] . '</span>';
                    }
                }
                return $badges;
            })->addColumn('tipe', function ($row) {
                if ($row->tipe) {
                    $badgeClass = $row->tipe == 'WFO' ? 'badge-success' : 'badge-danger';
                    return '<span class="badge ' . $badgeClass . '">' . $row->tipe . '</span>';
                }
                return '<span class="badge badge-secondary">-</span>';
            })->addColumn('status_badge', function ($row) {
                $color = $row->status == 'Aktif' ? 'success' : 'danger';
                return '<span class="badge badge-' . $color . '">' . $row->status . '</span>';
            })->addColumn('jam_kerja', function ($row) {
                return '<strong>' . $row->jam_masuk_max . '</strong> - <strong>' . $row->jam_pulang_min . '</strong>';
            })->addColumn('durasi', function ($row) {
                $start = Carbon::parse($row->jam_masuk_max);
                $end = Carbon::parse($row->jam_pulang_min);

                // Handle overnight shifts
                if ($end < $start) {
                    $end->addDay();
                }

                $hours = $end->diffInHours($start);
                $minutes = $end->diffInMinutes($start) % 60;

                return sprintf('<span class="badge badge-success">%d jam %d menit</span>', $hours, $minutes);
            })->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-sm btn-warning edit-btn" data-id="' . $row->id . '" title="Edit"><i class="fas fa-edit"></i></button>';
                $deleteBtn = '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" title="Hapus"><i class="fas fa-trash-alt"></i></button>';
                return '<div class="btn-group">' . $editBtn . $deleteBtn . '</div>';
            })->rawColumns(['action', 'berlaku_untuk_badge', 'status_badge', 'tipe', 'jam_kerja', 'durasi'])->make(true);
        }
    }

    public function shiftKerja()
    {
        $shifts = ShiftKerja::with('jenis_kerja')->get();
        return response()->json($shifts, 200, [], JSON_PRETTY_PRINT);
    }

    public function getData()
    {
        $shifts = ShiftKerja::where('status', 'Aktif')
            ->select('id', 'nama_shift', 'jam_masuk_min', 'jam_masuk_max', 'jam_pulang_min', 'jam_pulang_max', 'tipe')
            ->orderBy('nama_shift', 'asc')
            ->get();

        return response()->json(['data' => $shifts]);
    }

    public function store(Request $request)
    {
        // 1. Rules Dasar
        $validator = Validator::make($request->all(), [
            'nama_shift'      => ['required', 'string', 'max:255'],
            'jam_masuk_min'   => ['required', 'date_format:H:i'],
            'jam_masuk_max'   => [
                'required', 'date_format:H:i', 'after_or_equal:jam_masuk_min',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value < $request->jam_masuk_min) $fail('Jam masuk max harus >= min.');
                }
            ],
            'jam_pulang_min'  => [
                'required', 'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value < $request->jam_masuk_max || $value > $request->jam_pulang_max) {
                        $fail('Rentang jam pulang tidak valid.');
                    }
                }
            ],
            'jam_pulang_max'  => ['required', 'date_format:H:i', 'after_or_equal:jam_pulang_min'],
            'tipe'            => ['required', 'string'],
            'status'          => ['required', Rule::in(['Aktif', 'Tidak Aktif'])],
            'berlaku_untuk'   => ['nullable'], // Validasi berat dilakukan di hook after
        ]);

        // Set Attribute Names ... (kode Anda sebelumnya)

        // -----------------------------------------------------------
        // 2. VALIDASI LOGIKA KOMPLEKS (HOOK AFTER)
        // -----------------------------------------------------------
        $validator->after(function ($validator) use ($request) {
            $inputBerlaku = $request->berlaku_untuk; // Ini array dari input select2

            // --- CEK 1: Default Shift (NULL) ---
            // Cek apakah inputnya kosong (berarti user ingin buat default shift)
            $isInputNull = empty($inputBerlaku) || (is_array($inputBerlaku) && count($inputBerlaku) === 0);

            if ($isInputNull) {
                $query = \DB::table('shift_kerja')->whereNull('berlaku_untuk');
                if ($request->filled('id')) $query->where('id', '!=', $request->id); // Exclude diri sendiri saat edit

                if ($query->exists()) {
                    $validator->errors()->add('berlaku_untuk', 'Gagal: Shift Default (Tanpa Karyawan Spesifik) sudah ada. Hanya boleh ada satu.');
                }
            }

            // --- CEK 2: Spesifik ID (Duplikat Orang) ---
            // Jika inputnya berisi ID Karyawan (Array tidak kosong)
            else {
                // Ambil semua shift lain yang TIDAK NULL dari database
                $query = \DB::table('shift_kerja')->whereNotNull('berlaku_untuk');

                if ($request->filled('id')) {
                    $query->where('id', '!=', $request->id); // Penting: Jangan cek diri sendiri saat edit
                }

                // Kita ambil kolom 'id', 'nama_shift', dan isi 'berlaku_untuk'
                $existingShifts = $query->get(['id', 'nama_shift', 'berlaku_untuk']);

                // Loop setiap Shift yang ada di DB
                foreach ($existingShifts as $shift) {
                    // Database menyimpan string "id1,id2,id3", kita pecah jadi array
                    $dbIds = explode(',', $shift->berlaku_untuk);

                    // Cek Irisan: Apakah ada ID dari input user yang SAMA dengan ID di baris DB ini?
                    // array_intersect membandingkan dua array dan mengembalikan nilai yang sama
                    $duplicateIds = array_intersect($inputBerlaku, $dbIds);

                    if (!empty($duplicateIds)) {
                        // Jika ketemu yang sama
                        $conflictId = reset($duplicateIds); // Ambil satu ID contoh yang konflik

                        // Opsional: Query nama karyawan biar pesan error lebih manusiawi
                        // $namaKaryawan = \DB::table('data_karyawans')->where('id', $conflictId)->value('fullName') ?? $conflictId;

                        $validator->errors()->add(
                            'berlaku_untuk',
                            "Data jabatan sudah terdaftar di aturan shift '$shift->nama_shift'. Harap hapus dari shift lama dulu."
                        );

                        // Break agar tidak menumpuk pesan error
                        return;
                    }
                }
            }
        });

        // -----------------------------------------------------------

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 3. Simpan Data
        $shiftKerja = new ShiftKerja();
        if ($request->filled('id')) {
            $shiftKerja = ShiftKerja::find($request->id);
        }

        $shiftKerja->nama_shift     = $request->nama_shift;
        $shiftKerja->jam_masuk_min  = $request->jam_masuk_min;
        $shiftKerja->jam_masuk_max  = $request->jam_masuk_max;
        $shiftKerja->jam_pulang_min = $request->jam_pulang_min;
        $shiftKerja->jam_pulang_max = $request->jam_pulang_max;
        $shiftKerja->tipe           = $request->tipe;
        $shiftKerja->status         = $request->status;

        // Pastikan simpan NULL jika array kosong, atau String implode jika ada isi
        if (empty($request->berlaku_untuk) || count($request->berlaku_untuk) == 0) {
            $shiftKerja->berlaku_untuk = null;
        } else {
            $shiftKerja->berlaku_untuk = implode(',', $request->berlaku_untuk);
        }

        $shiftKerja->save();

        return response()->json(['success' => 'Data Shift Kerja berhasil disimpan.']);
    }

    public function edit($id)
    {
        $shift = ShiftKerja::with('jenis_kerja')->findOrFail($id);
//        $shift->berlaku_untuk = $shift->berlaku_untuk ? explode(',', $shift->berlaku_untuk) : [];
        return response()->json([
            'id' => $shift->id,
            'nama_shift' => $shift->nama_shift,
            // Ambil 5 karakter pertama saja (08:00:00 -> 08:00)
            'jam_masuk_min' => substr($shift->jam_masuk_min, 0, 5),
            'jam_masuk_max' => substr($shift->jam_masuk_max, 0, 5),
            'jam_pulang_min' => substr($shift->jam_pulang_min, 0, 5),
            'jam_pulang_max' => substr($shift->jam_pulang_max, 0, 5),
            'tipe' => $shift->tipe,
            'status' => $shift->status,

            // Handle berlaku_untuk (Array)
            'berlaku_untuk' => $shift->berlaku_untuk ? explode(',', $shift->berlaku_untuk) : [],
        ]);
    }

    public function update(Request $request, $id)
    {
        // 1. RULES DASAR (Validasi Format & Jam)
        $validator = Validator::make($request->all(), [
            'nama_shift'      => ['required', 'string', 'max:255'],
            'jam_masuk_min'   => ['required', 'date_format:H:i'],
            'jam_masuk_max'   => [
                'required', 'date_format:H:i', 'after_or_equal:jam_masuk_min',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value < $request->jam_masuk_min) $fail('Jam masuk max harus >= min.');
                }
            ],
            'jam_pulang_min'  => [
                'required', 'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value < $request->jam_masuk_max || $value > $request->jam_pulang_max) {
                        $fail('Rentang jam pulang tidak valid.');
                    }
                }
            ],
            'jam_pulang_max'  => ['required', 'date_format:H:i', 'after_or_equal:jam_pulang_min'],
            'tipe'            => ['required', 'string'],
            'status'          => ['required', \Illuminate\Validation\Rule::in(['Aktif', 'Tidak Aktif'])],
            'berlaku_untuk'   => ['nullable'], // Validasi berat dilakukan di hook after
        ]);

        // 2. LOGIKA VALIDASI LANJUTAN (HOOK AFTER)
        // Kita butuh variabel $id untuk mengecualikan data ini sendiri dari pengecekan
        $validator->after(function ($validator) use ($request, $id) {
            $inputBerlaku = $request->berlaku_untuk; // Array ID dari Select2

            // --- CEK 1: Default Shift (NULL) ---
            // Cek apakah input kosong (user ingin set jadi Default Shift)
            $isInputNull = empty($inputBerlaku) || (is_array($inputBerlaku) && count($inputBerlaku) === 0);

            if ($isInputNull) {
                // Cari apakah ada shift default LAIN di database
                $query = \DB::table('shift_kerja')
                    ->whereNull('berlaku_untuk')
                    ->where('id', '!=', $id); // PENTING: Kecualikan diri sendiri!

                if ($query->exists()) {
                    $validator->errors()->add('berlaku_untuk', 'Gagal: Shift Default (Tanpa Karyawan Spesifik) sudah ada. Hanya boleh ada satu.');
                }
            }

            // --- CEK 2: Spesifik ID (Duplikat Orang) ---
            // Jika input ada isinya (Array ID Karyawan)
            else {
                // Ambil semua shift LAIN yang tidak null
                $existingShifts = \DB::table('shift_kerja')
                    ->whereNotNull('berlaku_untuk')
                    ->where('id', '!=', $id) // PENTING: Kecualikan diri sendiri!
                    ->get(['id', 'nama_shift', 'berlaku_untuk']);

                // Loop shift lain untuk cari irisan ID
                foreach ($existingShifts as $shift) {
                    $dbIds = explode(',', $shift->berlaku_untuk);

                    // Cari apakah ada ID baru yang bentrok dengan ID lama
                    $duplicateIds = array_intersect($inputBerlaku, $dbIds);

                    if (!empty($duplicateIds)) {
                        $conflictId = reset($duplicateIds); // Ambil satu contoh ID

                        $validator->errors()->add(
                            'berlaku_untuk',
                            "Ada jabatan sudah terdaftar di aturan shift '$shift->nama_shift'. Harap hapus dari shift tersebut dulu."
                        );
                        return;
                    }
                }
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 3. PROSES UPDATE DATA
        $shift = ShiftKerja::findOrFail($id);

        // Siapkan data berlaku_untuk (Array -> String atau Null)
        $berlaku_untuk_final = null;
        if (!empty($request->berlaku_untuk) && count($request->berlaku_untuk) > 0) {
            $berlaku_untuk_final = implode(',', $request->berlaku_untuk);
        }

        // Update Field
        $shift->nama_shift     = $request->nama_shift;
        $shift->jam_masuk_min  = $request->jam_masuk_min;
        $shift->jam_masuk_max  = $request->jam_masuk_max;
        $shift->jam_pulang_min = $request->jam_pulang_min;
        $shift->jam_pulang_max = $request->jam_pulang_max;
        $shift->tipe           = $request->tipe;
        $shift->status         = $request->status;
        $shift->berlaku_untuk  = $berlaku_untuk_final;

        $shift->save();

        return response()->json(['success' => 'Data Shift Kerja berhasil diperbarui.']);
    }


    public function destroy($id)
    {
        ShiftKerja::destroy($id);
        return response()->json(['success' => 'Data Shift Kerja berhasil dihapus.']);
    }
}
