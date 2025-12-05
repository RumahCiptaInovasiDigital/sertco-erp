<?php

namespace App\Http\Controllers\Page\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Suplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Barang;
use App\Models\ProjectSheet;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PurchaseOrderController extends Controller
{
    public function getData(Request $request)
    {
        $query = PurchaseOrder::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn("nama_suplier", function ($row) {
                if($row->id_suplier != null) {
                    return $row->hasSuplier->nama_suplier;
                } else {
                    return "-";
                }
            })
            ->editColumn("tanggal_po", function ($row) {
                return date('d F Y H:i:s',strtotime($row->tanggal_po));
            })
            ->editColumn("tanggal_dibutuhkan", function ($row) {
                return date('d F Y',strtotime($row->tanggal_dibutuhkan));
            })
            ->editColumn("status", function ($row) {
                if ($row->status_po == "draft") {
                    return '<span class="badge badge-secondary">Draft</span>';
                } elseif ($row->status_po == "pending") {
                    return '<span class="badge badge-info">Pending</span>';
                } elseif ($row->status_po == "on review") {
                    return '<span class="badge badge-warning">On Review</span>';
                } elseif ($row->status_po == "approved") {
                    return '<span class="badge badge-primary">Approved</span>';
                } elseif ($row->status_po == "rejected") {
                    return '<span class="badge badge-danger">Rejected</span>';
                } elseif ($row->status_po == "finished") {
                    return '<span class="badge badge-success">Finished</span>';
                } else {
                    return '<span class="badge badge-dark">Unknown</span>';
                }
            })
            ->addColumn('action', function ($row) {
                if ($row->status_po == "draft") {
                    return '<a href="'.route('v1.poso-request.po.edit', $row->id_po).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_po.'\')"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-sm btn-primary" onclick="sendData(\''.$row->id_po.'\')"><i class="fas fa-paper-plane"></i></button>';
                } elseif ($row->status_po == "pending") {
                    if(auth()->user()->nik == $row->nik) {
                         return '<button class="btn btn-sm btn-primary" onclick="cancelData(\''.$row->id_po.'\')"><i class="fas fa-undo"></i></button>';
                    } else {
                        return '#';
                    }
                } elseif ($row->status_po == "on review") {
                    return '#';
                } elseif ($row->status_po == "approved") {
                    return '#';
                } elseif ($row->status_po == "rejected") {
                    return '#';
                } elseif ($row->status_po == "finished") {
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
        return view('page.v1.poso.po.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['suplier'] = Suplier::query()->latest()->get();
        $data['barang'] = Barang::query()->latest()->get();
        $data['project'] = ProjectSheet::query()->latest()->get();
        return view('page.v1.poso.po.create', $data);
    }

    public function itemSearch($id)
    {
        $barang = Barang::find($id);
        if (!$barang) {
            return response()->json(['error' => 'Data alat tidak ditemukan'], 404);
        }
        return response()->json($barang);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'suplier' => 'required|exists:supliers,id_suplier',
            'tanggal_dibutuhkan' => 'required',
            'deskripsi' => 'required|string',
            'idBarang' => 'required|array|min:1',
            'req' => 'required|array|min:1',
        ]);

        // Generate automatic PO number in format: SQ/SO/YY/MM/NNNN
        $currentYear = date('y');
        $currentMonth = date('m');

        // Find last PO for the current year (include trashed records)
        $lastNoPo = PurchaseOrder::where('no_po', 'like', "SQ/PO/{$currentYear}/%")
            ->orderBy('created_at', 'desc')
            ->withTrashed()
            ->first();

        if ($lastNoPo && preg_match('/(\d{4})$/', $lastNoPo->no_po, $matches)) {
            $lastNumber = (int) $matches[1];
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $noPo = sprintf('SQ/PO/%s/%s/%s', $currentYear, $currentMonth, $newNumber);

        $data = [
            'no_po' => $noPo,
            'id_suplier' => $request->suplier,
            'tanggal_po' => now(),
            'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
            'nik' => auth()->user()->nik,
            'deskripsi_po' => $request->deskripsi,
            'id_project' => $request->project ?? null,
            'status_po' => 'draft',
        ];

        try {
            \DB::beginTransaction();

            // Hitung total alat
            $totalBarang = count($request->idBarang);

            $po = PurchaseOrder::create($data);

            //simpan detail PO
            foreach ($request->idBarang as $i => $barangId) {
                PurchaseOrderDetail::create([
                    'id_po' => $po->id_po,
                    'id_barang' => $barangId,
                    'jumlah_barang' => $request->req[$i],
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PO Request Tersimpan di Draft',
                'redirect' => route('v1.poso-request.po.index'),
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

    public function send(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID is required.',
            ]);
        }

        $data = [
            'status_po' => "pending",
        ];

        try {
            \DB::beginTransaction();

            PurchaseOrder::where('id_po', $id)->update($data);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PO Request berhasil dikirim.',
                'redirect' => route('v1.poso-request.po.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong '.$th->getMessage(),
            ], 500);
        }
    }

    public function cancel(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID is required.',
            ]);
        }

        $data = [
            'status_po' => "draft",
        ];

        try {
            \DB::beginTransaction();

            PurchaseOrder::where('id_po', $id)->update($data);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'PO Request berhasil dibatalkan.',
                'redirect' => route('v1.poso-request.po.index'),
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
