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
        $serti = JenisSertifikat::where('id_sertifikat', $request->id)->first();
        if ($request->hasFile('file_serti')) {
            $file = $request->file('file_serti');
            $fileName = $request->nik. '-' . '.pdf';
            $dest = public_path('assets/sertifikat/' . $request->nik . '/');

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

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required',
            'due_date' => 'required|date',
        ]);

        // Cari row matrix yang sudah ada
        $matrix = MatrixPersonil::where('nik_karyawan', $request->nik)
            ->where('idSertifikat', $id)
            ->firstOrFail();

        if (!$matrix) {
            return response()->json([
                'success' => false,
                'message' => 'Data sertifikat tidak ditemukan',
            ], 404);
        }

        // Ambil nama sertifikat untuk nama file
        $serti = JenisSertifikat::where('id_sertifikat', $id)->first();

        $fileName = $matrix->file_serti;

        // Jika upload file baru → hapus lama → simpan baru
        if ($request->hasFile('file_serti')) {

            $oldPath = public_path("assets/sertifikat/{$request->nik}/{$matrix->file_serti}");
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $file = $request->file('file_serti');
            $fileName = $request->nik . '-'  . '.pdf';

            $dest = public_path("assets/sertifikat/{$request->nik}/");
            if (!file_exists($dest)) {
                mkdir($dest, 0755, true);
            }

            $file->move($dest, $fileName);
        }

        // UPDATE DATABASE
        $matrix->update([
            'file_serti' => $fileName,
            'due_date'   => $request->due_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sertifikat berhasil diupdate',
        ]);
    }


    // public function edit()
    // {
    //     $karyawan = DataKaryawan::orderBy('fullName')->get();
    //     $sertifikat = JenisSertifikat::orderBy('name')->get();
    //     return view('page.v1.hse.matrixPersonil.edit', compact('karyawan','sertifikat'));
    // }
    public function show($id)
    {
        $data = MatrixPersonil::find($id);

        if (!$data || !$data->file_serti) {
            return response()->json([
                'success' => false,
                'message' => 'File sertifikat tidak ditemukan.'
            ], 404);
        }
        
        $nik = $data->nik_karyawan;
        $fileName = $data->file_serti;
        $path = public_path('assets/sertifikat/' .$nik. '/' .$fileName);

        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak tersedia di server.'
            ], 404);
        }

        if (!file_exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak tersedia di server.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'url' => asset('assets/sertifikat/' .$nik. '/' .$fileName)
        ]);
    }
}
