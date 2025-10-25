<?php

namespace App\Http\Controllers\Page\HRGA_IT\DataKaryawan;

use App\Http\Controllers\Controller;
use App\Models\DataKaryawan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DataKaryawanController extends Controller
{
    public function getData(Request $request)
    {
        $query = DataKaryawan::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.service.type.edit', $row->id_service_type).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_service_type.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.service.type.index');
    }

    public function create()
    {
        return view('page.v1.service.type.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $lastNik = DataKaryawan::max('nik');
        $nik = is_null($lastNik) ? 1 : ($lastNik + 1);

        try {
            \DB::beginTransaction();

            DataKaryawan::create([
                'nik' => $nik,
                'name' => $request->name,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service Type created successfully',
                'redirect' => route('v1.service.type.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong '.$th->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $data = DataKaryawan::find($id);

        return view('page.v1.service.type.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            \DB::beginTransaction();

            $data = DataKaryawan::find($id);
            $data->update(['name' => $request->name]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service Type updated successfully',
                'redirect' => route('v1.service.type.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong '.$th->getMessage(),
            ], 500);
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

        $data = DataKaryawan::find($id);

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
