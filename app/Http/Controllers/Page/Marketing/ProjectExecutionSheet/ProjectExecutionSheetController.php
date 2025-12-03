<?php

namespace App\Http\Controllers\Page\Marketing\ProjectExecutionSheet;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\KategoriService;
use App\Models\ProjectSheet;
use App\Models\ProjectSheetDetail;
use App\Models\ProjectSheetLog;
use App\Models\Role;
use App\Models\ServiceType;
use App\Services\ProjectExecutionSheet\CreateProjectLogService;
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
        // if (auth()->user()->jabatan == 'Administrator') {
        //     $query = ProjectSheet::query()->Where('progress', '!=',100)->latest()->get();
        //     // $query = ProjectSheet::query()->where('prepared_by', auth()->user()->id_user)->orWhere('progress', '!=',100)->latest()->get();
        // } else {
        // }

        $query = ProjectSheet::query()->where('progress', '!=',100)->latest()->get();

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
            ->addColumn('batas_waktu', function ($row) {
                $deadline = Carbon::parse($row->updated_at)->addHours(24);
                $now = Carbon::now();

                if ($now->gte($deadline)) {
                    return '<div class="text-center">
                                <span class="badge badge-danger">Expired</span>
                            </div>';
                }

                $seconds = $deadline->diffInSeconds($now);
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $secs = $seconds % 60;

                return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
            })
            ->addColumn('action', function ($row) {
                return  '<div class="text-center">
                            <a href="'.route('v1.pes.show', $row->id_project).'" class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></a>
                            <a href="'.route('v1.pes.show', $row->id_project).'#comment" class="btn btn-sm btn-primary me-2"><i class="fas fa-comment"></i></a>
                        </div>';
                // if(auth()->user()->jabatan != 'Administrator'){
                //     if($row->prepared_by !== auth()->id()){
                //     }
                //     return '<div class="text-center">
                //                 <a href="'.route('v1.pes.show', $row->id_project).'" class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></a>
                //                 <a href="'.route('v1.pes.edit', $row->id_project).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                //                 <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_project.'\')"><i class="fas fa-trash"></i></button>
                //             </div>';
                // }else{
                //     return '<div class="text-center">
                //                 <a href="'.route('v1.pes.show', $row->id_project).'" class="btn btn-sm btn-info me-2" data-toggle="tooltip" data-placement="bottom" title="Lihat Detail Project"><i class="fas fa-eye"></i></a>
                //                 <a href="'.route('v1.pes.show', $row->id_project).'#comment" class="btn btn-sm btn-primary me-2" data-toggle="tooltip" data-placement="bottom" title="Lihat Komentar"><i class="fas fa-comment"></i></a>
                //                 <a href="'.route('v1.pes.edit', $row->id_project).'" class="btn btn-sm btn-warning me-2" data-toggle="tooltip" data-placement="bottom" title="Edit Project"><i class="fas fa-edit"></i></a>
                //                 <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_project.'\')" data-toggle="tooltip" data-placement="bottom" title="Hapus Project"><i class="fas fa-trash"></i></button>
                //             </div>';
                // }
            })
            ->rawColumns([
                'project_no',
                'status',
                'batas_waktu',
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        $data = ProjectSheet::with('project_sheet_detail')->where('progress', '!=', 100)->latest()->limit(5)->get();

        return view('page.v1.pes.index', compact('data'));
    }

    public function show($id)
    {
        
        $data = ProjectSheet::query()
        ->where('id_project', $id)
        ->first();
        $serviceKategori = KategoriService::orderByRaw('CAST(sort_num AS UNSIGNED) ASC')->get();
        $serviceType = ServiceType::orderByRaw('CAST(sort_num AS UNSIGNED) ASC')->get();

        $projectLog = ProjectSheetLog::query()
            ->where('id_project_sheet', $id)
            ->latest()
            ->get();
        
        return view('page.v1.pes.show', compact('data', 'serviceKategori', 'serviceType', 'projectLog'));
    }

    public function create()
    {
        return view('page.v1.pes.create');
    }

    public function store(Request $request)
    {
        $is_draft = $request->has('is_draft') ? true : false;
        $progess = 100;

        if (!$is_draft) {
            $project_no = $this->generateProjectNo();
            $validated = $request->validate([
                'nik' => 'required|string|max:255',
                'prepared_by' => 'required|string|max:255',
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

            $projectSheet = ProjectSheet::create([
                'project_no' => $project_no,
                'project_detail' => $request->project_detail,
                'prepared_by' => $request->prepared_by,
                'issued_date' => $request->issued_date,
                'signature_by' => null,
                'signature_date' => null,
                'status' => 'draft',
                'progress' => $progess,
            ]);
            
            $pricedocName = null;
            $pricedocLink = null;
            $unpricedocName = null;
            $unpricedocLink = null;

            if ($request->price_type === 'file') {
                $priceTmp = $request->input('priceDoc_tmp');

                if ($priceTmp) {
                    // pindahkan dari storage/app/tmp/... ke public assets
                    $tmpFull = storage_path('app/' . $priceTmp); // storage/app/tmp/...
                    if (file_exists($tmpFull)) {
                        $pricedocName = time().'_priced_'.basename($tmpFull);
                        $destDir = public_path('assets/project/'.$project_no.'/pricedoc');
                        if (!file_exists($destDir)) mkdir($destDir, 0755, true);
                        rename($tmpFull, $destDir . '/' . $pricedocName);
                    }
                }
            } else {
                $pricedocLink = $request->priceLink;
            }

            if ($request->unprice_type === 'file') {
                $unpriceTmp = $request->input('unpriceDoc_tmp');

                if ($unpriceTmp) {
                    $tmpFull = storage_path('app/' . $unpriceTmp);
                    if (file_exists($tmpFull)) {
                        $unpricedocName = time().'_unpriced_'.basename($tmpFull);
                        $destDir = public_path('assets/project/'.$project_no.'/unpricedoc');
                        if (!file_exists($destDir)) mkdir($destDir, 0755, true);
                        rename($tmpFull, $destDir . '/' . $unpricedocName);
                    }
                }
            } else {
                $unpricedocLink = $request->unpriceLink;
            }

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
                'pricedoclink' => $pricedocLink,
                'unpricedoclink' => $unpricedocLink,
            ]);

            (new CreateProjectLogService())->handle($projectSheet->id_project, $progess);

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
}
