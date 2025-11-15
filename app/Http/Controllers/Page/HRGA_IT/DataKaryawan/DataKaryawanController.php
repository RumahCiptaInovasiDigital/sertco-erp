<?php

namespace App\Http\Controllers\Page\HRGA_IT\DataKaryawan;

use App\Http\Controllers\Controller;
use App\Models\DataKaryawan;
use App\Models\Departemen;
use App\Models\Role;
use App\Models\UserCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Yajra\DataTables\Facades\DataTables;

class DataKaryawanController extends Controller
{
    public function getData(Request $request)
    {
        $query = DataKaryawan::query()
            ->select([
                'id',
                'nik',
                'inisial',
                'fullName',
                'email',
                'phoneNumber',
                'namaJabatan',
            ])->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('fullName', function ($row) {
                return $row->fullName.' <b>('.$row->inisial.')</b>';
            })
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.data-karyawan.edit', $row->id).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id.'\')"><i class="fas fa-trash"></i></button>
                        <a href="'.route('v1.data-karyawan.detail', $row->id).'" class="btn btn-sm btn-primary ms-2">Detail</a>';
            })
            ->rawColumns([
                'action',
                'fullName',
            ])
            ->make(true);
    }

    public function detail($id)
    {
        $data = DataKaryawan::find($id);
        dd($data);
        // return view('page.v1.hrga_it.dataKaryawan.detail', compact('data'));
    }

    public function index()
    {
        return view('page.v1.hrga_it.dataKaryawan.index');
    }

    public function create()
    {
        $departemen = Departemen::orderBy('name')->get();
        $role = Role::orderBy('name')->get();

        return view('page.v1.hrga_it.dataKaryawan.create', compact('departemen', 'role'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'pendidikan' => 'required|string|max:255',
            'tempatLahir' => 'required|string|max:255',
            'tanggalLahir' => 'required|date',
            'email' => 'required|string|max:255',
            'phoneNumber' => 'required|string|max:255',
            'agama' => 'required|string|max:255',
            'emergencyContact' => 'required|string|max:255',
            'emergencyName' => 'required|string|max:255',
            'emergencyRelation' => 'required|string|max:255',
            'inisial' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'idDepartemen' => 'required|string|max:255',
            'idJabatan' => 'required|string|max:255',
            'empDateStart' => 'required|date',
        ]);

        // Pilihan Agama Lainnya
        $agama = $request->agama === 'lainnya' ? $request->agamaLain : $request->agama;
    
        // Buat nama lengkap otomatis
        $fullName = trim($request->firstName . ' ' . $request->lastName);

        // Buat NIK otomatis
        $lastData = DataKaryawan::where('nik', 'LIKE', 'SQ-%')
                    ->orderByRaw("CAST(RIGHT(nik, 3) AS UNSIGNED) DESC")
                    ->first();
        $lastNumber = 0;
        if ($lastData && preg_match('/SQ-\w+-(\d{3})$/', $lastData->nik, $matches)) {
            $lastNumber = (int) $matches[1];
        }
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        $nik = 'SQ-' . strtoupper($request->inisial) . '-' . $newNumber;

        $departemen = Departemen::where('id_departemen', $request->idDepartemen)->first();
        $role = Role::where('id_role', $request->idJabatan)->first();

        try {
            DB::beginTransaction();

            $data = DataKaryawan::create([
                'fullName' => $fullName,
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'pendidikan' => $request->pendidikan,
                'tempatLahir' => $request->tempatLahir,
                'tanggalLahir' => $request->tanggalLahir,
                'noKTP' => $request->noKTP,
                'noSIM' => $request->noSIM,
                'noNPWP' => $request->noNPWP,
                'alamat' => $request->alamat,
                'agama' => $agama,
                'email' => $request->email,
                'phoneNumber' => $request->phoneNumber,
                'ijazah' => $request->ijazahPath,
                'foto' => $request->foto,
                'statusTK' => $request->statusTK,
                'statusPTKP' => $request->statusPTKP,
                'noRekening' => $request->noRekening,
                'nik' => $nik,
                'inisial' => strtoupper($request->inisial),
                'grade' => $request->grade,
                'nppBpjsTk' => $request->nppBpjsTk,
                'BpjsKes' => $request->BpjsKes,
                'AXA' => $request->AXA,
                'idJabatan' => $request->idJabatan,
                'namaJabatan' => $role->name,
                'idDepartemen' => $request->idDepartemen,
                'namaDepartemen' => $departemen->name,
                'empDateStart' => $request->empDateStart,
                'empDateEnd' => $request->empDateEnd,
                'joinDate' => $request->empDateStart, // otomatis
                'resignDate' => $request->resignDate,
                'emergencyContact' => $request->emergencyContact,
                'emergencyName' => $request->emergencyName,
                'emergencyRelation' => $request->emergencyRelation,
            ]);

            //Upload Foto
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = $data->id . '.jpg';

                $destinationPath = public_path('assets/dokumen/'. $data->id . '/foto-karyawan');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $foto->move($destinationPath, $filename);
                $fotoPath = 'assets/dokumen/' .$data->id . '/foto/' . $filename;
            }

            // Upload Ijazah (PDF)
            $ijazahPath = null;
            if ($request->hasFile('ijazah')) {
                $ijazah = $request->file('ijazah');
                $ijazahFilename = 'ijazah-' . $data->id . '.pdf';

                $destinationPath = public_path('assets/dokumen/'. $data->id . '/ijazah-karyawan');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $ijazah->move($destinationPath, $ijazahFilename);
                $ijazahPath = 'assets/dokumen/' .$data->id . '/ijazah/' . $ijazahFilename;
            }

            // Upload KTP
            if ($request->hasFile('fileKTP')) {
                $ktp = $request->file('fileKTP');
                $ktpFilename = $data->noKTP . '.jpg';

                $destinationPath = public_path('assets/dokumen/'. $data->id . '/foto-ktp');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $ktp->move($destinationPath, $ktpFilename);
            }

            // Upload SIM
            if ($request->hasFile('fileSIM')) {
                $sim = $request->file('fileSIM');
                $simFilename = $data->noSIM . '.jpg';

                $destinationPath = public_path('assets/dokumen/'. $data->id . '/foto-sim');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $sim->move($destinationPath, $simFilename);
            }

            // Upload NPWP
            if ($request->hasFile('fileNPWP')) {
                $npwp = $request->file('fileNPWP');
                $npwpFilename = $data->noNPWP . '.jpg';

                $destinationPath = public_path('assets/dokumen/'. $data->id . '/foto-npwp');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $npwp->move($destinationPath, $npwpFilename);
            }

            $data->update([
                'foto' => $filename,
                'ijazah' => $ijazahFilename,
            ]);

            UserCredential::create([
                'nik' => $nik,
                'pass' => password_hash('password123', PASSWORD_DEFAULT),
            ]);
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service Type created successfully',
                'redirect' => route('v1.data-karyawan.index'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong '.$th->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $data = DataKaryawan::find($id);

        return view('page.v1.hrga_it.dataKaryawan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $data = DataKaryawan::find($id);
            $data->update(['name' => $request->name]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service Type updated successfully',
                'redirect' => route('v1.data-karyawan.index'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong '.$th->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID is required.',
            ]);
        }

        $data = DataKaryawan::find($id);

        // Hapus role
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
