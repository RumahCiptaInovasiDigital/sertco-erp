<?php

namespace App\Http\Controllers\Page\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\ServiceOrder;
use App\Models\ProjectSheet;
use App\Models\LogSO;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ServiceOrderController extends Controller
{
    public function getData(Request $request)
    {
        $query = ServiceOrder::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn("nama_vendor", function ($row) {
                if($row->id_vendor != null) {
                    return $row->hasVendor->nama_vendor;
                } else {
                    return "-";
                }
            })
            ->editColumn("tanggal_so", function ($row) {
                return date('d F Y H:i:s',strtotime($row->tanggal_so));
            })
            ->editColumn("tanggal_dibutuhkan", function ($row) {
                return date('d F Y',strtotime($row->tanggal_dibutuhkan));
            })
            ->editColumn("total_estimasi", function ($row) {
                return "Rp. ".number_format($row->total_estimasi,2,",",".") ."";
            })
            ->editColumn("status", function ($row) {
                if ($row->status_so == "draft") {
                    return '<span class="badge badge-secondary">Draft</span>';
                } elseif ($row->status_so == "pending") {
                    return '<span class="badge badge-info">Pending</span>';
                } elseif ($row->status_so == "on review") {
                    return '<span class="badge badge-warning">On Review</span>';
                } elseif ($row->status_so == "approved") {
                    return '<span class="badge badge-primary">Approved</span>';
                } elseif ($row->status_so == "rejected") {
                    return '<span class="badge badge-danger">Rejected</span>';
                } elseif ($row->status_so == "finished") {
                    return '<span class="badge badge-success">Finished</span>';
                } else {
                    return '<span class="badge badge-dark">Unknown</span>';
                }
            })
            ->addColumn('action', function ($row) {
                if ($row->status_so == "draft") {
                    return '<a href="'.route('v1.poso-request.so.edit', $row->id_so).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_so.'\')"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-sm btn-primary" onclick="sendData(\''.$row->id_so.'\')"><i class="fas fa-paper-plane"></i></button>';
                } elseif ($row->status_so == "pending") {
                    return '#';
                } elseif ($row->status_so == "on review") {
                    return '#';
                } elseif ($row->status_so == "approved") {
                    return '#';
                } elseif ($row->status_so == "rejected") {
                    return '#';
                } elseif ($row->status_so == "finished") {
                    return '#';
                } else {
                    return '#';
                }
            })
            ->rawColumns([
                'status',
                'action',
            ])
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('page.v1.poso.so.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['vendor'] = Vendor::query()->latest()->get();
        $data['project'] = ProjectSheet::query()->latest()->get();
        return view('page.v1.poso.so.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor' => 'required|exists:vendors,id_vendor',
            'tanggal_dibutuhkan' => 'required',
            'jenis_pekerjaan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'estimasi_jasa' => 'required',
            'file_lampiran' => 'required|file|mimes:pdf|max:5120',
        ]);

        $ganti = array('Rp. ', '.', ',');
        $estimasi_jasa = str_replace($ganti, '', $request->estimasi_jasa);
        $estimasi_material = str_replace($ganti, '', $request->estimasi_material ?? '0');
        $total_estimasi = (int)$estimasi_jasa + (int)$estimasi_material;

        // Generate automatic SO number in format: SQ/SO/YYYY/MM/NNNN
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Find last SO for the current year (include trashed records)
        $lastNoSo = ServiceOrder::where('no_so', 'like', "SQ/SO/{$currentYear}/%")
            ->orderBy('created_at', 'desc')
            ->withTrashed()
            ->first();

        if ($lastNoSo && preg_match('/(\d{4})$/', $lastNoSo->no_so, $matches)) {
            $lastNumber = (int) $matches[1];
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $noSo = sprintf('SQ/SO/%s/%s/%s', $currentYear, $currentMonth, $newNumber);

        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $filename = 'SO-Lampiran-' . date('ymdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/so-lampiran'), $filename);
            $file_lampiran = $filename;
        } else {
            $file_lampiran = "-";
        }

        $data = [
            'no_so' => $noSo,
            'id_vendor' => $request->vendor,
            'tanggal_so' => now(),
            'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
            'nik' => auth()->user()->nik,
            'jenis_pekerjaan' => $request->jenis_pekerjaan,
            'deskripsi_so' => $request->deskripsi,
            'estimasi_biaya_jasa' => $estimasi_jasa,
            'estimasi_biaya_material' => $estimasi_material,
            'total_estimasi' => $total_estimasi,
            'file_lampiran' => $file_lampiran,
            'id_project' => $request->project ?? null,
            'status_so' => 'draft',
        ];

        try {
            \DB::beginTransaction();

            $so = ServiceOrder::create($data);

            // Simpan log SO
            LogSO::create([
                'id_so' => $so->id_so,
                'status_so' => 'draft',
                'ket_log_so' => 'Draft Service Order dibuat',
                'eksekutor' => auth()->user()->nik,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'SO Request Tersimpan di Draft',
                'redirect' => route('v1.poso-request.so.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong '.$th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
