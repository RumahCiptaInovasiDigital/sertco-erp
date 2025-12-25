<?php

namespace App\Http\Controllers\Page\MasterData\Barang;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\SatuanBarang;
use App\Models\DataKaryawan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Services\generateQR;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BarangMasterController extends Controller
{
    public function getData(Request $request)
    {
        $query = Barang::query()->latest()->orderBy('nama_barang', 'asc')->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn("status_barang", function ($row) {
                if ($row->status_barang == "1") {
                    return '<span class="badge badge-success">Baik</span>';
                } elseif ($row->status_barang == "2") {
                    return '<span class="badge badge-secondary">Rusak Ringan</span>';
                } elseif ($row->status_barang == "3") {
                    return '<span class="badge badge-danger">Rusak Berat</span>';
                } elseif ($row->status_barang == "4") {
                    return '<span class="badge badge-primary">Sedang Digunakan</span>';
                } elseif ($row->status_barang == "5") {
                    return '<span class="badge badge-info">Dipinjam</span>';
                } elseif ($row->status_barang == "6") {
                    return '<span class="badge badge-warning">Sedang Diperbaiki</span>';
                } else {
                    return '<span class="badge badge-dark">Hilang</span>';
                }
            })
            ->editColumn("status_kepemilikan", function ($row) {
                if ($row->status_kepemilikan == "1") {
                    return '<span class="badge badge-primary">Sertco Quality</span>';
                } else {
                    return '<span class="badge badge-info">Karyawan</span>';
                }
            })
            ->editColumn("nama_kategori", function ($row) {
                return $row->hasKategori ? $row->hasKategori->nama_kategori : '-';
            })
            ->editColumn("last_maintenance", function ($row) {
                return $row->last_maintenance == null ? "-" : date("d F Y",strtotime($row->last_maintenance));
            })
            ->editColumn("qty", function ($row) {
                return $row->qty_barang." ".$row->hasSatuan->satuan;
            })
            ->addColumn('action', function ($row) {
                return actionButtons($row->id_barang, 'v1.barang.master');
            })
            ->rawColumns([
                'action',
                'status_barang',
                'status_kepemilikan',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.barang.master.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['kategori'] = KategoriBarang::query()->latest()->get();
        $data['satuan'] = SatuanBarang::query()->latest()->get();
        $data['data_karyawan'] = DataKaryawan::query()->latest()->get();
        return view('page.v1.barang.master.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_perolehan' => 'required|date',
            'deskripsi_barang' => 'required|string',
            'jumlah_barang' => 'required|integer',
            'satuan_barang' => 'required|exists:satuan_barangs,id_satuan_barang',
            'kategori_barang' => 'required|exists:kategori_barangs,id_kategori_barang',
            'status_barang' => 'required|in:1,2,3,4,5,6,7',
            'status_kepemilikan' => 'required|in:1,2',
        ]);

        $kategoriBarang = KategoriBarang::find($request->kategori_barang);
        $kodeKategori = $kategoriBarang->kode_kategori;

        $lastBarang = Barang::where('id_kategori_barang', $request->kategori_barang)
            ->orderBy('created_at', 'desc')
            ->withTrashed()
            ->first();

        if ($lastBarang) {
            $lastKodeBarang = intval(substr($lastBarang->kode_barang, -3)) + 1;
            $newKodeBarang = str_pad($lastKodeBarang, 3, '0', STR_PAD_LEFT);
        } else {
            $newKodeBarang = '001';
        }

        $kodeBarang = "SQ-".$kodeKategori . '-' . $newKodeBarang;
        $data = [
            'nama_barang' => $request->nama,
            'kode_barang' => $kodeBarang,
            'deskripsi_barang' => $request->deskripsi_barang,
            'tanggal_perolehan' => $request->tgl_perolehan,
            'last_maintenance' => $request->last_maintenance ?? null,
            'qty_barang' => $request->jumlah_barang,
            'status_barang' => $request->status_barang,
            'status_kepemilikan' => $request->status_kepemilikan,
            'id_kategori_barang' => $request->kategori_barang,
            'id_satuan_barang' => $request->satuan_barang,
            'nik' => $request->karyawan ?? null,
        ];

        try {
            \DB::beginTransaction();

            Barang::create($data);

            \DB::commit();

            $text = $kodeBarang;
            $label = $kodeBarang;
            $path = public_path('assets/qr-code-barang/');
            $fileName = 'qr-barang-'.$kodeBarang.'.png';
            (new generateQR())->hendle($text, $label, $path, $fileName);

            return response()->json([
                'success' => true,
                'message' => 'Barang Berhasil Ditambahkan',
                'redirect' => route('v1.barang.master.index'),
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
        $data['barang'] = Barang::query()
            ->where('id_barang', $id)
            ->with(['hasKategori', 'hasSatuan'])
            ->first();

        return view('page.v1.barang.master.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['kategori'] = KategoriBarang::query()->latest()->get();
        $data['satuan'] = SatuanBarang::query()->latest()->get();
        $data['data_karyawan'] = DataKaryawan::query()->latest()->get();
        $data['barang'] = Barang::query()
            ->where('id_barang', $id)
            ->first();
        return view('page.v1.barang.master.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_perolehan' => 'required|date',
            'deskripsi_barang' => 'required|string',
            'jumlah_barang' => 'required|integer',
            'satuan_barang' => 'required|exists:satuan_barangs,id_satuan_barang',
            'kategori_barang' => 'required|exists:kategori_barangs,id_kategori_barang',
            'status_barang' => 'required|in:1,2,3,4,5,6,7',
            'status_kepemilikan' => 'required|in:1,2',
        ]);

        $data = [
            'nama_barang' => $request->nama,
            'deskripsi_barang' => $request->deskripsi_barang,
            'tanggal_perolehan' => $request->tgl_perolehan,
            'last_maintenance' => $request->last_maintenance ?? null,
            'qty_barang' => $request->jumlah_barang,
            'status_barang' => $request->status_barang,
            'status_kepemilikan' => $request->status_kepemilikan,
            'id_kategori_barang' => $request->kategori_barang,
            'id_satuan_barang' => $request->satuan_barang,
            'nik' => $request->karyawan ?? null,
        ];

        try {
            \DB::beginTransaction();

            Barang::where('id_barang', $id)->update($data);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Barang Berhasil Diupdate',
                'redirect' => route('v1.barang.master.index'),
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

        $data = Barang::query()
            ->where('id_barang', $id)
            ->first();

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Dihapus.',
        ]);
    }

    /**
     * Export barang data to Excel (XLSX)
     */
    public function export(Request $request)
    {
        $items = Barang::with(['hasKategori', 'hasSatuan'])->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = [
            'No',
            'Kode Barang',
            'Nama Barang',
            'Kategori',
            'Jumlah',
            'Satuan',
            'Status Barang',
            'Status Kepemilikan',
            'Tanggal Perolehan',
            'Last Maintenance',
            'Deskripsi',
            'NIK'
        ];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col.'1', $h);
            $col++;
        }

        $row = 2;
        foreach ($items as $index => $item) {
            $statusBarang = match ($item->status_barang) {
                '1' => 'Baik',
                '2' => 'Rusak Ringan',
                '3' => 'Rusak Berat',
                '4' => 'Sedang Digunakan',
                '5' => 'Dipinjam',
                '6' => 'Sedang Diperbaiki',
                default => 'Hilang',
            };

            $statusKepemilikan = $item->status_kepemilikan == '1' ? 'Sertco Quality' : 'Karyawan';

            $sheet->setCellValue('A'.$row, $index + 1);
            $sheet->setCellValue('B'.$row, $item->kode_barang ?? '-');
            $sheet->setCellValue('C'.$row, $item->nama_barang ?? '-');
            $sheet->setCellValue('D'.$row, $item->hasKategori->nama_kategori ?? '-');
            $sheet->setCellValue('E'.$row, $item->qty_barang ?? 0);
            $sheet->setCellValue('F'.$row, $item->hasSatuan->satuan ?? '-');
            $sheet->setCellValue('G'.$row, $statusBarang);
            $sheet->setCellValue('H'.$row, $statusKepemilikan);
            $sheet->setCellValue('I'.$row, $item->tanggal_perolehan ? date('Y-m-d', strtotime($item->tanggal_perolehan)) : '-');
            $sheet->setCellValue('J'.$row, $item->last_maintenance ? date('Y-m-d', strtotime($item->last_maintenance)) : '-');
            $sheet->setCellValue('K'.$row, $item->deskripsi_barang ?? '-');
            $sheet->setCellValue('L'.$row, $item->nik ?? '-');

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'data-barang-'.date('Ymd_His').'.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }
}
