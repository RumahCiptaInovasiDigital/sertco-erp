<?php

namespace App\Http\Controllers\Presensi;

use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\JadwalKaryawan; // Impor model JadwalKaryawan

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'title' => 'Data Presensi',
            'subtitle' => 'Halaman Data Presensi Karyawan',
            'selectedDate' => $request->input('tanggal', Carbon::now()->toDateString()),
        ];

        return view('page.presensi.data', $data);
    }

    public function data(Request $request)
    {
        $selectedDate = $request->get('tanggal', Carbon::now()->toDateString());

        $query = Presensi::with([
            'karyawan.departemen',
            'karyawan.jabatan',
            'officeMasuk',
            'officePulang'
        ])->whereBetween('tanggal', [$selectedDate . ' 00:00:00', $selectedDate . ' 23:59:59']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('lat_masuk', function($row) {
                if ($row->koordinat_masuk) {
                    $coords = explode(',', $row->koordinat_masuk);
                    return isset($coords[0]) ? trim($coords[0]) : null;
                }
                return null;
            })
            ->addColumn('lng_masuk', function($row) {
                if ($row->koordinat_masuk) {
                    $coords = explode(',', $row->koordinat_masuk);
                    return isset($coords[1]) ? trim($coords[1]) : null;
                }
                return null;
            })
            ->addColumn('lat_pulang', function($row) {
                if ($row->koordinat_pulang) {
                    $coords = explode(',', $row->koordinat_pulang);
                    return isset($coords[0]) ? trim($coords[0]) : null;
                }
                return null;
            })
            ->addColumn('lng_pulang', function($row) {
                if ($row->koordinat_pulang) {
                    $coords = explode(',', $row->koordinat_pulang);
                    return isset($coords[1]) ? trim($coords[1]) : null;
                }
                return null;
            })
            ->addColumn('nama_karyawan', function ($row) {
                $nama = $row->karyawan?->fullName ?? 'N/A';
                $nik = $row->karyawan?->nik ?? '-';

                return '<strong>' . $nama . '</strong><br>' .
                    '<small class="text-muted">NIK: ' . $nik . '</small>';
            })
            ->addColumn('type_presensi', function ($row) {
                $status = $row->type_presensi;
                $badgeClass = 'badge-secondary';

                if ($status == 'WFO') {
                    $badgeClass = 'badge-success';
                } elseif ($status == 'WFA') {
                    $badgeClass = 'badge-danger';
                }

                $badge = '<span class="badge ' . $badgeClass . '">' . ($status ?? 'N/A') . '</span>';

                $jamMasuk = $row->jam_harus_masuk_akhir ? Carbon::parse($row->jam_harus_masuk_akhir)->format('H:i') : '--:--';
                $jamPulang = $row->jam_harus_pulang_awal ? Carbon::parse($row->jam_harus_pulang_awal)->format('H:i') : '--:--';
                $schedule = '<br><small class="text-muted">Jadwal: ' . $jamMasuk . ' - ' . $jamPulang . '</small>';

                return $badge . $schedule;
            })
            ->addColumn('kantor', function ($row) {
                $kantorMasuk = $row->officeMasuk?->name ?? '-';
                $kantorPulang = $row->officePulang?->name ?? '-';

                if ($kantorMasuk === $kantorPulang) {
                    return '<span class="badge badge-info">' . $kantorMasuk . '</span>';
                }

                return '<small>Masuk: ' . $kantorMasuk . '<br>Pulang: ' . $kantorPulang . '</small>';
            })
            ->editColumn('jam_masuk', function ($row) {
                if (!$row->jam_masuk) {
                    return '<span class="text-danger text-sm"><i class="fas fa-times-circle"></i> Belum Absen</span>';
                }

                $jamMasuk = Carbon::parse($row->jam_masuk)->format('H:i');
                $jamHarusMasuk = $row->jam_harus_masuk_akhir ? Carbon::parse($row->jam_harus_masuk_akhir)->format('H:i') : null;

                // Tentukan warna berdasarkan ketepatan waktu
                $color = 'success';
                if ($jamHarusMasuk && $jamMasuk > $jamHarusMasuk) {
                    $color = 'danger';
                }

                // Parse koordinat untuk button
                $lat = null;
                $lng = null;
                if ($row->koordinat_masuk) {
                    $coords = explode(',', $row->koordinat_masuk);
                    $lat = isset($coords[0]) ? trim($coords[0]) : null;
                    $lng = isset($coords[1]) ? trim($coords[1]) : null;
                }

                $disabled = (!$lat || !$lng) ? 'disabled' : '';
                $nama = $row->karyawan?->fullName ?? 'N/A';

                return '<button class="btn btn-outline-' . $color . ' btn-sm rounded-pill view-location"
                   data-lat="' . ($lat ?? '') . '"
                   data-lng="' . ($lng ?? '') . '"
                   data-type="masuk"
                   data-name="' . htmlspecialchars($nama) . '"
                   title="Klik untuk lihat lokasi absen masuk"
                   ' . $disabled . '>
                <i class="fas fa-map-marker-alt"></i> ' . $jamMasuk . '
            </button>';
            })
            ->editColumn('jam_pulang', function ($row) {
                if (!$row->jam_pulang) {
                    return '<span class="text-warning text-sm"><i class="fas fa-clock"></i> Belum Pulang</span>';
                }

                $jamPulang = Carbon::parse($row->jam_pulang)->format('H:i');
                $jamHarusPulang = $row->jam_harus_pulang_awal ? Carbon::parse($row->jam_harus_pulang_awal)->format('H:i') : null;

                // Tentukan warna berdasarkan ketepatan waktu
                $color = 'warning'; // Default kuning untuk pulang
                if ($jamHarusPulang && $jamPulang >= $jamHarusPulang) {
                    $color = 'success'; // Hijau jika pulang sesuai/setelah jadwal
                }

                // Parse koordinat untuk button
                $lat = null;
                $lng = null;
                if ($row->koordinat_pulang) {
                    $coords = explode(',', $row->koordinat_pulang);
                    $lat = isset($coords[0]) ? trim($coords[0]) : null;
                    $lng = isset($coords[1]) ? trim($coords[1]) : null;
                }

                $disabled = (!$lat || !$lng) ? 'disabled' : '';
                $nama = $row->karyawan?->fullName ?? 'N/A';

                return '<button class="btn btn-outline-' . $color . ' btn-sm rounded-pill view-location"
                   data-lat="' . ($lat ?? '') . '"
                   data-lng="' . ($lng ?? '') . '"
                   data-type="pulang"
                   data-name="' . htmlspecialchars($nama) . '"
                   title="Klik untuk lihat lokasi absen pulang"
                   ' . $disabled . '>
                <i class="fas fa-map-marker-alt"></i> ' . $jamPulang . '
            </button>';
            })

            ->editColumn('status', function ($row) {
                $status = $row->status;
                $badgeClass = 'badge-secondary';
                $statusText = $status ?? 'N/A';

                switch ($status) {
                    case 'good':
                        $badgeClass = 'badge-success';
                        $statusText = 'Tepat Waktu';
                        break;
                    case 'late':
                        $badgeClass = 'badge-warning';
                        $statusText = 'Terlambat';
                        break;
                    case 'uncompleted':
                        $badgeClass = 'badge-warning';
                        $statusText = 'Belum Lengkap';
                        break;
                    case 'leave':
                        $badgeClass = 'badge-info';
                        $statusText = 'Cuti';
                        break;
                    case 'sick':
                        $badgeClass = 'badge-info';
                        $statusText = 'Sakit';
                        break;
                    case 'absent':
                        $badgeClass = 'badge-danger';
                        $statusText = 'Tidak Hadir';
                        break;
                    case 'overtime':
                        $badgeClass = 'badge-primary';
                        $statusText = 'Lembur';
                        break;
                    case 'onduty':
                        $badgeClass = 'badge-primary';
                        $statusText = 'Tugas Luar';
                        break;
                }

                $result = '<span class="badge ' . $badgeClass . '">' . $statusText . '</span>';

                if ($row->total_jam_kerja) {
                    $result .= '<br><small class="text-muted">' . number_format($row->total_jam_kerja, 1) . ' jam</small>';
                }

                return $result;
            })
            ->addColumn('keterangan', function ($row) {
                return $row->keterangan ?? '-';
            })
            ->addColumn('action', function ($row) {
                $detailButton = '<button class="btn btn-sm btn-info detail-btn" data-id="' . $row->id . '" title="Detail"><i class="fas fa-eye"></i></button>';
                return $detailButton;
            })
            ->filterColumn('nama_karyawan', function ($query, $keyword) {
                $query->whereHas('karyawan', function ($q) use ($keyword) {
                    $q->where('fullName', 'like', "%{$keyword}%");
                });
            })
            ->orderColumn('nama_karyawan', function ($query, $order) {
                $query->leftJoin('data_karyawans', 'presensi.data_karyawan_id', '=', 'data_karyawans.id')
                    ->orderBy('data_karyawans.fullName', $order)
                    ->select('presensi.*');
            })
            ->orderColumn('type_presensi', function ($query, $order) {
                $query->orderBy('presensi.type_presensi', $order);
            })
            ->orderColumn('jam_masuk', function ($query, $order) {
                $query->orderBy('presensi.jam_masuk', $order);
            })
            ->orderColumn('jam_pulang', function ($query, $order) {
                $query->orderBy('presensi.jam_pulang', $order);
            })
            ->orderColumn('status', function ($query, $order) {
                $query->orderBy('presensi.status', $order);
            })
            ->rawColumns(['status', 'action', 'nama_karyawan', 'type_presensi', 'kantor', 'jam_masuk', 'jam_pulang', ])
            ->make(true);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data_karyawan_id' => 'required|exists:data_karyawans,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i:s',
            'jam_pulang' => 'nullable|date_format:H:i:s',
            'status' => 'required|string|in:good,late,uncompleted,leave,sick,absent,overtime,onduty',
            'type_presensi' => 'required|string|in:WFO,WFA',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Secara dinamis mengambil jadwal kerja karyawan
        $jadwal = JadwalKaryawan::where('data_karyawan_id', $request->data_karyawan_id)
            ->where('tanggal', $request->tanggal)
            ->first();

        if (!$jadwal) {
            return response()->json(['error' => 'Jadwal kerja untuk karyawan ini tidak ditemukan pada tanggal tersebut.'], 404);
        }

        $dataToCreate = $request->only('data_karyawan_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status', 'type_presensi', 'keterangan');
        $dataToCreate['jam_harus_masuk_awal'] = $jadwal->jam_masuk;
        $dataToCreate['jam_harus_masuk_akhir'] = $jadwal->jam_masuk; // Asumsi sama, bisa disesuaikan
        $dataToCreate['jam_harus_pulang_awal'] = $jadwal->jam_pulang;
        $dataToCreate['jam_harus_pulang_akhir'] = $jadwal->jam_pulang; // Asumsi sama, bisa disesuaikan

        $presensi = Presensi::create($dataToCreate);

        return response()->json(['success' => 'Data presensi berhasil ditambahkan.', 'data' => $presensi], 201);
    }

    public function edit($id)
    {
        $presensi = Presensi::with([
            'karyawan.departemen',
            'karyawan.jabatan',
            'officeMasuk',
            'officePulang'
        ])->find($id);

        if (!$presensi) {
            return response()->json(['error' => 'Data presensi tidak ditemukan.'], 404);
        }
        return response()->json($presensi);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jam_masuk' => 'nullable|date_format:H:i:s',
            'jam_pulang' => 'nullable|date_format:H:i:s',
            'status' => 'required|string|in:good,late,uncompleted,leave,sick,absent,overtime,onduty',
            'type_presensi' => 'required|string|in:WFO,WFA',
            'keterangan' => 'nullable|string',
            'total_jam_kerja' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $presensi = Presensi::find($id);
        if (!$presensi) {
            return response()->json(['error' => 'Data presensi tidak ditemukan.'], 404);
        }

        // Menghitung total jam kerja jika jam masuk dan pulang diisi
        $dataToUpdate = $request->only('jam_masuk', 'jam_pulang', 'status', 'type_presensi', 'keterangan', 'total_jam_kerja');

        if ($request->jam_masuk && $request->jam_pulang) {
            $jamMasuk = strtotime($presensi->tanggal . ' ' . $request->jam_masuk);
            $jamPulang = strtotime($presensi->tanggal . ' ' . $request->jam_pulang);
            $totalDetik = $jamPulang - $jamMasuk;
            $dataToUpdate['total_jam_kerja'] = round($totalDetik / 3600, 2);
        }

        $presensi->update($dataToUpdate);

        return response()->json(['success' => 'Data presensi berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $presensi = Presensi::find($id);
        if (!$presensi) {
            return response()->json(['error' => 'Data presensi tidak ditemukan.'], 404);
        }

        $presensi->delete();

        return response()->json(['success' => 'Data presensi berhasil dihapus.']);
    }

    public function showLocation($id)
    {
        $presensi = Presensi::find($id);

        if (!$presensi) {
            return response()->json(['error' => 'Data presensi tidak ditemukan.'], 404);
        }

        return response()->json([
            'koordinat_masuk' => $presensi->koordinat_masuk,
            'jam_masuk' => $presensi->jam_masuk ? Carbon::parse($presensi->jam_masuk)->format('H:i:s') : null,
            'koordinat_pulang' => $presensi->koordinat_pulang,
            'jam_pulang' => $presensi->jam_pulang ? Carbon::parse($presensi->jam_pulang)->format('H:i:s') : null,
        ]);
    }

    public function summary(Request $request)
    {
        $selectedDate = $request->get('tanggal', Carbon::now()->toDateString());

        // Query base untuk tanggal yang dipilih
        $baseQuery = Presensi::whereDate('tanggal', $selectedDate);

        // Total presensi
        $total = (clone $baseQuery)->count();

        // Status presensi
        $good = (clone $baseQuery)->where('status', 'good')->count();
        $late = (clone $baseQuery)->where('status', 'late')->count();
        $absent = (clone $baseQuery)->where('status', 'absent')->count();
        $leave = (clone $baseQuery)->where('status', 'leave')->count();
        $sick = (clone $baseQuery)->where('status', 'sick')->count();
        $overtime = (clone $baseQuery)->where('status', 'overtime')->count();
        $onduty = (clone $baseQuery)->where('status', 'onduty')->count();
        $uncompleted = (clone $baseQuery)->where('status', 'uncompleted')->count();

        // Tipe presensi
        $wfa = (clone $baseQuery)->where('type_presensi', 'WFA')->count();
        $wfo = (clone $baseQuery)->where('type_presensi', 'WFO')->count();

        return response()->json([
            'total' => $total,
            // Status
            'good' => $good,
            'late' => $late,
            'absent' => $absent,
            'leave' => $leave,
            'sick' => $sick,
            'overtime' => $overtime,
            'onduty' => $onduty,
            'uncompleted' => $uncompleted,
            // Tipe
            'wfa' => $wfa,
            'wfo' => $wfo,
        ]);
    }
}
