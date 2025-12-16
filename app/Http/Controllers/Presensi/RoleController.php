<?php

namespace App\Http\Controllers\Presensi;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Data Role', 'subtitle' => 'Master'];
        return view('page.master.role', $data);
    }

    public function data(Request $request)
    {
        if ($request->has('select')) {
            $search = $request->term;
            $query = Role::query();

            if ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            $data = $query->orderBy('name', 'asc')->limit(10)->get();

            $response = $data->map(function ($item) {
                return [
                    'id' => $item->id_role,
                    'text' => $item->name,
                ];
            });

            return response()->json($response);
        }

        $data = Role::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-id="' . $row->id_role . '" class="btn btn-warning btn-sm edit-btn" title="Edit"><i class="fas fa-edit"></i></a> ';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id_role . '" class="btn btn-danger btn-sm delete-btn" title="Hapus"><i class="fas fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:roles,name',
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique' => 'Nama role sudah ada.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Role::create(['name' => $request->name]);

        return response()->json(['success' => 'Role berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:roles,name,' . $id . ',id_role',
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique' => 'Nama role sudah ada.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $role->update(
            [
                'name' => $request->name,
            ]
        );

        return response()->json(['success' => 'Role berhasil diperbarui.']);
    }

    public function delete($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        $role->delete();
        return response()->json(['success' => 'Role berhasil dihapus.']);
    }
}
