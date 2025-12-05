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
                                <span class="badge badge-info">Menunggu Persetujuan</span>
                            </div>';
                } elseif ($row->approval->approved === '1') {
                    return '<div class="text-center">
                                <span class="badge badge-danger w-100 d-block text-center">Ditolak</span>
                            </div>';
                } else {
                    return '<div class="text-center">
                        <span class="badge badge-success text-center">Disetujui</span>
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

        return view('page.v1.approval.peminjamanAlat.show', compact('dataDetail', 'dataPeminjaman'));
    }
}
