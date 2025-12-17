<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    use HasFactory;
    protected $table = 'branch_offices';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'fax',
        'email',
        'coordinates',
        'ip_registered',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'ip_registered' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function isIPValid($ipAddress)
    {
        if (!isset($this->ip_registered) || !is_array($this->ip_registered)) {
            return false;
        }

        $ip = explode('.', $ipAddress);
        $ipcompiled = $ip[0] . '.' . $ip[1] . '.' . $ip[2];

        return in_array($ipcompiled, $this->ip_registered);
    }

    public function isInRadiusCoordinate(float $latitude, float $longitude, ?float &$distance = null): bool
    {
        if (!isset($this->coordinates['lat']) || !isset($this->coordinates['lng'])) {
            return false;
        }

        $radiusInMeters = $this->coordinates['radius'] ?? 100; // default 100 meters
        $earthRadius    = 6371000; // meters

        $lat1 = deg2rad($latitude);
        $lon1 = deg2rad($longitude);
        $lat2 = deg2rad(floatval($this->coordinates['lat']));
        $lon2 = deg2rad(floatval($this->coordinates['lng']));

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos($lat1) * cos($lat2)
            * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // assign ke parameter reference
        $distance = $earthRadius * $c;

        return $distance <= $radiusInMeters;
    }

}
