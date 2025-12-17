<?php

namespace App\Http\Controllers\Presensi;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PenggunaController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Data Pengguna', 'subtitle' => 'Master'];
        return view('page.master.pengguna', $data);
    }

    public function data()
    {
        $data = User::with(['jabatan', 'departemen']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nama', function ($row) {
                $imageUrl = $row->foto_profil_url ? Storage::url($row->foto_profil_url) : asset('noimage.png');
                return '<div class="d-flex align-items-center">
                           <img src="' . $imageUrl . '" alt="Avatar" class="table-avatar rounded-circle me-3">
                           <div>
                               <div class="fw-bold">' . $row->nama_lengkap . '</div>
                               <small class="text-muted">' . $row->nomor_telepon . '</small>
                           </div>
                       </div>';
            })
            ->addColumn('jabatan', fn($row) => $row->jabatan->nama_jabatan ?? '-')
            ->addColumn('departemen', fn($row) => $row->departemen->nama_departemen ?? '-')
            ->addColumn('role', function ($row) {
                $badge = $row->role == 'admin' ? 'badge-primary' : 'badge-secondary';
                return '<span class="badge ' . $badge . '">' . ucfirst($row->role) . '</span>';
            })
            ->addColumn('status', function ($row) {
                $badge = $row->status == 'aktif' ? 'badge-success' : 'badge-danger';
                return '<span class="badge ' . $badge . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-warning btn-sm edit-btn" title="Edit"><i class="fas fa-edit"></i></a> ';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete-btn" title="Hapus"><i class="fas fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['nama', 'role', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nomor_telepon' => 'nullable|string',
            'id_jabatan' => 'nullable|exists:jabatans,id',
            'id_departemen' => 'nullable|exists:departemens,id',
            'role' => 'required|in:karyawan,admin',
            'status' => 'required|in:aktif,tidak_aktif',
            'foto_profil_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('password', 'foto_profil_url');
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('foto_profil_url')) {
            $path = $request->file('foto_profil_url')->store('foto-profil', 'public');
            $data['foto_profil_url'] = $path;
        }

        User::create($data);

        return response()->json(['success' => 'Pengguna berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $user = User::with(['jabatan', 'departemen'])->find($id);
        if (!$user) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'nomor_telepon' => 'nullable|string',
            'id_jabatan' => 'nullable|exists:jabatans,id',
            'id_departemen' => 'nullable|exists:departemens,id',
            'role' => 'required|in:karyawan,admin',
            'status' => 'required|in:aktif,tidak_aktif',
            'foto_profil_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $data = $request->except('password', 'foto_profil_url');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil_url')) {
            if ($user->foto_profil_url) {
                Storage::disk('public')->delete($user->foto_profil_url);
            }
            $path = $request->file('foto_profil_url')->store('foto-profil', 'public');
            $data['foto_profil_url'] = $path;
        }

        $user->update($data);

        return response()->json(['success' => 'Pengguna berhasil diperbarui.']);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        if ($user->foto_profil_url) {
            Storage::disk('public')->delete($user->foto_profil_url);
        }

        $user->delete();
        return response()->json(['success' => 'Pengguna berhasil dihapus.']);
    }
}
