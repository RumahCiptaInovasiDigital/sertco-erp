<?php

namespace App\Http\Controllers\Page\Marketing\ProjectExecutionSheet;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\KategoriService;
use App\Models\ProjectSheet;
use App\Models\ProjectSheetDetail;
use App\Models\Role;
use App\Models\ServiceType;
use App\Services\ProjectExecutionSheet\ProjectStatusService;
use App\Traits\GenerateProjectNo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProjectExecutionSheetController extends Controller
{
    use GenerateProjectNo;

    public function getData(Request $request, $action)
    {
        if (auth()->user()->jabatan == 'Administrator') {
            if ($action == 'all') {
                $query = ProjectSheet::query()->latest()->get();
            } elseif ($action == 'draft') {
                $query = ProjectSheet::query()->where('is_draft', 1)->latest()->get();
            } elseif ($action == 'non-draft') {
                $query = ProjectSheet::query()->where('is_draft', 0)->latest()->get();
            }
        } else {
            if ($action == 'all') {
                $query = ProjectSheet::query()->where('prepared_by', auth()->user()->id_user)->latest()->get();
            } elseif ($action == 'draft') {
                $query = ProjectSheet::query()->where('prepared_by', auth()->user()->id_user)->where('progress', 100)->latest()->get();
            } elseif ($action == 'non-draft') {
                $query = ProjectSheet::query()->where('prepared_by', auth()->user()->id_user)->where('progress', '!=',100)->latest()->get();
            }
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
                // if ($row->status === 'draft') {
                // } elseif ($row->status === 'progress') {
                //     if ($row->approval) {
                //         if ($row->approval->is_approved === 1) {
                //             return '<div class="text-center">
                //                         <button type="button" class="btn btn-block bg-gradient-success">Approved</button>
                //                     </div>';
                //         } elseif ($row->approval->is_rejected === 1) {
                //             return '<div class="text-center">
                //                         <button type="button" class="btn btn-block bg-gradient-danger">Rejected</button>
                //                     </div>';
                //         } else {
                //             return '<div class="text-center">
                //                 <button type="button" class="btn btn-block bg-gradient-info">Waiting Approval</button>
                //                 </div>';
                //         }
                //     } else {
                //         return '<div class="text-center">
                //                 <i>data tidak valid</i>
                //             </div>';
                //     }
                // }elseif($row->status === 'complete'){
                //     return '<div class="text-center">
                //                 <button type="button" class="btn btn-block bg-gradient-success">Selesai</button>
                //             </div>';
                // }
            })
            ->addColumn('action', function ($row) {
                // if ($row->progres == 0) {
                //     if (!$row->approval) {
                //         return '<div class="text-center">
                //                 <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_project.'\')"><i class="fas fa-trash"></i></button>
                //             </div>';
                //     }

                //     if ($row->approval->is_approved === 1) {
                //         return '<div class="text-center">
                //             <a href="'.route('v1.review.pes.show', $row->id_project).'" class="btn btn-sm btn-success me-2"><i>Review Project</i></a>
                //         </div>';
                //     }
                //     if ($row->approval->is_rejected === 1) {
                //         return '<div class="text-center">
                //             <a href="'.route('v1.review.pes.show', $row->id_project).'" class="btn btn-sm btn-success me-2"><i>Review Project</i></a>
                //         </div>';
                //     }

                //     return '<div class="text-center">
                //             <button class="btn btn-sm btn-success me-2" disabled><i>Review Project</i></button>
                //         </div>';
                // } else {
                    if($row->prepared_by !== auth()->id()){
                        return  '<div class="text-center">
                        <a href="'.route('v1.pes.show', $row->id_project).'#comment" class="btn btn-sm btn-primary me-2"><i class="fas fa-comment"></i></a>
                                </div>';
                    }
                    return '<div class="text-center">
                                <a href="'.route('v1.pes.show', $row->id_project).'" class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></a>
                                <a href="'.route('v1.pes.edit', $row->id_project).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                                <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_project.'\')"><i class="fas fa-trash"></i></button>
                            </div>';
                // }
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
        $data = ProjectSheet::with('project_sheet_detail')->where('status', 'draft')->latest()->limit(5)->get();

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
        $serviceKategori = KategoriService::orderByRaw('CAST(sort_num AS UNSIGNED) ASC')->get();
        $serviceType = ServiceType::orderByRaw('CAST(sort_num AS UNSIGNED) ASC')->get();
        
        return view('page.v1.pes.show', compact('data', 'projectSheet', 'serviceKategori', 'serviceType'));
    }

    public function create()
    {
        $project_no = $this->generateProjectNo();
        $role = Role::orderBy('name')->get();
        $departemen = Departemen::orderBy('name')->get();

        return view('page.v1.pes.create', compact('project_no', 'role', 'departemen'));
    }

    public function store(Request $request, $id_karyawan, $id_Serti)
    {
        $is_draft = $request->has('is_draft') ? true : false;
        $progess = 100;
        if(!$is_draft){
            $progess = 101;
        }

        if (!$is_draft) {
            $validated = $request->validate([
                'nik' => 'required|string|max:255',
                'prepared_by' => 'required|string|max:255',
                'issued_date' => 'required|date',
                'project_no' => 'required|string|max:255',
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

            $projectSheet = ProjectSheet::create([
                'project_no' => $request->project_no,
                'project_detail' => $request->project_detail,
                'prepared_by' => $request->prepared_by,
                'issued_date' => $request->issued_date,
                'signature_by' => null,
                'signature_date' => null,
                'status' => 'draft',
                'progress' => $progess,
            ]);

            // ==== HANDLE FILE UPLOAD ==== //
            $pricedocPath = null;
            $unpricedocPath = null;

            // if (!$is_draft) {
            if ($request->hasFile('priceDoc')) {
                $pricedoc = $request->file('priceDoc');
                $pricedocName = time().'_priced_'.$pricedoc->getClientOriginalName();

                $pricedocPath = public_path('assets/project/'.$request->project_no.'/pricedoc');
                if (!file_exists($pricedocPath)) {
                    mkdir($pricedocPath, 0755, true);
                }
                $pricedoc->move($pricedocPath, $pricedocName);
            }

            if ($request->hasFile('unpriceDoc')) {
                $unpricedoc = $request->file('unpriceDoc');
                $unpricedocName = time().'_priced_'.$unpricedoc->getClientOriginalName();

                $unpricedocPath = public_path('assets/project/'.$request->project_no.'/unpricedoc');
                if (!file_exists($unpricedocPath)) {
                    mkdir($unpricedocPath, 0755, true);
                }
                $unpricedoc->move($unpricedocPath, $unpricedocName);
            }
            // }

            ProjectSheetDetail::create([
                'id_project' => $projectSheet->id_project,
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
                'pricedoc' => $pricedocName,
                'unpricedoc' => $unpricedocName,
            ]);

            DB::commit();

            if ($is_draft) {
                return response()->json([
                    'success' => true,
                    'message' => 'Draft saved successfully.',
                    'redirect' => route('v1.pes.service.index', strtolower($projectSheet->project_no)),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Information saved successfully. Redirect to Services Page.',
                'redirect' => route('v1.pes.service.index', strtolower($projectSheet->project_no)),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

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

        $role = Role::orderBy('name')->get();

        $departemen = Departemen::orderBy('name')->get();

        return view('page.v1.pes.edit', compact('data', 'role', 'departemen'));
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


    public function uploadPriceDoc(Request $request)
    {
        
    }
    public function uploadUnpriceDoc(Request $request)
    {

    }
}
