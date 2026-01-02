<?php

namespace App\Http\Controllers\Page\MasterData\Purchase;

use App\Http\Controllers\Controller;
use App\Models\JenisSuplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class JenisSuplierController extends Controller
{
    public function getData(Request $request)
    {
        $query = JenisSuplier::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                // return '<a href="'.route('v1.barang.kategori.edit', $row->id_kategori_barang).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                //         <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_kategori_barang.'\')"><i class="fas fa-trash"></i></button>';
                return actionButtons($row->id_jenis_suplier, 'v1.jenis-suplier');
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('page.v1.jenisSuplier.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.v1.jenisSuplier.create');
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

            JenisSuplier::create([
                'nama_jenis_suplier' => $request->nama,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jenis Suplier Berhasil Ditambahkan',
                'redirect' => route('v1.jenis-suplier.index'),
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
        $data = JenisSuplier::findOrFail($id);

        return view('page.v1.jenisSuplier.edit', compact('data'));
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

            $data = JenisSuplier::findOrFail($id);
            $data->update([
                'nama_jenis_suplier' => $request->nama,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Jenis Suplier Berhasil Diubah',
                'redirect' => route('v1.jenis-suplier.index'),
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

        $data = JenisSuplier::query()
            ->where('id_jenis_suplier', $id)
            ->first();

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Dihapus.',
        ]);
    }
}
