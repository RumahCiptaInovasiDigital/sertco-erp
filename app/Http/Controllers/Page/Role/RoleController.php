<?php

namespace App\Http\Controllers\Page\Role;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Role;
use App\Models\RoleHasDepartemen;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function getData(Request $request)
    {
        $query = Role::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('departemen', function ($row) {
                return $row->hasDepartemen->departemen->name ?? 'Non-Departemen';
            })
            ->addColumn('user', function ($row) {
                return '<div class="text-center">
                <h4><span class="badge badge-pill badge-secondary">'.count($row->totalUser).' User</span></h4>
            </div>';
            })
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.role.edit', $row->id_role).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_role.'\')"><i class="fas fa-trash"></i></button>
                        |
                        <a href="'.route('v1.role.assign.index', [strtolower($row->name), $row->id_role]).'" class="btn btn-info btn-sm ms-5"><i class="fas fa-user-plus"></i> Tambahkan User</a>';
            })
            ->rawColumns([
                'departemen',
                'user',
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.role.index');
    }

    public function create()
    {
        $departemen = Departemen::orderBy('name')->get();
        $routes = \Route::getRoutes()->getRoutesByName();

        return view('page.v1.role.create', compact('departemen', 'routes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'departemen' => 'required',
        ]);

        $departemen = $request->departemen;

        try {
            \DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
            ]);

            if ($departemen !== 'na') {
                RoleHasDepartemen::create([
                    'id_role' => $role->id_role,
                    'id_departemen' => $departemen,
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'redirect' => route('v1.role.index'),
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
        $data = Role::query()
            ->where('id_role', $id)
            ->first();

        $departemen = Departemen::orderBy('name')->get();

        return view('page.v1.role.edit', compact('data', 'departemen'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'departemen' => 'required',
        ]);

        $departemen = $request->departemen;

        try {
            \DB::beginTransaction();

            $data = Role::query()->where('id_role', $id)->firstOrFail();

            $data->update([
                'name' => $request->name,
            ]);

            RoleHasDepartemen::updateOrCreate(
                ['id_role' => $data->id_role], // kondisi pencarian
                ['id_departemen' => $departemen] // data yang diupdate / dibuat
            );

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
                'redirect' => route('v1.role.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: '.$th->getMessage(),
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

        $data = Role::query()
            ->where('id_role', $id)
            ->first();

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
