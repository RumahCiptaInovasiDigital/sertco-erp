<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKaryawan extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'jadwal_karyawans';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_karyawan',
        'jadwal_json',
    ];

    protected $casts = [
        'jadwal_json' => 'array',
    ];

    public function karyawan()
    {
        return $this->belongsTo(DataKaryawan::class, 'id_karyawan');
    }

    /**
     * Get jadwal_json attribute and handle double-encoded JSON
     */
    public function getJadwalJsonAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }

        // If already an array, return as is
        if (is_array($value)) {
            return $value;
        }

        // Decode JSON string
        $decoded = json_decode($value, true);

        // If decode failed, return empty array
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        // Check if it's double-encoded (string inside string)
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }

        return $decoded ?? [];
    }

    /**
     * Set jadwal_json attribute
     */
    public function setJadwalJsonAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['jadwal_json'] = json_encode($value);
        } else if (is_string($value)) {
            // If it's already a JSON string, store as is
            $this->attributes['jadwal_json'] = $value;
        } else {
            $this->attributes['jadwal_json'] = null;
        }
    }

    /**
     * Get shift for a specific day
     * @param int $day 0=Sunday, 1=Monday, ..., 6=Saturday
     * @return array|null ['shift_id' => '...', 'hari' => '...', 'lokasi' => '...']
     */
    public function getShiftForDay($day)
    {
        if (!$this->jadwal_json || !is_array($this->jadwal_json)) {
            return null;
        }
        return $this->jadwal_json[$day] ?? null;
    }

    /**
     * Set shift for a specific day
     * @param int $day 0=Sunday, 1=Monday, ..., 6=Saturday
     * @param string $shiftId
     * @param string $lokasi (optional)
     */
    public function setShiftForDay($day, $shiftId, $lokasi = null)
    {
        $jadwal = $this->jadwal_json ?? [];
        $hariNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $jadwal[$day] = [
            'shift_id' => $shiftId,
            'hari' => $hariNames[$day] ?? 'N/A',
            'lokasi' => $lokasi
        ];
        $this->jadwal_json = $jadwal;
    }

    /**
     * Get shift ID for a specific day
     * @param int $day 0=Sunday, 1=Monday, ..., 6=Saturday
     * @return string|null
     */
    public function getShiftIdForDay($day)
    {
        $daySchedule = $this->getShiftForDay($day);
        return $daySchedule['shift_id'] ?? null;
    }

    /**
     * Get location for a specific day
     * @param int $day 0=Sunday, 1=Monday, ..., 6=Saturday
     * @return string|null
     */
    public function getLokasiForDay($day)
    {
        $daySchedule = $this->getShiftForDay($day);
        return $daySchedule['lokasi'] ?? null;
    }
}
