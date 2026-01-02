<?php

namespace App\Http\Controllers\Page\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Suplier;
use App\Models\JenisSuplier;
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
            ->editColumn("jenis", function ($row) {
                    return $row->hasJenis->nama_jenis_suplier;
            })
            ->editColumn("telp_suplier", function ($row) {
                if($row->telp_suplier != null) {
                    return $row->telp_suplier;
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
        $jenisSuplier = JenisSuplier::all();
        return view('page.v1.suplier.create', compact('jenisSuplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required',
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'bayar' => 'required',
            'syarat' => 'required',
            'nama_kontak' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'hp' => 'required|numeric',
            'email' => 'required|string|max:255',
            'norek' => 'required|numeric',
            'bank' => 'required|string|max:255',
            'nama_pemilik_rek' => 'required|string|max:255',
            'cabang_bank' => 'required|string|max:255',
        ]);

        if($request->cp != null) {
            $request->validate([
                'file_cp' => 'required|mimes:pdf|max:5120',
            ]);
        }

        if($request->npwp != null) {
            $request->validate([
                'file_npwp' => 'required|mimes:pdf|max:5120',
            ]);
        }

        if($request->siup != null) {
            $request->validate([
                'file_siup' => 'required|mimes:pdf|max:5120',
            ]);
        }

        if($request->tdp != null) {
            $request->validate([
                'file_tdp' => 'required|mimes:pdf|max:5120',
            ]);
        }

        if($request->akta != null) {
            $request->validate([
                'file_akta' => 'required|mimes:pdf|max:5120',
            ]);
        }

        if($request->domisili != null) {
            $request->validate([
                'file_domisili' => 'required|mimes:pdf|max:5120',
            ]);
        }

        if($request->sertifikat != null) {
            $request->validate([
                'file_sertifikat' => 'required|mimes:pdf|max:5120',
            ]);
        }

        try {
            \DB::beginTransaction();

            $data = Suplier::create([
                'id_jenis_suplier' => $request->jenis,
                'nama_suplier' => $request->nama,
                'alamat_suplier' => $request->alamat,
                'cara_bayar' => $request->bayar,
                'syarat_pembayaran' => $request->syarat,
                'nama_kontak' => $request->nama_kontak,
                'jabatan_kontak' => $request->jabatan,
                'telp_suplier' => $request->telp ?? null,
                'hp_suplier' => $request->hp,
                'email_suplier' => $request->email,
                'web_suplier' => $request->web ?? null,
                'norek_suplier' => $request->norek,
                'bank_suplier' => $request->bank,
                'nama_pemilik_rek' => $request->nama_pemilik_rek,
                'cabang_bank' => $request->cabang_bank,
            ]);

            if ($request->hasFile('file_cp')) {
                $file_cp = $request->file('file_cp');
                $fileName_cp = 'cp_suplier_'.date('ymdHis').'.'.$file_cp->getClientOriginalExtension();

                $destinationPath = public_path('assets/dokumen/suplier/'.$data->id_suplier.'/company-profile');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file_cp->move($destinationPath, $fileName_cp);

                $data->update([
                    'file_cp' => $fileName_cp,
                ]);
            }

            if ($request->hasFile('file_npwp')) {
                $file_npwp = $request->file('file_npwp');
                $fileName_npwp = 'npwp_suplier_'.date('ymdHis').'.'.$file_npwp->getClientOriginalExtension();

                $destinationPath = public_path('assets/dokumen/suplier/'.$data->id_suplier.'/npwp');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file_npwp->move($destinationPath, $fileName_npwp);

                $data->update([
                    'file_npwp' => $fileName_npwp,
                ]);
            }

            if ($request->hasFile('file_siup')) {
                $file_siup = $request->file('file_siup');
                $fileName_siup = 'siup_suplier_'.date('ymdHis').'.'.$file_siup->getClientOriginalExtension();

                $destinationPath = public_path('assets/dokumen/suplier/'.$data->id_suplier.'/siup');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file_siup->move($destinationPath, $fileName_siup);

                $data->update([
                    'file_siup' => $fileName_siup,
                ]);
            }

            if ($request->hasFile('file_tdp')) {
                $file_tdp = $request->file('file_tdp');
                $fileName_tdp = 'tdp_suplier_'.date('ymdHis').'.'.$file_tdp->getClientOriginalExtension();

                $destinationPath = public_path('assets/dokumen/suplier/'.$data->id_suplier.'/tdp');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file_tdp->move($destinationPath, $fileName_tdp);

                $data->update([
                    'file_tdp' => $fileName_tdp,
                ]);
            }

            if ($request->hasFile('file_akta')) {
                $file_akta = $request->file('file_akta');
                $fileName_akta = 'akta_suplier_'.date('ymdHis').'.'.$file_akta->getClientOriginalExtension();

                $destinationPath = public_path('assets/dokumen/suplier/'.$data->id_suplier.'/akta');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file_akta->move($destinationPath, $fileName_akta);

                $data->update([
                    'file_akta' => $fileName_akta,
                ]);
            }

            if ($request->hasFile('file_domisili')) {
                $file_domisili = $request->file('file_domisili');
                $fileName_domisili = 'domisili_suplier_'.date('ymdHis').'.'.$file_domisili->getClientOriginalExtension();

                $destinationPath = public_path('assets/dokumen/suplier/'.$data->id_suplier.'/domisili');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file_domisili->move($destinationPath, $fileName_domisili);

                $data->update([
                    'file_domisili' => $fileName_domisili,
                ]);
            }

            if ($request->hasFile('file_sertifikat')) {
                $file_sertifikat = $request->file(' file_sertifikat ');
                $fileName_sertifikat = 'sertifikat_suplier_'.date('ymdHis').'.'.$file_sertifikat->getClientOriginalExtension();

                $destinationPath = public_path('assets/dokumen/suplier/'.$data->id_suplier.'/sertifikat');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $file_sertifikat->move($destinationPath, $fileName_sertifikat);

                $data->update([
                    'file_sertifikat' => $fileName_sertifikat,
                ]);
            }

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
     * Export Suplier data to Excel (XLSX)
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
     * Import suplier data from uploaded Excel (XLSX)
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
