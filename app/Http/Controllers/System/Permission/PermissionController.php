<?php

namespace App\Http\Controllers\System\Permission;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function getData(Request $request)
    {
        $query = Role::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="'.route('admin.permission.edit', $row->id_role).'" class="btn btn-warning btn-sm ms-5"><i class="fas fa-key"></i> Setting Permissions</a>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.admin.permission.index');
    }

    public function edit($id)
    {
        $data = Role::query()
            ->where('id_role', $id)
            ->first();

        $departemen = Departemen::orderBy('name')->get();
        $routes = \Route::getRoutes()->getRoutesByName();

        return view('page.admin.permission.edit', compact('data', 'departemen', 'routes'));
    }

    public function update(Request $request, $id)
    {
        try {
            \DB::beginTransaction();

            $data = Role::query()->where('id_role', $id)->firstOrFail();

            // Hapus permissions lama jika ada
            $data->permission()->delete();

            // Perbarui izin berdasarkan URL yang dipilih
            foreach ($request->input('urls', []) as $url) {
                $data->permission()->create(['url' => $url]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permmissions updated successfully',
                'redirect' => route('admin.permission.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: '.$th->getMessage(),
            ], 500);
        }
    }
}
