<?php

namespace App\Http\Controllers\Presensi;

use App\Models\BranchOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class KantorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data Kantor Cabang';
        return view('page.master.kantor', compact('title'));
    }

    /**
     * Get data for DataTables
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = BranchOffice::select(['id', 'name', 'address', 'city', 'phone', 'email']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    //                    $btn = '<button class="btn btn-sm btn-info view-btn" data-id="' . $row->id . '" title="Lihat Peta"><i class="fas fa-map-marked-alt"></i></button> ';
                    $btn = '<button class="btn btn-sm btn-warning edit-btn" data-id="' . $row->id . '" title="Edit"><i class="fas fa-edit"></i></button> ';
                    $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '" title="Hapus"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'address', 'city', 'phone', 'email']);
        $data['id'] = Str::uuid();
        $data['coordinates'] = [
            'lat' => $request->latitude,
            'lng' => $request->longitude,
            'radius' => $request->radius,
        ];

        BranchOffice::create($data);

        return response()->json(['success' => 'Kantor cabang berhasil ditambahkan!']);
    }

    /**
     * Get branch office data for editing
     */
    public function getEdit($id)
    {
        $branchOffice = BranchOffice::findOrFail($id);
        $coordinates = $branchOffice->coordinates ?? [];

        return response()->json([
            'id' => $branchOffice->id,
            'name' => $branchOffice->name,
            'address' => $branchOffice->address,
            'city' => $branchOffice->city,
            'phone' => $branchOffice->phone,
            'email' => $branchOffice->email,
            'latitude' => $coordinates['lat'] ?? null,
            'longitude' => $coordinates['lng'] ?? null,
            'radius' => $coordinates['radius'] ?? null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $branchOffice = BranchOffice::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'address', 'city', 'phone', 'email']);
        $data['coordinates'] = [
            'lat' => $request->latitude,
            'lng' => $request->longitude,
            'radius' => $request->radius,
        ];

        $branchOffice->update($data);

        return response()->json(['success' => 'Kantor cabang berhasil diupdate!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $branchOffice = BranchOffice::findOrFail($id);
        $branchOffice->delete();

        return response()->json(['success' => 'Kantor cabang berhasil dihapus!']);
    }

    /**
     * Get all branch office data for map display.
     */
    public function getAllKantorForMap()
    {
        $data = BranchOffice::all()->map(function ($item) {
            $coordinates = $item->coordinates ?? [];
            return [
                'id' => $item->id,
                'name' => $item->name,
                'address' => $item->address,
                'city' => $item->city,
                'latitude' => $coordinates['lat'] ?? null,
                'longitude' => $coordinates['lng'] ?? null,
                'radius' => $coordinates['radius'] ?? null,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
