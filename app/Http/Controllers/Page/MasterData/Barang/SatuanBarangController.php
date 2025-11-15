<?php

namespace App\Http\Controllers\Page\MasterData\Barang;

use App\Http\Controllers\Controller;
use App\Models\SatuanBarang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SatuanBarangController extends Controller
{
    public function getData(Request $request)
    {
        $query = SatuanBarang::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.barang.satuan.edit', $row->id_satuan_barang).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_satuan_barang.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }
    public function index()
    {
        return view('page.v1.barang.satuan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.v1.barang.satuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        try {
            \DB::beginTransaction();

            SatuanBarang::create([
                'satuan' => $request->nama,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Satuan Barang Berhasil Ditambahkan',
                'redirect' => route('v1.barang.satuan.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong '.$th->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = SatuanBarang::query()
            ->where('id_satuan_barang', $id)
            ->first();

        return view('page.v1.barang.satuan.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        try {
            \DB::beginTransaction();

            $data = SatuanBarang::query()
            ->where('id_satuan_barang', $id)
            ->first();
            $data->update([
                'satuan' => $request->nama,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Satuan Barang Berhasil Diperbarui',
                'redirect' => route('v1.barang.satuan.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong '.$th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID is required.',
            ]);
        }

        $data = SatuanBarang::query()
            ->where('id_satuan_barang', $id)
            ->first();

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Dihapus.',
        ]);
    }
}
