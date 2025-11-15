<?php

namespace App\Http\Controllers\Page\MasterData\Barang;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\SatuanBarang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Services\generateQR;

class BarangMasterController extends Controller
{
    public function generateQrCode()
    {
        $text = "This is a sample QR code text.";
        $label = "Sertco ERP";
        $path = public_path('assets/qr_code_barang/');
        $fileName = 'sample-qr-code.png';
        (new generateQR())->hendle($text, $label, $path, $fileName);
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
