<?php

namespace App\Http\Controllers\Page\HSE\MatrixPersonil;

use App\Http\Controllers\Controller;
use App\Models\DataKaryawan;
use App\Models\JenisSertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MatrixPersonilController extends Controller
{
    public function index()
    {
        $jenisSerti = JenisSertifikat::orderByRaw('CAST(sort_num AS UNSIGNED) ASC')->get();
        return view('page.v1.hse.matrixPersonil.index', compact('jenisSerti'));
    }

    public function getKaryawan($id)
    {
        $karyawan = DataKaryawan::find($id);

        if (!$karyawan) {
            return response()->json(['error' => 'Data karyawan tidak ditemukan'], 404);
        }

        return response()->json($karyawan);
    }

    public function create()
    {
        $karyawan = DataKaryawan::orderBy('fullName')->get();
        $sertifikat = JenisSertifikat::orderBy('name')->get();
        return view('page.v1.hse.matrixPersonil.create', compact('karyawan','sertifikat'));
    }
}
