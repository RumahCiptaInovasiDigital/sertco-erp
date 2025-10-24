<?php

namespace App\Http\Controllers\Page\Service\Kategori;

use App\Http\Controllers\Controller;
use App\Models\KategoriService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceKategoriController extends Controller
{
    public function getData(Request $request)
    {
        $query = KategoriService::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.service.kategori.edit', $row->id_kategori_service).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_kategori_service.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.service.kategori.index');
    }

    public function create()
    {
        return view('page.v1.service.kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $lastSort = KategoriService::max('sort_num');
        $sortNum = is_null($lastSort) ? 1 : ($lastSort + 1);

        try {
            \DB::beginTransaction();

            KategoriService::create([
                'name' => $request->name,
                'sort_num' => $sortNum,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service Kategori created successfully',
                'redirect' => route('v1.service.kategori.index'),
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
        $data = KategoriService::query()
            ->where('id_kategori_service', $id)
            ->first();

        return view('page.v1.service.kategori.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            \DB::beginTransaction();

            $data = KategoriService::query()
            ->where('id_kategori_service', $id)
            ->first();
            $data->update(['name' => $request->name]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service Kategori updated successfully',
                'redirect' => route('v1.service.kategori.index'),
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

        $data = KategoriService::query()
            ->where('id_kategori_service', $id)
            ->first();

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
