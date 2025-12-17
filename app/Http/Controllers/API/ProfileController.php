<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\DataKaryawan;
use App\Models\UserCredential;
use App\Traits\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use FormatResponse;

    public function index()
    {
        return $this->successOrError(
            data: DataKaryawan::query()
                ->where("nik", request()->user()->nik )
                ->first()
        );
    }

    public function update(ProfileRequest $request, $profile)
    {
        $data = $request->validated();
        $nik = $request->user()->nik;
        $karyawan = UserCredential::query()
            ->where("nik", $nik)
            ->first();

        $karyawan->pass = \Hash::make($data['new_password']);
        $karyawan->save();

        return response()->json([
            'message' => __('profile.password_updated'),
            'data' => $data
        ]);
    }

    public function updateFoto(){
        $nik = request()->user()->nik;
        $file = request()->file('foto_profil');
        if($file){
            Storage::makeDirectory('foto_profil');
            $result = Storage::putFileAs('foto_profil', $file, "$nik.jpg");
             return response()->json([
                'message' => __('profile.photo_updated'),
                'data' => url("api/foto/$nik.jpg"),
                'hasil' => $result
            ]);

        }
        return response()->json([
            'message' => __('profile.photo_update_failed'),
            'data' => null
        ], 400);
    }

    public function getFoto($nik){
        $fotostraoge = "foto_profil/$nik.jpg";

        if(Storage::exists($fotostraoge)   ){
            return response()->download(Storage::path($fotostraoge));
        }
        return response()->json([
            'message' => __('profile.photo_not_found'),
            'path' => $fotostraoge
        ], 404);
    }
}
