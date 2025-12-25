<?php

namespace App\Http\Controllers\Presensi;

use App\Models\Presensi;
use App\Models\PresensiIzin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PresensiIzinController extends Controller
{
    public function index()
    {
        return view('page.presensi.pengajuan-izin');
    }

    public function izinData(Request $request)
    {
        $periode = $request->input('periode', date('Y-m'));
        $query = PresensiIzin::where('created_at', '>=', Carbon::parse($periode)->startOfMonth())
                             ->where('created_at', '<=', Carbon::parse($periode)->endOfMonth())
                             ->with('karyawan');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama', function($row) {
                return $row->karyawan?->fullName ?? '-';
            })
            ->addColumn('jenis_izin', function($row) {
                $badgeClass = match($row->jenis_izin) {
                    'cuti' => 'badge-info',
                    'sakit' => 'badge-warning',
                    'izin' => 'badge-primary',
                    'tugas' => 'badge-success',
                    default => 'badge-secondary'
                };
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->jenis_izin) . '</span>';
            })
            ->addColumn('tanggal_mulai', function($row) {
                return Carbon::parse($row->tanggal_mulai)->translatedFormat('d F Y');
            })
            ->addColumn('tanggal_selesai', function($row) {
                return Carbon::parse($row->tanggal_selesai)->translatedFormat('d F Y');
            })
            ->addColumn('file_pendukung', function($row) {
                if ($row->file_pendukung) {
                    return '<a href="' . asset('storage/' . $row->file_pendukung) . '" target="_blank" class="btn btn-sm btn-info">
                        <i class="fas fa-file-pdf"></i> Lihat
                    </a>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('status', function($row) {
                $badgeClass = match($row->status) {
                    'disetujui' => 'badge-success',
                    'ditolak' => 'badge-danger',
                    'pending' => 'badge-warning',
                    'pengajuan' => 'badge-info',
                    default => 'badge-secondary'
                };
                return '<span class="badge ' . $badgeClass . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('aksi', function($row) {
                $approveBtn = '';
                $rejectBtn = '';

                if ($row->status === 'pengajuan') {
                    $approveBtn = '<button class="btn btn-success btn-sm approve-btn" data-id="' . $row->id . '">
                    <i class="fas fa-check"></i>
                </button>';
                    $rejectBtn = '<button class="btn btn-danger btn-sm reject-btn ml-1" data-id="' . $row->id . '">
                    <i class="fas fa-times"></i>
                </button>';
                }

                $detailBtn = '<button class="btn btn-info btn-sm detail-btn ml-1" data-id="' . $row->id . '">
                <i class="fas fa-eye"></i>
            </button>';

                return $approveBtn . $rejectBtn . $detailBtn;
            })
            ->rawColumns(['jenis_izin', 'file_pendukung', 'status', 'aksi'])
            ->make(true);
    }

    public function approve(Request $request,$id)
    {
        $izin = PresensiIzin::findOrFail($id);
        $izin->catatan_approver = $request->catatan_approver;
        $izin->approved_by =auth()->user()->id_user;
        $izin->approved_at = now();
        $izin->status = 'disetujui';

        $izin->save();
        return response()->json(['message' => 'Data berhasil disetujui']);
    }

    public function reject(Request $request,$id)
    {
        $izin = PresensiIzin::findOrFail($id);
        $izin->catatan_approver = $request->catatan_approver;
        $izin->approved_by = auth()->user()->id_user;
        $izin->approved_at = now();
        $izin->status = 'ditolak';
        $izin->save();
        return response()->json(['message' => 'Data berhasil ditolak']);
    }

    public function detail($id)
    {
        // Tambahkan with('karyawan')
        $izin = PresensiIzin::with('karyawan')->findOrFail($id);
        return response()->json($izin);
    }

}
