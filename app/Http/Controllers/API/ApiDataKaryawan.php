<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DataKaryawan;
use Illuminate\Http\Request;

class ApiDataKaryawan extends Controller
{
    public function index(Request $request)
    {
        $query = DataKaryawan::query();

        return response()->json([
            'message' => 'Success',
            'data' => $query->get(),
        ]);
    }

    // public function show($id)
    // {
    //     $data = DataKaryawan::find($id);
    //     if (!$data) {
    //         return response()->json(['message' => 'Data not found'], 404);
    //     }

    //     return response()->json($data);
    // }

    public function getByNik($nik)
    {
        $data = DataKaryawan::where('nik', $nik)->first();
        if (!$data) {
            return response()->json(['message' => 'Karyawan dengan NIK tersebut tidak ditemukan'], 404);
        }

        return response()->json($data);
    }

    public function getByName($name)
    {
        $data = DataKaryawan::where('fullName', 'like', "%{$name}%")->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'Karyawan dengan nama tersebut tidak ditemukan'], 404);
        }

        return response()->json($data);
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'fullName' => 'required|string|max:255',
    //         'firstName' => 'required|string|max:255',
    //         'lastName' => 'required|string|max:255',
    //         'nik' => 'required|string|unique:data_karyawans,nik',
    //         'inisial' => 'required|string|max:10',
    //         'idJabatan' => 'required|string',
    //         'namaJabatan' => 'required|string',
    //         'empDateStart' => 'required|date',
    //         'joinDate' => 'required|date',
    //     ]);

    //     $data = DataKaryawan::create($validated + $request->except(['id']));

    //     return response()->json(['message' => 'Data created successfully', 'data' => $data], 201);
    // }

    // public function update(Request $request, $id)
    // {
    //     $data = DataKaryawan::find($id);
    //     if (!$data) {
    //         return response()->json(['message' => 'Data not found'], 404);
    //     }

    //     $data->update($request->all());

    //     return response()->json(['message' => 'Data updated successfully', 'data' => $data]);
    // }

    // public function destroy($id)
    // {
    //     $data = DataKaryawan::find($id);
    //     if (!$data) {
    //         return response()->json(['message' => 'Data not found'], 404);
    //     }

    //     $data->delete();

    //     return response()->json(['message' => 'Data deleted successfully']);
    // }
}
