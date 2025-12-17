<?php

namespace App\Http\Controllers\Presensi;

use App\Models\JenisKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class JenisKerjaController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Data Jenis Kerja', 'subtitle' => 'Master'];
        return view('page.master.jeniskerja', $data);
    }

    public function data(Request $request)
    {
        if ($request->has('select')) {
            $search = $request->term;
            $query = JenisKerja::query();

            if ($search) {
                $query->where('nama_jenis_kerja', 'LIKE', '%' . $search . '%');
            }

            $data = $query->orderBy('nama_jenis_kerja', 'asc')->limit(10)->get();

            $response = $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->nama_jenis_kerja
                ];
            });

            return response()->json($response);
        }

        $data = JenisKerja::query();

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
            'nama_jenis_kerja' => 'required|string|max:50|unique:jenis_kerja',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:active,nonactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        JenisKerja::create($request->all());

        return response()->json(['success' => 'Jenis Kerja berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $jenisKerja = JenisKerja::find($id);
        if (!$jenisKerja) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($jenisKerja);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_jenis_kerja' => 'required|string|max:50|unique:jenis_kerja,nama_jenis_kerja,' . $id,
            'keterangan' => 'nullable|string',
            'status' => 'required|in:Aktif,Non-Aktif',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jenisKerja = JenisKerja::find($id);
        if (!$jenisKerja) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $jenisKerja->update($request->all());

        return response()->json(['success' => 'Jenis Kerja berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $jenisKerja = JenisKerja::find($id);
        if (!$jenisKerja) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
        $jenisKerja->delete();
        return response()->json(['success' => 'Jenis Kerja berhasil dihapus.']);
    }
}
