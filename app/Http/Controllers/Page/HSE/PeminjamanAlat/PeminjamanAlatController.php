<?php

namespace App\Http\Controllers\Page\HSE\PeminjamanAlat;

use App\Http\Controllers\Controller;
use App\Models\DataPeralatan;
use App\Models\PeminjamanAlat;
use App\Models\PeminjamanAlatDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PeminjamanAlatController extends Controller
{
    public function getData(Request $request)
    {
        $query = PeminjamanAlat::query()->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('nikUser', function ($row) {
                return $row->karyawan->fullName ?? '-';
            })
            ->editColumn('approved', function ($row) {
                if ($row->approved === '0') {
                    return '<div class="text-center">
                                <span class="badge badge-info">Menunggu Persetujuan</span>
                            </div>';
                } elseif ($row->approved === '1') {
                    return '<div class="text-center">
                                <span class="badge badge-danger w-100 d-block text-center">Ditolak</span>
                            </div>';
                } else {
                    return '<div class="text-center">
                        <span class="badge badge-success w-100 d-block text-center">Disetujui</span>
                        </div>';
                }})
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.data-peminjaman.edit', $row->id).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id.'\')"><i class="fas fa-trash"></i></button>
                        <a href="'.route('v1.data-peminjaman.detail', $row->id).'" class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></a>';
            })
            ->rawColumns([
                'approved',
                'action',
            ])
            ->make(true);
    }

    public function index()
    {
        return view('page.v1.hse.peminjamanAlat.index');
    }

    public function getAlat($id)
    {
        $alat = DataPeralatan::find($id);

        if (!$alat) {
            return response()->json(['error' => 'Data alat tidak ditemukan'], 404);
        }

        return response()->json($alat);
    }

    public function create()
    {
        $alat = DataPeralatan::orderBy('name')->get();
        return view('page.v1.hse.peminjamanAlat.create', compact('alat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'required|date',
            'idAlat'          => 'required|array|min:1',  // minimal harus ada 1 alat
        ]);
    
        DB::beginTransaction();
    
        try {
    
            // Hitung total alat
            $totalAlat = count($request->idAlat);
    
            //simpan peminjaman
            $peminjaman = PeminjamanAlat::create([
                'nikUser'         => $request->nikUser,
                'namaClient'      => $request->namaClient,
                'tanggal_pinjam'  => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'total_alat'      => $totalAlat,   // <-- Simpan total alat
            ]);
    
            //simpan detail peminjaman alat
            foreach ($request->idAlat as $i => $alatId) {
    
                PeminjamanAlatDetail::create([
                    'idPeminjaman'  => $peminjaman->id,
                    'idAlat'         => $alatId,
                    'merkAlat'       => $request->merkAlat[$i],
                    'tipeAlat'       => $request->tipeAlat[$i],
                    'snAlat'         => $request->snAlat[$i],
                    'kondisiSebelum' => $request->kondisiSebelum[$i],
                ]);
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil disimpan',
                'redirect' => route('v1.data-peminjaman.index'),
            ]);
    
        } catch (\Throwable $th) {
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    
    public function edit($id)
    {
        $dataPeminjaman = PeminjamanAlat::query()
        ->where('id', $id)
        ->first();

        $dataDetail = PeminjamanAlatDetail::query()
        ->where('idPeminjaman', $id)
        ->get();

        $alat = DataPeralatan::orderBy('name')->get();

        return view('page.v1.hse.peminjamanAlat.edit', compact('dataDetail', 'dataPeminjaman', 'alat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'required|date',
            'idAlat'          => 'required|array|min:1',  // minimal harus ada 1 alat
        ]);
    
        try {

            DB::beginTransaction();
            // Hitung total alat
            $totalAlat = count($request->idAlat);

            $data = PeminjamanAlat::query()->where('id', $id)->firstOrFail();
    
            //simpan peminjaman
            $data->update([
                'namaClient'      => $request->namaClient,
                'tanggal_pinjam'  => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'total_alat'      => $totalAlat,
            ]);

            PeminjamanAlatDetail::where('idPeminjaman', $id)->delete();
            
            //simpan detail peminjaman alat
            foreach ($request->idAlat as $i => $idAlat) {
                PeminjamanAlatDetail::create([
                    'idPeminjaman'   => $data->id,
                    'idAlat'         => $idAlat,
                    'merkAlat'       => $request->merkAlat[$i],
                    'tipeAlat'       => $request->tipeAlat[$i],
                    'snAlat'         => $request->snAlat[$i],
                    'kondisiSebelum' => $request->kondisiSebelum[$i],
                ]);
            }

            PeminjamanAlat::updateOrCreate(
                ['id' => $data->id], // kondisi pencarian
            );

    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil disimpan',
                'redirect' => route('v1.data-peminjaman.index'),
            ]);
    
        } catch (\Throwable $th) {
            DB::rollBack();
    
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function detail($id)
    {
        $dataPeminjaman = PeminjamanAlat::query()
        ->where('id', $id)
        ->first();

        $dataDetail = PeminjamanAlatDetail::query()
        ->where('idPeminjaman', $id)
        ->get();

        return view('page.v1.hse.peminjamanAlat.detail', compact('dataDetail', 'dataPeminjaman'));
    }
}
