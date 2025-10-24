<?php

namespace App\Http\Controllers\Page\ProjectExecutionSheet;

use App\Http\Controllers\Controller;
use App\Models\KategoriService;
use App\Models\ProjectSheet;
use App\Models\ProjectSheetDetail;
use App\Models\Role;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectExecutionSheetController extends Controller
{
    public function getData(Request $request)
    {
        $query = ProjectSheet::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('project_no', function ($row) {
                return '<div class="text-center">
                            <h4><span class="badge badge-pill badge-info">'.$row->project_no.'</span></h4>
                        </div>';
            })
            ->editColumn('prepared_by', function ($row) {
                return $row->user->fullname ?? '-';
            })
            ->editColumn('issued_date', function ($row) {
                return $row->issued_date ?? '-';
            })
            ->editColumn('to', function ($row) {
                return $row->toRole->name ?? '-';
            })
            ->editColumn('attn', function ($row) {
                return $row->attnRole->name ?? '-';
            })
            ->editColumn('is_draft', function ($row) {
                if ($row->is_draft === 1) {
                    return '<div class="text-center">
                                <button type="button" class="btn btn-block bg-gradient-warning">Draft</button>
                            </div>';
                }

                return '<div class="text-center">
                            -
                        </div>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="text-center">
                            <a href="'.route('v1.pes.show', $row->id_project).'" class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></a>
                            <a href="'.route('v1.pes.edit', $row->id_project).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_project.'\')"><i class="fas fa-trash"></i></button>
                        </div>';
            })
            ->rawColumns([
                'project_no',
                'is_draft',
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        $data = ProjectSheet::with('project_sheet_detail')->orderBy('created_at', 'desc')->get();

        return view('page.v1.pes.index', compact('data'));
    }

    public function show($id)
    {
        $data = ProjectSheet::query()
            ->where('id_project', $id)
            ->first();
        $projectSheet = ProjectSheet::query()
        ->where('id_project', $id)
        ->first();
        $serviceKategori = KategoriService::all();
        $serviceType = ServiceType::all();

        return view('page.v1.pes.show', compact('data', 'projectSheet', 'serviceKategori', 'serviceType'));
    }

    public function create()
    {
        $today = now()->format('ymd');
        $prefix = 'PRJ'.$today;

        // find the latest project_no for today
        $latest = ProjectSheet::where('project_no', 'like', $prefix.'%')
            ->orderBy('project_no', 'desc')
            ->value('project_no');

        // determine next sequence (3 digits, leading zeros)
        $lastSeq = 0;
        if ($latest) {
            $lastSeq = (int) substr($latest, strlen($prefix));
        }
        $nextSeq = str_pad($lastSeq + 1, 3, '0', STR_PAD_LEFT);

        $project_no = $prefix.$nextSeq;

        $role = Role::orderBy('name')->get();

        return view('page.v1.pes.create', compact('project_no', 'role'));
    }

    public function store(Request $request)
    {
        $is_draft = $request->has('is_draft') ? true : false;

        if (!$is_draft) {
            $validated = $request->validate([
                'nik' => 'required|string|max:255',
                'prepared_by' => 'required|string|max:255',
                'issued_date' => 'required|date',
                'to' => 'required|string|max:255',
                'attn' => 'required|string|max:255',
                'project_no' => 'required|string|max:255',
                'client' => 'required|string|max:255',
                'owner' => 'required|string|max:255',
                'contract_no' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'ph_no' => 'required|string|max:255',
                'fax_no' => 'required|string|max:255',
                'hp_no' => 'required|string|max:255',
                'contract_description' => 'required|string',
                'contract_period' => 'required|string',
                'payment_term' => 'required|string',
                'schedule' => 'required|date',
                'project_detail' => 'required|string',
            ]);
        }

        try {
            \DB::beginTransaction();

            $projectSheet = ProjectSheet::create([
                'project_no' => $request->project_no,
                'project_detail' => $request->project_detail,
                'prepared_by' => $request->prepared_by,
                'issued_date' => $request->issued_date,
                'signature_date' => null,
                'to' => $request->to,
                'attn' => $request->attn,
                'received_by' => null,
                'is_draft' => $is_draft,
            ]);

            ProjectSheetDetail::create([
                'id_project' => $projectSheet->id_project,
                'client' => $request->client,
                'owner' => $request->owner,
                'contract_no' => $request->contract_no,
                'contact_person' => $request->contact_person,
                'ph_no' => $request->ph_no,
                'fax_no' => $request->fax_no,
                'hp_no' => $request->hp_no,
                'contract_description' => $request->contract_description,
                'contract_period' => $request->contract_period,
                'payment_term' => $request->payment_term,
                'schedule' => $request->schedule,
            ]);

            \DB::commit();

            if ($is_draft) {
                return response()->json([
                    'success' => true,
                    'message' => 'Draft saved successfully.',
                    'redirect' => route('v1.pes.index'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Information saved successfully. Redirect to Services Page.',
                'redirect' => route('v1.pes.service.index', strtolower($projectSheet->project_no)),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to save information. '.$th->getMessage(),
            ], 500);
        }
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
                'to' => 'required|string|max:255',
                'attn' => 'required|string|max:255',
                'client' => 'required|string|max:255',
                'owner' => 'required|string|max:255',
                'contract_no' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'ph_no' => 'required|string|max:255',
                'fax_no' => 'required|string|max:255',
                'hp_no' => 'required|string|max:255',
                'contract_description' => 'required|string',
                'contract_period' => 'required|string',
                'payment_term' => 'required|string',
                'schedule' => 'required|date',
                'project_detail' => 'required|string',
            ]);
        }

        try {
            \DB::beginTransaction();
            $projectSheet = ProjectSheet::query()->where('id_project', $id)->first();
            $projectSheet->update([
                'project_detail' => $request->project_detail,
                'issued_date' => $request->issued_date,
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
                'fax_no' => $request->fax_no,
                'hp_no' => $request->hp_no,
                'contract_description' => $request->contract_description,
                'contract_period' => $request->contract_period,
                'payment_term' => $request->payment_term,
                'schedule' => $request->schedule,
            ]);
            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Information updated successfully. Redirect to Services Page.',
                'redirect' => route('v1.pes.service.edit', strtolower($projectSheet->project_no)),
            ]);
        } catch (\Throwable $th) {
            // throw $th;

            \DB::rollBack();
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
