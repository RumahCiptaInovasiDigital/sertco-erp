<?php

namespace App\Http\Controllers\Page\HSE\MatrixPersonil;

use App\Http\Controllers\Controller;
use App\Models\DataKaryawan;
use App\Models\JenisSertifikat;
use App\Models\MatrixPersonil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MatrixPersonilController extends Controller
{
    public function index()
    {
        $dataKaryawan = DataKaryawan::with(['sertifikat'])->orderBy('fullName')->get();
        $jenisSerti = JenisSertifikat::orderByRaw('CAST(sort_num AS UNSIGNED) ASC')->get();
        return view('page.v1.hse.matrixPersonil.index', compact('dataKaryawan', 'jenisSerti'));
    }

    public function getKaryawan($id)
    {
        $karyawan = DataKaryawan::find($id);

        if (!$karyawan) {
            return response()->json(['error' => 'Data karyawan tidak ditemukan'], 404);
        }

        $sertifikat = JenisSertifikat::orderBy('name')->get();
        return response()->json([
            'karyawan' => $karyawan,
            'view' => view('page.v1.hse.matrixPersonil.partials.daftar-sertifikat', compact('karyawan','sertifikat'))->render(),
        ]);
    }

    public function create()
    {
        $karyawan = DataKaryawan::orderBy('fullName')->get();
        $sertifikat = JenisSertifikat::orderBy('name')->get();
        return view('page.v1.hse.matrixPersonil.create', compact('karyawan','sertifikat'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'nik' => 'required',
        //     'no_sertifikat' => 'required',
        //     'tanggal_terbit' => 'required|date',
        //     'tanggal_expired' => 'required|date|after:tanggal_terbit',
        // ]);

        if ($request->hasFile('file_serti')) {

            $file = $request->file('file_serti');
        
            // nama file yang aman & unik
            $fileName = time() . '_priced_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        
            $dest = public_path('assets/sertifikat/' . $request->nik . '/');
        
            // buat folder jika belum ada
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }
        
            // pindahkan file
            $file->move($dest, $fileName);
        
        } else {
            $fileName = null; // supaya tidak undefined
        }
        
        MatrixPersonil::create([
            'nik_karyawan' => $request->nik,
            'idSertifikat' => $request->id,
            'file_serti' => $fileName,
            'due_date' => $request->due_date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil ditambahkan',
        ]);
        
    }
}
