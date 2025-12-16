<?php

namespace App\Http\Controllers\Presensi;

use App\Models\DataKaryawan;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KaryawanController extends Controller
{
    public function index()
    {
        return view('page.master.karyawan.data_karyawan');
    }

    public function data()
    {
        $karyawan = DataKaryawan::query();

        return DataTables::of($karyawan)
            ->addIndexColumn()
            ->addColumn('foto', function ($row) {
                $url = $row->foto ? Storage::url($row->foto) : null;
                $initials = $row->firstName && $row->lastName
                    ? mb_substr($row->firstName, 0, 1) . mb_substr($row->lastName, 0, 1)
                    : mb_substr($row->firstName ?? $row->lastName ?? 'NA', 0, 2);

                if ($url) {
                    return '<img src="' . $url . '" alt="Foto" width="50" height="50" style="object-fit: cover; border-radius: 50%;">';
                }

                // Gunakan UI Avatars API
                $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&size=50&background=random&color=fff&rounded=true';
                return '<img src="' . $avatarUrl . '" alt="Avatar" width="50" height="50" style="border-radius: 50%;">';
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('master.karyawan.edit', $row->id);
                $deleteForm = '
                <form action="' . route('master.karyawan.destroy', $row->id) . '" method="POST" style="display:inline-block;" class="delete-form">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>';

                return '<a href="' . $editUrl . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ' . $deleteForm;
            })
            ->rawColumns(['action', 'foto'])
            ->make(true);
    }


    public function create()
    {
        $roles = Role::orderBy('name', 'asc')->get();
        $departemens = Departemen::orderBy('name', 'asc')->get();
        return view('page.master.karyawan.add_edit_karyawan', compact('roles', 'departemens'));
    }

    public function edit($id)
    {
        $karyawan = DataKaryawan::findOrFail($id);
        $roles = Role::orderBy('name', 'asc')->get();
        $departemens = Departemen::orderBy('name', 'asc')->get();
        return view('page.master.karyawan.add_edit_karyawan', compact('karyawan', 'roles', 'departemens'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'nullable|email|unique:data_karyawans,email',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'nik' => 'required|string|unique:data_karyawans,nik',
            'inisial' => 'required|string|max:255',
            'idJabatan' => 'required|exists:roles,id_role',
            'idDepartemen' => 'nullable|exists:departemens,id_departemen',
            'joinDate' => 'required|date',
            'empDateStart' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->except(['foto', 'ijazah']);

            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('foto_karyawan', 'public');
            }
            if ($request->hasFile('ijazah')) {
                $data['ijazah'] = $request->file('ijazah')->store('ijazah_karyawan', 'public');
            }

            $role = Role::find($request->idJabatan);
            $data['namaJabatan'] = $role->name;

            if ($request->filled('idDepartemen')) {
                $departemen = Departemen::find($request->idDepartemen);
                $data['namaDepartemen'] = $departemen->name;
            }

            DataKaryawan::create($data);

            return response()->json(['success' => 'Karyawan berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $karyawan = DataKaryawan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'nullable|email|unique:data_karyawans,email,' . $id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ijazah' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'nik' => 'required|string|unique:data_karyawans,nik,' . $id,
            'inisial' => 'required|string|max:255',
            'idJabatan' => 'required|exists:roles,id_role',
            'idDepartemen' => 'nullable|exists:departemens,id_departemen',
            'joinDate' => 'required|date',
            'empDateStart' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->except(['foto', 'ijazah']);

            if ($request->hasFile('foto')) {
                if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                    Storage::disk('public')->delete($karyawan->foto);
                }
                $data['foto'] = $request->file('foto')->store('foto_karyawan', 'public');
            }
            if ($request->hasFile('ijazah')) {
                if ($karyawan->ijazah && Storage::disk('public')->exists($karyawan->ijazah)) {
                    Storage::disk('public')->delete($karyawan->ijazah);
                }
                $data['ijazah'] = $request->file('ijazah')->store('ijazah_karyawan', 'public');
            }

            $role = Role::find($request->idJabatan);
            $data['namaJabatan'] = $role->name;

            if ($request->filled('idDepartemen')) {
                $departemen = Departemen::find($request->idDepartemen);
                $data['namaDepartemen'] = $departemen->name;
            } else {
                $data['namaDepartemen'] = null;
            }

            $karyawan->update($data);

            return response()->json(['success' => 'Data karyawan berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $karyawan = DataKaryawan::findOrFail($id);
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            if ($karyawan->ijazah && Storage::disk('public')->exists($karyawan->ijazah)) {
                Storage::disk('public')->delete($karyawan->ijazah);
            }
            $karyawan->delete();
            return response()->json(['success' => 'Karyawan berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Methods for Select2 AJAX
    public function getJabatan(Request $request)
    {
        $search = $request->term;
        $data = Role::where('name', 'LIKE', '%' . $search . '%')->orderBy('name', 'asc')->get();
        $response = $data->map(function ($item) {
            return ['id' => $item->id, 'text' => $item->name];
        });
        return response()->json(['results' => $response]);
    }

    public function getDepartemen(Request $request)
    {
        $search = $request->term;
        $data = Departemen::where('name', 'LIKE', '%' . $search . '%')->orderBy('name', 'asc')->get();
        $response = $data->map(function ($item) {
            return ['id' => $item->id_departemen, 'text' => $item->name];
        });
        return response()->json(['results' => $response]);
    }
}
