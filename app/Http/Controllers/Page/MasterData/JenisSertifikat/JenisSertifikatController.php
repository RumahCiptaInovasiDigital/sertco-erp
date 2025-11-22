<?php

namespace App\Http\Controllers\Page\MasterData\JenisSertifikat;

use App\Http\Controllers\Controller;
use App\Models\JenisSertifikat;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class JenisSertifikatController extends Controller
{
    public function getData(Request $request)
    {
        $query = JenisSertifikat::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('pic', function ($row) {
                return $row->jabatan->name;
            })
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.jenis-sertifikat.edit', $row->id_sertifikat).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_sertifikat.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.hse.jenisSertifikat.index');
    }

    public function create()
    {
        $role = Role::orderBy('name')->get();
        return view('page.v1.hse.jenisSertifikat.create', compact('role'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
        ]);

        $lastSort = JenisSertifikat::max('sort_num');
        $sortNum = is_null($lastSort) ? 1 : ($lastSort + 1);

        try {
            \DB::beginTransaction();

            JenisSertifikat::create([
                'name' => $request->name,
                'pic' => $request->pic,
                'sort_num' => $sortNum,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service Kategori created successfully',
                'redirect' => route('v1.jenis-sertifikat.index'),
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
        $data = JenisSertifikat::query()
            ->where('id_sertifikat', $id)
            ->first();

        $role = Role::orderBy('name')->get();
        return view('page.v1.hse.jenisSertifikat.edit', compact('data', 'role'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
        ]);

        try {
            \DB::beginTransaction();

            $data = JenisSertifikat::query()
            ->where('id_sertifikat', $id)
            ->first();

            $data->update([
                'name'      => $request->name,
                'pic'  => $request->pic,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jenis Sertifikat Berhasil Di updated',
                'redirect' => route('v1.jenis-sertifikat.index'),
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

        $data = JenisSertifikat::query()
            ->where('id_sertifikat', $id)
            ->first();

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
