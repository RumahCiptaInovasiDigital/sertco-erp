<?php

namespace App\Http\Controllers\Page\MasterData\Barang;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriBarangController extends Controller
{
    public function getData(Request $request)
    {
        $query = KategoriBarang::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.barang.kategori.edit', $row->id_kategori_barang).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_kategori_barang.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }
    public function index()
    {
        return view('page.v1.barang.kategori.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.v1.barang.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'maintenance' => 'required',
        ]);

        try {
            \DB::beginTransaction();

            KategoriBarang::create([
                'nama_kategori' => $request->nama,
                'maintenance' => $request->maintenance,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kategori Barang Berhasil Ditambahkan',
                'redirect' => route('v1.barang.kategori.index'),
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
