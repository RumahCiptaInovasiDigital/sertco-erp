<?php

namespace App\Http\Controllers\Presensi;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DepartemenController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Data Departemen', 'subtitle' => 'Master'];
        return view('page.master.departemen', $data);
    }

    public function data(Request $request)
    {
        if ($request->has('select')) {
            $search = $request->term;
            $query = Departemen::query();

            if ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            $data = $query->orderBy('name', 'asc')->limit(10)->get();

            $response = $data->map(function ($item) {
                return [
                    'id' => $item->id_departemen,
                    'text' => $item->name
                ];
            });

            return response()->json($response);
        }

        $data = Departemen::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-id="' . $row->id_departemen . '" class="btn btn-warning btn-sm edit-btn" title="Edit"><i class="fas fa-edit"></i></a> ';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id_departemen . '" class="btn btn-danger btn-sm delete-btn" title="Hapus"><i class="fas fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:departemens,name',

        ], [
            'name.required' => 'Nama departemen wajib diisi.',
            'name.unique' => 'Nama departemen sudah ada.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Departemen::create($request->all());

        return response()->json(['success' => 'Departemen berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $departemen = Departemen::find($id);
        if (!$departemen) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($departemen);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // INI BAGIAN YANG DIPERBAIKI
            'name' => 'required|string|max:100|unique:departemens,name,' . $id . ',id_departemen',

        ], [
            'name.required' => 'Nama departemen wajib diisi.',
            'name.unique' => 'Nama departemen sudah ada.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $departemen = Departemen::find($id);
        if (!$departemen) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $departemen->update($request->all());

        return response()->json(['success' => 'Departemen berhasil diperbarui.']);
    }

    public function delete($id)
    {
        $departemen = Departemen::find($id);
        if (!$departemen) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        $departemen->delete();
        return response()->json(['success' => 'Departemen berhasil dihapus.']);
    }
}
