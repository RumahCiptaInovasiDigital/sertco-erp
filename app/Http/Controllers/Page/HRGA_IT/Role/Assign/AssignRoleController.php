<?php

namespace App\Http\Controllers\Page\HRGA_IT\Role\Assign;

use App\Http\Controllers\Controller;
use App\Models\DataKaryawan;
use App\Models\Role;
use App\Models\UserHasRole;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AssignRoleController extends Controller
{
    public function getData(Request $request, $role, $id)
    {
        $query = UserHasRole::query()->where('id_role', $id)->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('fullname', function ($row) {
                return $row->user->fullName;
            })
            ->addColumn('action', function ($row) {
                $btn = '<ul class="list-inline me-auto mb-0">
                                <li class="list-inline-item align-bottom"
                                    title="Delete">
                                    <button type="button" data-url="'.route('v1.role.assign.destroy', $row->id).'" class="btn btn-sm btn-danger deletePost" style="border-radius: 7px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </li>
                            </ul>';

                return $btn;
            })
            ->rawColumns([
                'departemen',
                'action',
            ])
            ->make(true);
    }

    public function index($role, $id)
    {
        $role = Role::where('id_role', $id)->first();

        return view('page.v1.hrga_it.jabatan.assign.index', compact('role'));
    }

    public function getEmployee(Request $request)
    {
        $employee = DataKaryawan::where('fullName', 'like', "%{$request->q}%")->get();

        $data = [];
        foreach ($employee as $item) {
            // code...
            $data[] = [
                'nik' => $item['nik'],
                'fullname' => $item['fullName'],
            ];
        }

        return response()->json($data, 200);
    }

    public function store(Request $request, $role, $id)
    {
        $role = Role::where('id_role', $id);
        $data = UserHasRole::where('nik', $request->nik)->where('id_role', $id)->first();
        $user = UserHasRole::where('nik', $request->nik)->first();
        $dataKaryawan = DataKaryawan::where('nik', $request->nik)->first();

        if ($user) {
            $is_active = Role::where('id_role', $user->id_role)->first();
            if (!$is_active) {
                $user->delete();
            }
            $user = UserHasRole::where('nik', $request->nik)->first();
        }

        if (!empty($data) || !empty($user)) {
            // code...
            return response()->json([
                'success' => false,
                'message' => 'Account Sudah Terdaftar',
            ]);
        } else {
            UserHasRole::create([
                'nik' => $request->nik,
                'id_role' => $id,
            ]);
            $dataKaryawan->update([
                'idJabatan' => $id,
                'namaJabatan' => $role->first()->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Account Berhasil didaftarkan',
            ]);
        }
    }

    public function destroy($id)
    {
        $data = UserHasRole::find($id);

        $dataKaryawan = DataKaryawan::where('nik', $data->nik)->first();
        if (empty($data)) {
            // code...
            return response()->json([
                'success' => false,
                'message' => 'Account Tidak Bisa dihapus',
            ]);
        }
        $dataKaryawan->update([
            'idJabatan' => '-',
            'namaJabatan' => '-',
        ]);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account Berhasil dihapus',
        ]);
    }
}
