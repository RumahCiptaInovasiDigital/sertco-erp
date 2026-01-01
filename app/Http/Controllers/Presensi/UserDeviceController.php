<?php

namespace App\Http\Controllers\Presensi;

use App\Models\UserDevice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserDeviceController extends Controller
{
    // Halaman Manajemen Device
    public function index()
    {
        return view('page.device.manajemen');
    }

    // Data untuk DataTables Manajemen
    public function data(Request $request)
    {
        $query = UserDevice::with(['user'])
            ->whereIn('status', ['active', 'blocked'])
            ->select('user_devices.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama', function($row) {
                return $row->user->karyawan->fullName ?? '-';
            })
            ->addColumn('nik', function($row) {
                return $row->user->nik ?? '-';
            })

            ->addColumn('status_badge', function($row) {
                if ($row->blocked_at != null) {
                    return '<span class="badge badge-danger">Diblokir</span>';
                }
                return '<span class="badge badge-success">Aktif</span>';
            })
            ->addColumn('action', function($row) {
                $btn = '<div class="btn-group" role="group">';

                if ($row->blocked_at != null) {
                    $btn .= '<button type="button" class="btn btn-sm btn-success unblock-btn" data-id="'.$row->id.'" title="Aktifkan">
                                <i class="fas fa-unlock"></i> Unblock
                            </button>';
                } else {
                    $btn .= '<button type="button" class="btn btn-sm btn-danger block-btn" data-id="'.$row->id.'" title="Blokir">
                                <i class="fas fa-lock"></i> Block
                            </button>';
                }

                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    // Block Device
    public function block($id)
    {
        $device = UserDevice::findOrFail($id);
        $device->status = 'blocked';
        $device->reason_blocked = request('alasan');
        $device->blocked_at = now();
        $device->validator_id = auth()->user()->id_user;
        $device->save();

        return response()->json(['message' => 'Device berhasil diblokir']);
    }


    // Unblock Device
    public function unblock($id)
    {
        try {
            $device = UserDevice::findOrFail($id);
            $device->status = 'active';
            $device->blocked_at = null;
            $device->validator_id = null;
            $device->reason_blocked = null;
            $device->save();

            return response()->json([
                'success' => true,
                'message' => 'Device berhasil diaktifkan kembali'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan device: ' . $e->getMessage()
            ], 500);
        }
    }

    // Halaman Approval Device
    public function approval()
    {
        return view('page.device.approval');
    }

    // Data untuk DataTables Approval
    public function approvalData(Request $request)
    {
        $query = UserDevice::with(['user'])
            ->orWhere('status','inactive')
            ->orWhere('register_new','<>',null)
            ->select('user_devices.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama', function($row) {
                return $row->user->karyawan->fullName ?? '-';
            })
            ->addColumn('nik', function($row) {
                return $row->user->nik ?? '-';
            })
            ->addColumn('device_info', function($row) {
                $registernew = $row->register_new ? $row->register_new : [];
                $row->device_name = $registernew['device_name'] ?? $row->device_name;
                $row->device_model = $registernew['device_model'] ?? 'Unknown Model';
                return $row->device_name . '<br><small class="text-muted">' . $row->device_model . '</small>';
            })
            ->editColumn('device_id', function($row) {
                $registernew = $row->register_new ? $row->register_new : [];
                return $registernew['device_id'] ?? $row->device_id;
            })
            ->addColumn('created_at_formatted', function($row) {
                return $row->created_at->format('d/m/Y H:i');
            })
            ->addColumn('action', function($row) {
                return '<div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-success approve-btn" data-id="'.$row->id.'" title="Setujui">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger reject-btn" data-id="'.$row->id.'" title="Tolak">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['device_info', 'action'])
            ->make(true);
    }

    // Approve Device
    public function approve($id)
    {
        try {
            $device = UserDevice::findOrFail($id);
            $device->status = 'active';
            $regusternew = $device->register_new ? $device->register_new : [];
            $device->device_id = $regusternew['device_id'] ?? $device->device_id;
            $device->device_name = $regusternew['device_name'] ?? $device->device_name;
            $device->device_type = $regusternew['device_type'] ?? $device->device_type;
            $device->activate_at = now();
            $device->validator_id = auth()->user()->id_user;
            $device->register_new = null;
            $device->reason_blocked = null;
            $device->blocked_at = null;
            $device->status = 'active';
            $device->save();

            return response()->json([
                'success' => true,
                'message' => 'Device berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui device: ' . $e->getMessage()
            ], 500);
        }
    }

    // Reject Device
    public function reject($id)
    {
        try {
            $device = UserDevice::findOrFail($id);
            $device->delete();

            return response()->json([
                'success' => true,
                'message' => 'Device berhasil ditolak dan dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak device: ' . $e->getMessage()
            ], 500);
        }
    }
}
