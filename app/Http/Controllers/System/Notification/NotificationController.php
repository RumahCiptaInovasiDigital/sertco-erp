<?php

namespace App\Http\Controllers\System\Notification;

use App\Http\Controllers\Controller;
use App\Models\DataKaryawan;
use App\Models\MasterNotifikasi;
use App\Models\Notification;
use App\Services\SendNotifToEmployee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function getData()
    {
        $query = MasterNotifikasi::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            // ->editColumn('jenis_notifikasi', function ($row) {
            //     switch ($row->jenis_notifikasi) {
            //         case 'sekali':
            //             return 'Sekali';
            //         case 'daily':
            //             return 'Harian';
            //         case 'weekly':
            //             return 'Mingguan';
            //         case 'monthly':
            //             return 'Bulanan';
            //         case 'yearly':
            //             return 'Tahunan';
            //         default:
            //             return 'Tidak Diketahui';
            //     }
            // })
            ->addColumn('tanggal_notifikasi', function ($row) {
                $parts = [];

                if ($row->jam_notifikasi) {
                    $parts[] = 'Jam: '.\Carbon\Carbon::parse($row->jam_notifikasi)->format('H:i');
                }

                if ($row->hari_notifikasi) {
                    $parts[] = 'Hari: '.$row->hari_notifikasi;
                }

                if ($row->tanggal_notifikasi) {
                    $parts[] = 'Tanggal: '.$row->tanggal_notifikasi;
                }

                if ($row->bulan_notifikasi) {
                    $bulan = \Carbon\Carbon::createFromFormat('!m', $row->bulan_notifikasi)->format('M');
                    $parts[] = 'Bulan: '.$bulan;
                }

                if ($row->jenis_notifikasi === 'sekali' && empty($parts)) {
                    return \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i');
                }

                return implode(', ', $parts);
            })
            ->addColumn('penerima', function ($row) {
                $totalKaryawan = $row->karyawans()->count();
                if ($row->jenis_karyawan === 'all') {
                    return 'Semua Karyawan ('.$totalKaryawan.')';
                } elseif ($row->jenis_karyawan === 'selected') {
                    return 'Karyawan Terpilih ('.$totalKaryawan.')';
                } else {
                    return 'Tidak Diketahui';
                }
            })
            ->addColumn('status', function ($row) {
                return $row->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
            })
            ->addColumn('action', function ($row) {
                return '<a href="'.route('admin.notification.edit', $row->id).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
                'status',
            ])
            ->make(true);
    }

    public function getEmployee($id)
    {
        $query = Notification::query()->where('notification_id', $id)->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('penerima', function ($row) {
                return $row->karyawan->fullName;
            })
            ->addColumn('diterima', function ($row) {
                return $row->sent_at;
            })
            ->addColumn('action', function ($row) {
                return '<a href="'.route('admin.notification.edit', $row->id).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function searchEmployee(Request $request)
    {
        $search = $request->get('search');

        $query = DataKaryawan::query()
            ->select('id', 'fullName', 'namaJabatan')
            ->when($search, function ($q) use ($search) {
                $q->where('fullName', 'LIKE', "%{$search}%")
                  ->orWhere('namaJabatan', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return response()->json($query);
    }

    public function index()
    {
        // $notifikasis = MasterNotifikasi::query()
        //     ->where('is_active', true)
        //     ->get();

        // $all = [];
        // foreach ($notifikasis as $notif) {
        //     $all = $notif->usersNotifikasi;
        // }
        // dd($all);

        return view('page.admin.notification.index');
    }

    public function create()
    {
        return view('page.admin.notification.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'pesan' => 'required|string',
            'jenis_karyawan' => 'required|in:all,selected',
            'jenis_notifikasi' => 'required|in:sekali,daily,weekly,monthly,yearly',
            'jam_notifikasi' => 'nullable',
            'hari_notifikasi' => 'nullable|string',
            'tanggal_notifikasi' => 'nullable|integer',
            'bulan_notifikasi' => 'nullable|integer',
            'is_active' => 'boolean',
            'karyawan' => 'nullable|array',
        ]);
        $is_sent = false;

        \DB::beginTransaction();

        try {
            // 1️⃣ Simpan ke master_notifikasis
            $masterNotif = MasterNotifikasi::create([
                'title' => $validated['title'],
                'pesan' => $validated['pesan'],
                'jenis_karyawan' => $validated['jenis_karyawan'],
                'jenis_notifikasi' => $validated['jenis_notifikasi'],
                'jam_notifikasi' => $validated['jam_notifikasi'] ?? null,
                'hari_notifikasi' => $validated['hari_notifikasi'] ?? null,
                'tanggal_notifikasi' => $validated['tanggal_notifikasi'] ?? null,
                'bulan_notifikasi' => $validated['bulan_notifikasi'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            if ($validated['jenis_notifikasi'] == 'sekali') {
                $is_sent = true;
            }

            $karyawanList = [];

            if ($validated['jenis_karyawan'] === 'all') {
                $karyawanList = DataKaryawan::pluck('id')->toArray();
            } elseif (!empty($validated['karyawan'])) {
                $karyawanList = $validated['karyawan'];
            }

            if (!empty($karyawanList)) {
                (new SendNotifToEmployee())->handle($karyawanList, $masterNotif, $is_sent, null);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dibuat',
                'redirect' => route('admin.notification.index'),
            ]);
        } catch (\Throwable $e) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat notifikasi: '.$e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $data = MasterNotifikasi::find($id);

        return view('page.admin.notification.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'pesan' => 'required|string',
            'jenis_karyawan' => 'required|in:all,selected',
            'jenis_notifikasi' => 'required|in:sekali,daily,weekly,monthly,yearly',
            'jam_notifikasi' => 'nullable',
            'hari_notifikasi' => 'nullable|string',
            'tanggal_notifikasi' => 'nullable|integer',
            'bulan_notifikasi' => 'nullable|integer',
            'is_active' => 'boolean',
            'karyawan' => 'nullable|array',
        ]);

        \DB::beginTransaction();

        try {
            $dataNotif = MasterNotifikasi::find($id);
            // 1️⃣ Simpan ke master_notifikasis
            $dataNotif->update([
                'title' => $validated['title'],
                'pesan' => $validated['pesan'],
                'jenis_karyawan' => $validated['jenis_karyawan'],
                'jenis_notifikasi' => $validated['jenis_notifikasi'],
                'jam_notifikasi' => $validated['jam_notifikasi'] ?? null,
                'hari_notifikasi' => $validated['hari_notifikasi'] ?? null,
                'tanggal_notifikasi' => $validated['tanggal_notifikasi'] ?? null,
                'bulan_notifikasi' => $validated['bulan_notifikasi'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            Notification::query()->where('notification_id', $dataNotif->id)->delete();

            $karyawanList = [];

            if ($validated['jenis_karyawan'] === 'all') {
                $karyawanList = DataKaryawan::pluck('id')->toArray();
            } elseif (!empty($validated['karyawan'])) {
                $karyawanList = $validated['karyawan'];
            }

            if (!empty($karyawanList)) {
                (new SendNotifToEmployee())->handle($karyawanList, $dataNotif);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil di Update',
                'redirect' => route('admin.notification.index'),
            ]);
        } catch (\Throwable $e) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat notifikasi: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID is required.',
            ]);
        }

        $data = MasterNotifikasi::find($id);

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi deleted successfully.',
        ]);
    }

    public function resend($id)
    {
    }
}
