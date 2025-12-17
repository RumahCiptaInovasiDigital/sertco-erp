<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BeritaAcaraHarian extends Model
{
    use HasUuids;
    protected $table = "berita_acara_harians";
    protected $primaryKey = "id";
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'uraian_kegiatan',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'hasil_yang_dicapai',
        'path_file_lampiran',
        'mimetype',
    ];


    protected $hidden = ['path_file_lampiran'];

    protected $appends = ['url_lampiran'];

    public function newEloquentBuilder($query): \App\Database\CustomBuilder
    {
        return new \App\Database\CustomBuilder($query);
    }

    public function getUrlLampiranAttribute(){
        if(Storage::exists($this->path_file_lampiran ?? 'xx')){
            return url('api/bap/unduh/'.$this->id);
        }
        return null;
    }

    public function karyawan(){
        return $this->belongsTo(DataKaryawan::class, 'karyawan_id', 'id');
    }

}
