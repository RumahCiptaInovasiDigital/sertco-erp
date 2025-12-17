<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Presensi extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'presensi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'data_karyawan_id',
        'id',
        'user_id',
        'tanggal',
        'type_presensi',
        'jam_masuk',
        'ip_masuk',
        'device_masuk',
        'koordinat_masuk',

        'jam_harus_masuk_awal',
        'jam_harus_masuk_akhir',
        'jam_pulang',
        'ip_pulang',
        'device_pulang',
        'koordinat_pulang',
        'shift_kerja_id',
        'jam_harus_pulang_awal',
        'jam_harus_pulang_akhir',
        'origin_branchoffice_masuk_id',
        'branchoffice_masuk_id',
        'origin_branchoffice_pulang_id',
        'branchoffice_pulang_id',
        'status',
        'total_jam_kerja',
        'keterangan',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'tanggal' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'string',
    ];
    const STATUS = [
        'good' => 'Good',
        'late' => 'Late',
        'uncompleted' => 'Uncompleted',
        'leave' => 'Leave',
        'sick' => 'Sick',
        'absent' => 'Absent',
        'overtime' => 'Overtime',
        'onduty' => 'Onduty',
    ];

    const TYPE_PRESENSI = [
        'WFO' => 'Work From Office',
        'WFA' => 'Work From Anywhere',
    ];

    public function originOfficeMasuk()
    {
        return $this->belongsTo(BranchOffice::class,  'origin_branchoffice_masuk_id', 'id');
    }

    public function originOfficePulang(){
        return $this->belongsTo(BranchOffice::class,   'origin_branchoffice_pulang_id', 'id');
    }

    public function officeMasuk()
    {
        return $this->belongsTo(BranchOffice::class,  'branchoffice_masuk_id', 'id');
    }

    public function officePulang()
    {
        return $this->belongsTo(BranchOffice::class,   'branchoffice_pulang_id', 'id');
    }

    public function karyawan(){
        return $this->belongsTo(DataKaryawan::class, 'data_karyawan_id', 'id');
    }

    public function isDiluarJamMasuk(){
        $jamMasuk = strtotime($this->tanggal . ' ' . $this->jam_masuk ?? date('H:i:s'));
        $jamHarusMasukAwal = strtotime($this->tanggal . ' ' . $this->jam_harus_masuk_awal);
        $jamHarusMasukAkhir = strtotime($this->tanggal . ' ' . $this->jam_harus_masuk_akhir);

        return $jamMasuk < $jamHarusMasukAwal || $jamMasuk > $jamHarusMasukAkhir;
    }

    public function cekBAPCount($karyawan){
        $count = BeritaAcaraHarian::query()->where([
            'karyawan_id' => $karyawan->id,
            'tanggal' => Carbon::now()->format('Y-m-d'),
        ])->count();
        return $count;
    }

    public function isDiluarJamPulang(){
        $jamMasuk = Carbon::parse($this->tanggal . ' ' . ($this->jam_masuk ?? now()->format('H:i:s')));
        $jamHarusMasukAwal = Carbon::parse($this->tanggal . ' ' . $this->jam_harus_masuk_awal);
        return $jamMasuk->lt($jamHarusMasukAwal);
    }

    public function getTotalJamKerja(){
        $jamMasuk = strtotime($this->tanggal . ' ' . $this->jam_masuk ?? date('H:i:s'));
        $jamPulang = strtotime($this->tanggal . ' ' . $this->jam_pulang ?? date('H:i:s'));

        return ($jamPulang - $jamMasuk) / 60 / 60;
    }

    public function isOverTime(){
        $jamPulang = strtotime($this->tanggal . ' ' . $this->jam_pulang ?? date('H:i:s'));
        $jamHarusPulangAkhir = strtotime($this->tanggal . ' ' . $this->jam_harus_pulang_akhir);

        return $jamPulang > $jamHarusPulangAkhir;
    }

    public function getStatusFinal(){
        if($this->jam_masuk == null){
            return 'absent';
        }elseif($this->isDiluarJamMasuk() || $this->isDiluarJamPulang()){
            return 'late';
        }elseif($this->isOverTime()){
            return 'overtime';
        }else{
            return 'good';
        }
    }
}
