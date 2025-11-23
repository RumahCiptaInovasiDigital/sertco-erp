<?php

namespace App\Http\Controllers\Page\HSE\DataPeralatan;

use App\Http\Controllers\Controller;
use App\Models\DataPeralatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DataPeralatanController extends Controller
{
    public function getData(Request $request)
    {
        $query = DataPeralatan::query()
            ->select([
                'id',
                'name',
                'last_calibration',
                'due_calibration',
                'kondisi_alat',
            ])->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.data-peralatan.show', $row->id).'" class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></a>
                        <a href="'.route('v1.data-peralatan.edit', $row->id).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.hse.dataPeralatan.index');
    }

    public function create()
    {
        return view('page.v1.hse.dataPeralatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // 'kondisi_alat' => 'required|string|max:255',
            'status_alat' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            DataPeralatan::create([
                'name' => $request->name,
                'merk' => $request->merk,
                'tipe' => $request->tipe,
                'serial_number' => $request->serial_number,
                'last_calibration' => $request->last_calibration,
                'due_calibration' => $request->due_calibration,
                'lokasi' => $request->lokasi,
                'kondisi_alat' => $request->kondisi_alat,
                'status_alat' => $request->status_alat,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service Type created successfully',
                'redirect' => route('v1.data-peralatan.index'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong '.$th->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $data = DataPeralatan::query()
            ->where('id', $id)
            ->first();

        return view('page.v1.hse.dataPeralatan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kondisi_alat' => 'required|string|max:255',
            'status_alat' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $data = DataPeralatan::query()->where('id', $id)->firstOrFail();

            $data->update([
                'name' => $request->name,
                'merk' => $request->merk,
                'tipe' => $request->tipe,
                'serial_number' => $request->serial_number,
                'last_calibration' => $request->last_calibration,
                'due_calibration' => $request->due_calibration,
                'lokasi' => $request->lokasi,
                'kondisi_alat' => $request->kondisi_alat,
                'status_alat' => $request->status_alat,
            ]);

            DataPeralatan::updateOrCreate(
                ['id' => $data->id], // kondisi pencarian
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data updated successfully',
                'redirect' => route('v1.data-peralatan.index'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: '.$th->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $data = DataPeralatan::query()
        ->where('id', $id)
        ->first();

        return view('page.v1.hse.dataPeralatan.show', compact('data'));
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

        $data = DataPeralatan::query()
            ->where('id', $id)
            ->first();

        // Hapus 
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
