<?php

namespace App\Http\Controllers\Page\Marketing\ProjectExecutionSheet;

use App\Http\Controllers\Controller;
use App\Models\ProjectSheet;
use App\Models\ProjectSheetDetail;
use App\Models\Role;
use App\Services\ProjectExecutionSheet\ProjectStatusService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DraftController extends Controller
{
    public function getDraft()
    {
        if (auth()->user()->jabatan == 'Administrator') {
            $query = ProjectSheet::query()->latest()->get();
            // $query = ProjectSheet::query()->where('prepared_by', auth()->user()->id_user)->orWhere('progress', '!=',100)->latest()->get();
        } else {
            $query = ProjectSheet::query()->where('prepared_by', auth()->user()->id_user)->where('progress', '=',100)->latest()->get();
        }

        // $query = ProjectSheet::query()->where('is_draft', 0)->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('project_no', function ($row) {
                return '<div class="text-center">
                            <a href="'.route('v1.pes.show', $row->id_project).'">
                                <h5><span class="badge badge-success w-100">'.$row->project_no.'</span></h5>
                            </a>
                        </div>';
            })
            ->addColumn('client', function ($row) {
                return $row->project_sheet_detail->client ?? '-';
            })
            ->addColumn('owner', function ($row) {
                return $row->project_sheet_detail->owner ?? '-';
            })
            ->editColumn('prepared_by', function ($row) {
                return $row->preparedBy->fullName ?? '-';
            })
            ->editColumn('signature_by', function ($row) {
                return $row->signatureBy->fullName ?? '-';
            })
            ->editColumn('issued_date', function ($row) {
                $parse = Carbon::parse($row->issued_date);

                return $parse->translatedFormat('l, ').
                    $parse->locale('en-ID')->translatedFormat('d M Y');
            })
            ->editColumn('status', function ($row) {
                return (new ProjectStatusService())->handle($row->progress);
            })
            ->addColumn('action', function ($row) {
                return '<div class="text-center">
                            <a href="'.route('v1.pes.edit', $row->id_project).'" class="btn btn-sm btn-info me-2" data-toggle="tooltip" data-placement="bottom" title="Edit Project">Buka Draft</a>
                            <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_project.'\')" data-toggle="tooltip" data-placement="bottom" title="Hapus Project"><i class="fas fa-trash"></i></button>
                        </div>';
            })
            ->rawColumns([
                'project_no',
                'status',
                'action',
            ])
            ->make(true);
    }
    public function index()
    {
        return view('page.v1.pes.draft.index');
    }

    public function edit($id)
    {
        $data = ProjectSheet::query()
            ->where('id_project', $id)
            ->first();

        return view('page.v1.pes.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $is_draft = $request->has('is_draft') ? true : false;

        if (!$is_draft) {
            $validated = $request->validate([
                'issued_date' => 'required|date',
                'client' => 'required|string|max:255',
                'owner' => 'required|string|max:255',
                'contract_no' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'ph_no' => 'required|string|max:255',
                'email_client' => 'required|string|max:255',
                'hp_no' => 'required|string|max:255',
                'contract_description' => 'required|string',
                'contract_period' => 'required|string',
                'payment_term' => 'required|string',
                'schedule_start' => 'required|date',
                'schedule_end' => 'required|date',
                'project_detail' => 'required|string',
            ]);
        }

        try {
            DB::beginTransaction();
            $projectSheet = ProjectSheet::query()->where('id_project', $id)->first();
            $projectSheet->update([
                'project_detail' => $request->project_detail,
                'issued_date' => $request->issued_date,
                'signature_by' => null,
                'signature_date' => null,
                'to' => $request->to,
                'attn' => $request->attn,
                'received_by' => null,
                'is_draft' => $is_draft,
            ]);

            $projectDetail = ProjectSheetDetail::query()->where('id_project', $id)->first();
            $projectDetail->update([
                'client' => $request->client,
                'owner' => $request->owner,
                'contract_no' => $request->contract_no,
                'contact_person' => $request->contact_person,
                'ph_no' => $request->ph_no,
                'email_client' => $request->email_client,
                'hp_no' => $request->hp_no,
                'contract_description' => $request->contract_description,
                'contract_period' => $request->contract_period,
                'payment_term' => $request->payment_term,
                'schedule_start' => $request->schedule_start,
                'schedule_end' => $request->schedule_end,
            ]);
            DB::commit();

            if ($is_draft) {
                return response()->json([
                    'success' => true,
                    'message' => 'Draft saved successfully.',
                    'redirect' => route('v1.pes.service.edit', strtolower($projectSheet->project_no)),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Information updated successfully. Redirect to Services Page.',
                'redirect' => route('v1.pes.service.edit', strtolower($projectSheet->project_no)),
            ]);
        } catch (\Throwable $th) {
            // throw $th;

            DB::rollBack();
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

        $data = ProjectSheet::query()
            ->where('id_project', $id)
            ->first();

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
