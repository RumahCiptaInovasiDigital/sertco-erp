<?php

namespace App\Http\Controllers\Page\HSE\PeminjamanAlat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PeminjamanAlatController extends Controller
{
    public function index()
    {
        return view('page.v1.hse.peminjamanAlat.index');
    }

    public function create()
    {
        return view('page.v1.hse.peminjamanAlat.create');
    }
}
