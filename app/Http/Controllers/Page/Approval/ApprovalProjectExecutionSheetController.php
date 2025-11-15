<?php

namespace App\Http\Controllers\Page\Approval;

use App\Http\Controllers\Controller;
use App\Models\KategoriService;
use App\Models\ProjectSheet;
use App\Models\ProjectSheetApproval;
use App\Models\ServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApprovalProjectExecutionSheetController extends Controller
{
    public function getData()
    {
        $query = ProjectSheetApproval::latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('project_no', function ($row) {
                return $row->pes->project_no;
            })
            ->addColumn('request_by', function ($row) {
                return $row->karyawan->fullName;
            })
            ->addColumn('request_at', function ($row) {
                $parse = Carbon::parse($row->created_at);

                return $parse->translatedFormat('l, ').
                    $parse->locale('en-ID')->translatedFormat('d M Y H:i');
            })
            ->addColumn('status', function ($row) {
                if ($row->is_approved === 1) {
                    return '<div class="text-center">
                                <button type="button" class="btn btn-block btn-sm bg-gradient-success">Approved</button>
                            </div>';
                } elseif ($row->is_rejected === 1) {
                    return '<div class="text-center">
                                <button type="button" class="btn btn-block btn-sm bg-gradient-danger">Rejected</button>
                            </div>';
                } else {
                    return '<div class="text-center">
                        <button type="button" class="btn btn-block btn-sm bg-gradient-info">Waiting Approval</button>
                        </div>';
                }
            })
            ->addColumn('action', function ($row) {
                return '<div class="text-center">
                            <a href="'.route('v1.approval.pes.show', $row->id_project).'" class="btn btn-sm bg-gradient-warning me-2"><i class="fas fa-search mr-2"></i>Lihat Permintaan</a>
                        </div>';
            })
            ->rawColumns([
                'action',
                'status',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.approval.pes.index');
    }

    public function show($id)
    {
        $data = ProjectSheet::query()->with('project_sheet_detail')->where('id_project', $id)->first();
        $projectSheet = ProjectSheet::query()->with('project_sheet_detail')->where('id_project', $id)->first();
        $serviceKategori = KategoriService::orderByRaw('CAST(sort_num AS UNSIGNED) ASC')->get();
        $serviceType = ServiceType::orderByRaw('CAST(sort_num AS UNSIGNED) ASC')->get();
        $approvalData = ProjectSheetApproval::where('id_project', $id)->first();

        return view('page.v1.approval.pes.show', compact('data', 'projectSheet', 'serviceKategori', 'serviceType', 'approvalData'));
    }

    public function approveOrReject(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:project_sheets,id_project',
            'action' => 'required|in:approve,reject',
            'approval_note' => 'nullable|string|max:1000',
        ]);

        try {
            \DB::beginTransaction();

            $project = ProjectSheet::where('id_project', $request->project_id)->first();
            $status = $request->action === 'approve' ? 'approved' : 'rejected';
            $project->update([
                'received_by' => auth()->user()->id,
            ]);
            $approvalData = ProjectSheetApproval::where('id_project', $request->project_id)->first();
            if ($status === 'approved') {
                $approvalData->update([
                    'response_by' => auth()->user()->id_user,
                    'response_at' => now(),
                    'is_approved' => 1,
                    'note' => $request->approval_note,
                ]);
            } else {
                $approvalData->update([
                    'response_by' => auth()->user()->id_user,
                    'response_at' => now(),
                    'is_rejected' => 1,
                    'note' => $request->approval_note,
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => $status === 'approved'
                    ? 'Project berhasil di-approve.'
                    : 'Project telah ditolak.',
                'redirect' => route('v1.approval.pes.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses data.',
                'error' => $th->getMessage(),
            ]);
        }
    }
}
