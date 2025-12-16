<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BeritaAcaraRequest;
use App\Models\BeritaAcaraHarian;
use App\Models\DataKaryawan;
use App\Traits\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaAcaraHarianController extends Controller
{
    use FormatResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $karyawan = DataKaryawan::query()->where('nik', \request()->user()->nik)->first();
        $model = BeritaAcaraHarian::query()
                    ->where('karyawan_id', $karyawan?->id)
                    ->orderBy('tanggal', 'desc');

        if(\request()->has('cari')){
            $model->whereLikeColumns(['uraian_kegiatan', 'lokasi', 'hasil_yang_dicapai'], \request()->input('cari'));
        }
        return $this->success($model->paginate(30, [
            'id',
            'karyawan_id',
            'tanggal',
            'waktu_mulai',
            'waktu_selesai',
            'lokasi',
        ])->toArray());
    }

    public function bapTanggal($tanggal = null){
        if($tanggal==null){
            $tanggal = date('Y-m-d');
        }

        $karyawan = DataKaryawan::query()->where('nik', \request()->user()->nik)->first();
        $data = BeritaAcaraHarian::query()
            ->where([
                'karyawan_id' => $karyawan?->id,
                'tanggal' => $tanggal,
            ])
            ->get( );

        return response()->json([
            'data' => $data,
        ] );
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
    public function store(BeritaAcaraRequest $request)
    {
        $data = $request->validated();
        $beritaAcara = BeritaAcaraHarian::query()->create($data);
        $file = $request->file('file_lampiran');
        if($file){
            $path = $file->store('berita_acara_lampiran');
            $beritaAcara->path_file_lampiran = $path;
            $beritaAcara->origin_filename = $file->getClientOriginalName();
            $beritaAcara->mimetype = $file->getClientMimeType();
            $beritaAcara->save();
        }

        return $this->success(message: 'Berita Acara Harian berhasil ditambahkan.', data: $beritaAcara);
    }

    /**
     * Display the specified resource.
     */
    public function show(  $beritaAcaraHarian )
    {
        $data = BeritaAcaraHarian::query()->find($beritaAcaraHarian);
        return $this->successOrError(data: $data );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BeritaAcaraHarian $beritaAcaraHarian)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BeritaAcaraRequest $request, $beritaAcaraHarian)
    {
        $data = $request->validated();
        $bap = BeritaAcaraHarian::query()->find($beritaAcaraHarian);
        if(!$bap){
            return $this->error('Berita Acara Harian tidak ditemukan.', statusCode: 404);
        }

        $bap->update($data);

        if(\request('hapus_file_lampiran') == 'ya'){
            if(Storage::exists($bap->path_file_lampiran)){
                Storage::delete($bap->path_file_lampiran);
            }
            $bap->path_file_lampiran = null;
            $bap->mimetype = null;
            $bap->save();
        }

        $file = $request->file('file_lampiran');
        if($file){
            if(Storage::exists($bap->path_file_lampiran)){
                Storage::delete($bap->path_file_lampiran);
            }

            $path = $file->store('berita_acara_lampiran');
            $bap->path_file_lampiran = $path;
            $bap->mimetype = $file->getClientMimeType();
            $bap->save();
        }

        return $this->success(data: $bap, message: 'Berita Acara Harian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $beritaAcaraHarian)
    {
        $bap = BeritaAcaraHarian::query()->find($beritaAcaraHarian);

        if($bap->path_file_lampiran){
            if(Storage::exists($bap->path_file_lampiran ?? "xx")){
                Storage::delete($bap->path_file_lampiran);
            }
        }

        $bap->delete();
        return $this->success(message: 'Berita Acara Harian berhasil dihapus.');
    }

    public function unduhFile( $bap){
        $b = BeritaAcaraHarian::query()->find($bap);
        if(!$b){
            return $this->error('Berita acara tidak tersedia.');
        }
        if(!Storage::exists($b->path_file_lampiran)){
            return $this->error('File tidak tersedia.');
        }
        return Storage::download($b->path_file_lampiran);
    }
}
