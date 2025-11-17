<?php

namespace App\Http\Controllers\Page\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Suplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class SuplierController extends Controller
{
    public function getData(Request $request)
    {
        $query = Suplier::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn("norek", function ($row) {
                if($row->norek_suplier != null && $row->bank_suplier != null) {
                    return $row->norek_suplier." (".$row->bank_suplier.")";
                } else {
                    return "-";
                }
            })
            ->editColumn("cp", function ($row) {
                if($row->nama_kontak != null && $row->nohp_kontak != null) {
                    return $row->nohp_kontak." (".$row->nama_kontak.")";
                } else {
                    return "-";
                }
            })
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.suplier.edit', $row->id_suplier).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id_suplier.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.suplier.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.v1.suplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        try {
            \DB::beginTransaction();

            Suplier::create([
                'nama_suplier' => $request->nama,
                'telp_suplier' => $request->telp ?? null,
                'alamat_suplier' => $request->alamat,
                'email_suplier' => $request->email,
                'norek_suplier' => $request->norek ?? null,
                'bank_suplier' => $request->bank ?? null,
                'nama_kontak' => $request->kontak ?? null,
                'nohp_kontak' => $request->hp_kontak ?? null,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Suplier Berhasil Ditambahkan',
                'redirect' => route('v1.suplier.index'),
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
        $data['suplier'] = Suplier::query()
            ->where('id_suplier', $id)
            ->first();

        return view('page.v1.suplier.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        try {
            \DB::beginTransaction();

            $data = Suplier::query()
                ->where('id_suplier', $id)
                ->first();
            $data->update([
                'nama_suplier' => $request->nama,
                'telp_suplier' => $request->telp ?? null,
                'alamat_suplier' => $request->alamat,
                'email_suplier' => $request->email,
                'norek_suplier' => $request->norek ?? null,
                'bank_suplier' => $request->bank ?? null,
                'nama_kontak' => $request->kontak ?? null,
                'nohp_kontak' => $request->hp_kontak ?? null,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Suplier Berhasil Diperbarui',
                'redirect' => route('v1.suplier.index'),
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

        $data = Suplier::query()
            ->where('id_suplier', $id)
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
        $items = Suplier::query()->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = [
            'No',
            'Nama Suplier',
            'Telp Suplier',
            'Alamat Suplier',
            'Email Suplier',
            'Norek Suplier',
            'Bank Suplier',
            'Nama Kontak',
            'No HP Kontak',
        ];

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col.'1', $h);
            $col++;
        }

        $row = 2;
        foreach ($items as $index => $item) {
            $sheet->setCellValue('A'.$row, $index + 1);
            $sheet->setCellValue('B'.$row, $item->nama_suplier ?? '#');
            $sheet->setCellValue('C'.$row, $item->telp_suplier ?? '#');
            $sheet->setCellValue('D'.$row, $item->alamat_suplier ?? '#');
            $sheet->setCellValue('E'.$row, $item->email_suplier ?? '#');
            $sheet->setCellValue('F'.$row, $item->norek_suplier ?? '#');
            $sheet->setCellValue('G'.$row, $item->bank_suplier ?? '#');
            $sheet->setCellValue('H'.$row, $item->nama_kontak ?? '#');
            $sheet->setCellValue('I'.$row, $item->nohp_kontak ?? '#');

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'data-suplier-'.date('Ymd_His').'.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }

    /**
     * Import suplier data from uploaded Excel (XLSX/XLS/CSV)
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $file = $request->file('file');

        try {
            \DB::beginTransaction();

            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Expect header in first row. Start from second row.
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                // Map columns (0-based):
                // 0 Nama, 1 Telp, 2 Alamat, 3 Email, 4 Norek, 5 Bank, 6 Nama Kontak, 7 No HP Kontak
                $nama = isset($row[0]) ? trim($row[0]) : null;
                if (empty($nama)) {
                    continue; // skip rows without name
                }

                Suplier::create([
                    'nama_suplier' => $nama,
                    'telp_suplier' => isset($row[1]) && $row[1] !== '' ? trim($row[1]) : null,
                    'alamat_suplier' => isset($row[2]) && $row[2] !== '' ? trim($row[2]) : null,
                    'email_suplier' => isset($row[3]) && $row[3] !== '' ? trim($row[3]) : null,
                    'norek_suplier' => isset($row[4]) && $row[4] !== '' ? trim($row[4]) : null,
                    'bank_suplier' => isset($row[5]) && $row[5] !== '' ? trim($row[5]) : null,
                    'nama_kontak' => isset($row[6]) && $row[6] !== '' ? trim($row[6]) : null,
                    'nohp_kontak' => isset($row[7]) && $row[7] !== '' ? trim($row[7]) : null,
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Import berhasil',
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengimpor: '.$th->getMessage(),
            ], 500);
        }
    }
}
