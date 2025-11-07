<?php

namespace App\Http\Controllers\Page\Marketing\Service\Type;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceTypeController extends Controller
{
    public function getData(Request $request)
    {
        $query = ServiceType::query()->latest()->get();

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

        $lastSort = ServiceType::max('sort_num');
        $sortNum = is_null($lastSort) ? 1 : ($lastSort + 1);

        try {
            \DB::beginTransaction();

            ServiceType::create([
                'name' => $request->name,
                'sort_num' => $sortNum,
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
        $data = ServiceType::query()
            ->where('id_service_type', $id)
            ->first();

        return view('page.v1.service.type.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            \DB::beginTransaction();

            $data = ServiceType::query()
            ->where('id_service_type', $id)
            ->first();
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

        $data = ServiceType::query()
            ->where('id_service_type', $id)
            ->first();

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
