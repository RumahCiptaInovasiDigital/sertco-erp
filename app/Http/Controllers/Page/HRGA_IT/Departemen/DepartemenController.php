<?php

namespace App\Http\Controllers\Page\HRGA_IT\Departemen;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DepartemenController extends Controller
{
    public function getData(Request $request)
    {
        $query = Departemen::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.departemen.edit', $row->id_departemen).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_departemen.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.departemen.index');
    }

    public function create()
    {
        return view('page.v1.departemen.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            \DB::beginTransaction();

            Departemen::create([
                'name' => $request->name,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Departemen created successfully',
                'redirect' => route('v1.departemen.index'),
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
        $data = Departemen::query()
            ->where('id_departemen', $id)
            ->first();

        return view('page.v1.departemen.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            \DB::beginTransaction();

            $data = Departemen::query()
            ->where('id_departemen', $id)
            ->first();
            $data->update(['name' => $request->name]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Departemen updated successfully',
                'redirect' => route('v1.departemen.index'),
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

        $data = Departemen::query()
            ->where('id_departemen', $id)
            ->first();

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
