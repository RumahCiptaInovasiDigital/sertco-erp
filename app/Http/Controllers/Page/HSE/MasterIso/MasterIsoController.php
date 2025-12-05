<?php

namespace App\Http\Controllers\Page\HSE\MasterIso;

use App\Http\Controllers\Controller;
use App\Models\MasterIso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MasterIsoController extends Controller
{
        public function getData(Request $request)
    {
        $query = masterIso::query()
            ->select([
                'id',
                'name',
                'petugas',
                'tgl_audit',
            ])->latest()->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '<a href="'.route('v1.master-iso.edit', $row->id).'" class="btn btn-sm btn-warning me-2"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" onclick="deleteData(\''.$row->id.'\')"><i class="fas fa-trash"></i></button>';
            })
            ->rawColumns([
                'action',
            ])
            ->make(true);
    }
    public function index()
    {
        return view('page.v1.hse.masterIso.index');
    }

    public function create()
    {
        return view('page.v1.hse.masterIso.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'petugas' => 'required|string|max:255',
            'tgl_audit' => 'required|date',
            // 'fileIso' => 'required|string|max:255',
        ]);

        $fileIso = null;
        $linkIso = null;
        $date = now()->format('Ymd_His');

        if ($request->upload_type ==='file') {
            if ($request->hasFile('fileIso')) {
                $file = $request->file('fileIso');
                $fileName = $request->name. '-' .$date. '.pdf';
                $dest = public_path('assets/ISO/' . $request->name . '/');

                if (!file_exists($dest)) {
                    mkdir($dest, 0755, true);
                }
                // pindahkan file
                $file->move($dest, $fileName);
                $fileIso = $fileName;
            }
        } elseif ($request->upload_type === 'link') {
            $linkIso = $request->linkIso;
        }
        try {
            \DB::beginTransaction();

            MasterIso::create([
                'name' => $request->name,
                'petugas' => $request->petugas,
                'tgl_audit' => $request->tgl_audit,
                'fileIso' => $fileIso,
                'linkIso' => $linkIso,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Service Kategori created successfully',
                'redirect' => route('v1.master-iso.index'),
            ]);
        } catch (\Throwable $th) {
            \DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong '.$th->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $data = MasterIso::query()
            ->where('id', $id)
            ->first();

        return view('page.v1.hse.masterIso.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'petugas' => 'required|string|max:255',
            'tgl_audit' => 'required|date',
            // 'fileIso' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $data = MasterIso::query()->where('id', $id)->firstOrFail();

            $fileIso = $data->fileIso;
            $linkIso = $data->linkIso;
            
            $date = now()->format('Ymd_His');
            $newFileName = $fileIso;
            $newLink = $linkIso;
            if ($request->upload_type ==='file') {
                if ($request->hasFile('fileIso')) {

                    // hapus file lama jika ada
                    if ($fileIso) {
                        $oldPath = public_path('assets/ISO/' .$data->name . '/' . $fileIso);
                        if (file_exists($oldPath)) unlink($oldPath);
                    }

                    $file = $request->file('fileIso');
                    $newFileName = $request->name. '-' .$date. '.pdf';

                    $dest = public_path('assets/ISO/' . $data->name . '/');
                    if (!file_exists($dest)) {
                        mkdir($dest, 0755, true);
                    }
                    // pindahkan file
                    $file->move($dest, $newFileName);
                    // $fileIso = $newFileName;
                }
                $newLink = null;
            } elseif ($request->upload_type === 'link') {

                    // hapus file lama jika ada
                    if ($fileIso) {
                        $oldPath = public_path('assets/ISO/' .$data->name . '/' . $fileIso);
                        if (file_exists($oldPath)) unlink($oldPath);
                    }

                $newLink = $request->linkIso;
                $newFileName = null;
            }

            $data->update([
                'name' => $request->name,
                'petugas' => $request->petugas,
                'tgl_audit' => $request->tgl_audit,
                'fileIso' => $newFileName,
                'linkIso' => $newLink,
            ]);

            MasterIso::updateOrCreate(
                ['id' => $data->id], // kondisi pencarian
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data updated successfully',
                'redirect' => route('v1.master-iso.index'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: '.$th->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $data = MasterIso::query()
        ->where('id', $id)
        ->first();

        return view('page.v1.hse.masterIso.show', compact('data'));
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

        $data = MasterIso::query()
            ->where('id', $id)
            ->first();

        // Hapus 
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data deleted successfully.',
        ]);
    }
}
