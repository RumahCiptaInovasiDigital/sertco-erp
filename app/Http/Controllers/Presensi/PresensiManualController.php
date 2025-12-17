<?php

namespace App\Http\Controllers\Presensi;

use App\Models\DataKaryawan;
use App\Models\Presensi;
use App\Models\PresensiManual;
use App\Models\ShiftKerja;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;
use Yajra\DataTables\Facades\DataTables;

class PresensiManualController extends Controller
{
    //
    public function index()
    {

        return view('page.presensi.presensi-manual');
    }

    public function data()
    {
        $periode = request('periode', date('Y-m'));
        $query = PresensiManual::whereMonth('tanggal', Carbon::parse($periode)->month)
            ->whereYear('tanggal', Carbon::parse($periode)->year)
            ->with('karyawan');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama', function ($row) {
                return $row->karyawan->fullName ?? '-';
            })
            ->addColumn('tanggal', function ($row) {
                return Carbon::parse($row->tanggal)->translatedFormat('d F Y');
            })
            ->addColumn('jam_masuk', function ($row) {
                return $row->jam_masuk ? Carbon::parse($row->jam_masuk)->format('H:i') : '-';
            })
            ->addColumn('jam_pulang', function ($row) {
                return $row->jam_pulang ? Carbon::parse($row->jam_pulang)->format('H:i') : '-';
            })
            ->addColumn('waktu_input', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('d/m/Y H:i');
            })
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-sm btn-info detail-btn" data-id="' . $row->id . '" title="Detail">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-sm btn-warning edit-btn" data-id="' . $row->id . '" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_id' => 'required|exists:data_karyawans,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            // Tambahkan 'after:jam_masuk' disini
            'jam_pulang' => 'required|after:jam_masuk',
            'lokasi' => 'required|string',
            'alasan' => 'required|string',
        ], [
            'karyawan_id.required' => 'Karyawan harus dipilih',
            'karyawan_id.exists' => 'Data karyawan tidak valid',
            'tanggal.required' => 'Tanggal harus diisi',
            'jam_masuk.required' => 'Jam masuk harus diisi',
            'jam_pulang.required' => 'Jam pulang harus diisi',
            // Pesan error khusus jika jam pulang salah
            'jam_pulang.after' => 'Jam pulang harus lebih akhir dari jam masuk',
            'lokasi.required' => 'Lokasi harus diisi',
            'alasan.required' => 'Alasan harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();
        try {
            $presensi = new PresensiManual();
            $presensi->karyawan_id = $request->karyawan_id;
            $presensi->tanggal = $request->tanggal;
            $presensi->jam_masuk = $request->jam_masuk . ':00'; // Tambah detik
            $presensi->jam_pulang = $request->jam_pulang . ':00'; // Tambah detik
            $presensi->lokasi = $request->lokasi;
            $presensi->alasan = $request->alasan;

            $presensi->approved_by = auth()->user()->id_user;
            $presensi->catatan_approver = $request->catatan_approver ?? '';
            $presensi->save();

            Presensi::updateOrCreate(
                [
                    'data_karyawan_id' => $request->karyawan_id,
                    'tanggal' => $request->tanggal,
                ],
                [
                    // Data yang akan di-insert atau di-update
                    'jam_masuk' => $request->jam_masuk . ':00',
                    'jam_pulang' => $request->jam_pulang . ':00',

                    // Masukkan lokasi ke kolom koordinat (sesuaikan nama kolom di tabel presensi Anda)
                    'koordinat_masuk' => $request->lokasi,
                    'koordinat_pulang' => $request->lokasi,

                    'status' => 'good', // Atau 'hadir', sesuaikan dengan ENUM di tabel presensi

                    'keterangan' => $request->alasan, // Masukkan alasan ke keterangan
                ]
            );
            DB::commit();


            return response()->json([
                'success' => true,
                'message' => 'Data presensi manual berhasil disimpan'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function detail($id)
    {
        try {
            $presensi = PresensiManual::with('karyawan')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'karyawan_id' => $presensi->karyawan_id,
                    'karyawan' => $presensi->karyawan->fullName ?? '-',
                    'nik' => $presensi->karyawan->nik ?? '-',
                    'tanggal' => Carbon::parse($presensi->tanggal)->translatedFormat('d F Y'),
                    'tanggal_raw' => $presensi->tanggal,
                    'jam_masuk' => Carbon::parse($presensi->jam_masuk)->format('H:i'),
                    'jam_masuk_raw' => Carbon::parse($presensi->jam_masuk)->format('H:i'),
                    'jam_pulang' => Carbon::parse($presensi->jam_pulang)->format('H:i'),
                    'jam_pulang_raw' => Carbon::parse($presensi->jam_pulang)->format('H:i'),
                    'lokasi' => $presensi->lokasi,
                    'alasan' => $presensi->alasan,
                    'status' => ucfirst($presensi->status),
                    'approved_by' => $presensi->approved_by ?? '-',
                    'catatan_approver' => $presensi->catatan_approver ?? '-',
                    'created_at' => Carbon::parse($presensi->created_at)->translatedFormat('d F Y H:i'),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'karyawan_id' => 'required|exists:data_karyawans,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required|after:jam_masuk',
            'lokasi' => 'required|string',
            'alasan' => 'required|string',
        ], [
            'karyawan_id.required' => 'Karyawan harus dipilih',
            'tanggal.required' => 'Tanggal harus diisi',
            'jam_masuk.required' => 'Jam masuk harus diisi',
            'jam_pulang.required' => 'Jam pulang harus diisi',
            'jam_pulang.after' => 'Jam pulang harus lebih akhir dari jam masuk',
            'lokasi.required' => 'Lokasi harus diisi',
            'alasan.required' => 'Alasan harus diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        DB::beginTransaction();
        try {
            $presensi = PresensiManual::findOrFail($id);
//            $presensi->karyawan_id = $request->karyawan_id;
            $presensi->tanggal = $request->tanggal;
            $presensi->jam_masuk = $request->jam_masuk . ':00';
            $presensi->jam_pulang = $request->jam_pulang . ':00';
            $presensi->lokasi = $request->lokasi;
            $presensi->alasan = $request->alasan;
            $presensi->catatan_approver = $request->catatan_approver ?? '';
            $presensi->save();

            Presensi::updateOrCreate(
                [
                    'data_karyawan_id' => $presensi->karyawan_id,
                    'tanggal' => $request->tanggal,
                ],
                [
                    // Data yang akan di-insert atau di-update
                    'jam_masuk' => $request->jam_masuk . ':00',
                    'jam_pulang' => $request->jam_pulang . ':00',

                    // Masukkan lokasi ke kolom koordinat (sesuaikan nama kolom di tabel presensi Anda)
                    'koordinat_masuk' => $request->lokasi,
                    'koordinat_pulang' => $request->lokasi,

                    'status' => 'good', // Atau 'hadir', sesuaikan dengan ENUM di tabel presensi

                    'keterangan' => $request->alasan, // Masukkan alasan ke keterangan
                ]
            );
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data presensi manual berhasil diupdate'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getJadwal(Request $request)
    {
        try {
            $karyawanId = $request->get('karyawan_id');
            $tanggal = $request->get('tanggal');

            if (!$karyawanId || !$tanggal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Karyawan dan tanggal harus diisi'
                ], 400);
            }

            // Ambil data karyawan dengan jadwal (relasi: jadwalKerja)
            $karyawan = DataKaryawan::with(['jadwalKerja'])
                ->find($karyawanId);

            if (!$karyawan || !$karyawan->jadwalKerja) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal kerja karyawan tidak ditemukan'
                ], 404);
            }

            // Parse tanggal untuk mendapatkan hari
            $carbonDate = Carbon::parse($tanggal);
            $dayOfWeek = $carbonDate->dayOfWeek; // 0=Minggu, 1=Senin, dst

            // Decode jadwal JSON
            $jadwalJson = $karyawan->jadwalKerja->jadwal_json;
            if (is_string($jadwalJson)) {
                $jadwalJson = json_decode($jadwalJson, true);
            }

            // Cari jadwal untuk hari tersebut
            $jadwalHari = null;
            if (is_array($jadwalJson) && isset($jadwalJson[$dayOfWeek])) {
                $jadwalHari = $jadwalJson[$dayOfWeek];
            }

            if (!$jadwalHari || !isset($jadwalHari['shift_id'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal shift untuk tanggal tersebut'
                ], 404);
            }

            // Ambil data shift
            $shift = ShiftKerja::find($jadwalHari['shift_id']);

            if (!$shift) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data shift tidak ditemukan'
                ], 404);
            }

            // Ambil lokasi dari jadwal JSON (ID branch office)
            $lokasiId = $jadwalHari['lokasi'] ?? null;
            $lokasiData = null;

            if ($lokasiId) {
                $branchOffice = \App\Models\BranchOffice::find($lokasiId);
                if ($branchOffice) {
                    $lokasiData = [
                        'id' => $branchOffice->id,
                        'name' => $branchOffice->name,
                        'city' => $branchOffice->city ?? '',
                        'full_name' => $branchOffice->name . ($branchOffice->city ? ' - ' . $branchOffice->city : '')
                    ];
                }
            }

            // Format jam ke HH:mm
            $jamMasukFormatted = $shift->jam_masuk_max ? Carbon::parse($shift->jam_masuk_max)->format('H:i') : null;
            $jamPulangFormatted = $shift->jam_pulang_min ? Carbon::parse($shift->jam_pulang_min)->format('H:i') : null;

            return response()->json([
                'success' => true,
                'data' => [
                    'shift_nama' => $shift->nama_shift,
                    'jam_masuk_max' => $jamMasukFormatted,
                    'jam_pulang_min' => $jamPulangFormatted,
                    'tipe' => $shift->tipe,
                    'lokasi' => $lokasiData,
                    'hari' => $carbonDate->isoFormat('dddd'), // Format Indonesia
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Error getJadwal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $presensi = PresensiManual::findOrFail($id);

            Presensi::updateOrCreate(
                [
                    'data_karyawan_id' => $presensi->karyawan_id,
                    'tanggal' => $presensi->tanggal,
                ],
                [
                    // Data yang akan di-insert atau di-update
                    'jam_masuk' => null,
                    'jam_pulang' => null,

                    // Masukkan lokasi ke kolom koordinat (sesuaikan nama kolom di tabel presensi Anda)
                    'koordinat_masuk' => null,
                    'koordinat_pulang' => null,

                    'status' => 'uncompleted', // Atau 'hadir', sesuaikan dengan ENUM di tabel presensi

                    'keterangan' => null, // Masukkan alasan ke keterangan
                ]
            );
            $presensi->delete();


            return response()->json([
                'success' => true,
                'message' => 'Data presensi manual berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }


}
