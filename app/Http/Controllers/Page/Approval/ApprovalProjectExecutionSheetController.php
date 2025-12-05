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
use Illuminate\Support\Facades\Hash;

class ApprovalProjectExecutionSheetController extends Controller
{
    public function getData()
    {
        $query = ProjectSheetApproval::latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('project_no', function ($row) {
                // return $row->pes->project_no;
                return '<a href="'.route('v1.pes.show', $row->id_project).'" class="badge badge-primary">'.$row->pes->project_no.' <i class="fas fa-share"></i></a>';
            })
            ->addColumn('request_by', function ($row) {
                return $row->karyawan->fullName ?? '-';
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
                'project_no',
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
            'role' => 'required|in:mkt,to',
            'approval_note' => 'nullable|string|max:1000',
            'password' => 'required|string',
        ]);

        try {
            \DB::beginTransaction();

            $approvalData = ProjectSheetApproval::where('id_project', $request->project_id)->first();

            if (!$approvalData) {
                return response()->json(['success' => false, 'message' => 'Approval data tidak ditemukan.'], 404);
            }

            // VALIDASI PASSWORD
            $user = auth()->user();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah. Autentikasi ulang gagal.'
                ], 401);
            }

            $isApprove = $request->action === 'approve';
            $role = $request->role;
            $idUser = $user->id_user ?? null;

            // === UPDATE APPROVAL STATE ===
            if ($role === 'mkt') {

                $approvalData->disetujui_mkt = $isApprove;
                $approvalData->ditolak_mkt = !$isApprove;
                $approvalData->response_mkt_at = now();
                $approvalData->response_mkt_by = $idUser;
                $approvalData->note_mkt = $request->approval_note;
                $approvalData->save();

                if (!$isApprove) {
                    // Reset T&O state
                    $approvalData->disetujui_to = false;
                    $approvalData->ditolak_to = false;
                    $approvalData->response_to_at = null;
                    $approvalData->response_to_by = null;
                    $approvalData->note_to = null;
                    $approvalData->save();
                }

            } else { // role = T&O

                if (!$approvalData->disetujui_mkt) {
                    return response()->json([
                        'success' => false,
                        'message' => 'T&O tidak dapat melakukan approval sebelum Marketing menyetujui.'
                    ], 422);
                }

                $approvalData->disetujui_to = $isApprove;
                $approvalData->ditolak_to = !$isApprove;
                $approvalData->response_to_at = now();
                $approvalData->response_to_by = $idUser;
                $approvalData->note_to = $request->approval_note;
                $approvalData->save();
            }

            // ==== UPDATE PROGRESS VIA SERVICE ====
            $project = ProjectSheet::where('id_project', $request->project_id)->first();

            (new \App\Services\ProjectExecutionSheet\ProjectProgressService())
                ->updateProgress($project, $approvalData, $role, $isApprove);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => $isApprove ? 'Berhasil disetujui.' : 'Telah ditolak.',
                'redirect' => route('v1.approval.pes.index'),
            ]);

        } catch (\Throwable $th) {
            \DB::rollBack();
            \Log::error('Approval error: '.$th->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses approval.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }


}
