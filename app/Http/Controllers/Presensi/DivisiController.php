<?php

namespace App\Http\Controllers\Presensi;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DivisiController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Data Divisi', 'subtitle' => 'Master'];
        return view('master.divisi', $data);
    }

    public function data(Request $request)
    {
        if ($request->has('select')) {
            $search = $request->term;
            $query = Divisi::query();

            if ($search) {
                $query->where('nama_divisi', 'LIKE', '%' . $search . '%');
            }

            $data = $query->orderBy('nama_divisi', 'asc')->limit(10)->get();

            $response = $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama_divisi
                ];
            });

            return response()->json($response);
        }

        $data = Divisi::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-warning btn-sm edit-btn" title="Edit"><i class="fas fa-edit"></i></a> ';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm delete-btn" title="Hapus"><i class="fas fa-trash"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_divisi' => 'required|string|max:100|unique:divisis,nama_divisi',

        ], [
            'nama_divisi.required' => 'Nama divisi wajib diisi.',
            'nama_divisi.unique' => 'Nama divisi sudah ada.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Divisi::create($request->all());

        return response()->json(['success' => 'Divisi berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $divisi = Divisi::find($id);
        if (!$divisi) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($divisi);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_divisi' => 'required|string|max:100|unique:divisis,nama_divisi,' . $id,

        ], [
            'nama_divisi.required' => 'Nama divisi wajib diisi.',
            'nama_divisi.unique' => 'Nama divisi sudah ada.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $divisi = Divisi::find($id);
        if (!$divisi) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $divisi->update($request->all());

        return response()->json(['success' => 'Divisi berhasil diperbarui.']);
    }

    public function delete($id)
    {
        $divisi = Divisi::find($id);
        if (!$divisi) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        $divisi->delete();
        return response()->json(['success' => 'Divisi berhasil dihapus.']);
    }
}
