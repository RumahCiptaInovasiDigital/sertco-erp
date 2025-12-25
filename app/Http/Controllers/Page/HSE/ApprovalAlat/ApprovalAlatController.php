<?php

namespace App\Http\Controllers\Page\HSE\ApprovalAlat;

use App\Http\Controllers\Controller;
use App\Models\DataPeralatan;
use App\Models\PeminjamanAlat;
use App\Models\PeminjamanAlatDetail;
use App\Models\PeminjamanAlatApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class ApprovalAlatController extends Controller
{
    public function getData(Request $request)
    {
        if (auth()->user()->jabatan == 'Administrator') {
            $query = PeminjamanAlat::query()->latest()->get();
        } else {
            $query = PeminjamanAlat::query()->where('nikUser', auth()->user()->nik)->latest()->get();
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('nikUser', function ($row) {
                return $row->karyawan->fullName ?? '-';
            })
            ->editColumn('approved', function ($row) {
                if ($row->approval->approved === '0') {
                    return '<div class="text-center">
                                <span class="badge badge-info text-center">Menunggu Persetujuan</span>
                            </div>';
                } elseif ($row->approval->approved === '1') {
                    return '<div class="text-center">
                                <span class="badge badge-success text-center">Disetujui</span>
                            </div>';
                } else {
                    return '<div class="text-center">
                        <span class="badge badge-danger text-center">Ditolak</span>
                        </div>';
                }})
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.approval-alat.show', $row->id).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-eye"></i> Lihat Peminjam</a>';
            })
            ->rawColumns([
                'approved',
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.approval.peminjamanAlat.index');
    }

    public function show($id)
    {
        $dataPeminjaman = PeminjamanAlat::query()
        ->where('id', $id)
        ->first();

        $dataDetail = PeminjamanAlatDetail::query()
        ->where('idPeminjaman', $id)
        ->get();

        $dataApproved = PeminjamanAlatApproval::query()
        ->where('idPeminjamanAlat', $id)
        ->first();
// dd($dataApproved);
        return view('page.v1.approval.peminjamanAlat.show', compact('dataDetail', 'dataPeminjaman', 'dataApproved'));
    }

    public function approveOrReject(Request $request)
    {
        $request->validate([
            
            'action' => 'required|in:approve,reject',
            'catatan_approved' => 'nullable|string|max:1000'
        ]);

        try {

            DB::beginTransaction();

            $approvalData = PeminjamanAlatApproval::where('id', $request->id)->first();

            $user = auth()->user();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah. Autentikasi gagal.'
                ], 401);
            }

            $status = $request->action === 'approve' ? 'approved' : 'rejected';
            $approvedValue = $request->action === 'approve' ? '1' : '2';
            $idUser = $user->id_user ?? null;

            $approvalData->update([
                'response_by'        => $idUser,
                'approved'           => $approvedValue,
                'catatan_approved'   => $request->catatan_approved,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => $status === 'approved'
                    ? 'Peminjaman alat berhasil di-approve.'
                    : 'Peminjaman alat telah ditolak.',
                'redirect' => route('v1.approval-alat.index'),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
