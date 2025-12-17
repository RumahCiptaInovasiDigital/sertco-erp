<?php

namespace App\Http\Middleware\API;

use \App\Models\Enum\StatusDevice;
use App\Models\UserDevice;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckDeviceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $deviceid = $request->header('device-id');
        $fcmtoken = $request->header('fcm-token');

        if(!$deviceid){
            return response()->json([
                'message' => __("device.required")
            ], 400);
        }
        $nik = auth('api')->user()?->nik;

        $userDeviceModel = UserDevice::query()->where([
            'user_credential_nik' => $nik,
            'device_id' => $deviceid,
        ])->first();

        if (!$userDeviceModel) {
            return response()->json([
                'message' => __("device.required"),
                'device_id' => $deviceid,
                'nik' => $nik
            ], 403);
        }

        if($userDeviceModel->status == StatusDevice::ACTIVE){
            $coordinate = base64_decode( \request()->header('coordinate') ?? "[]" );
            $userDeviceModel->coordinate = json_decode($coordinate) ?? [];

            Cache::remember("fcm_token_{$nik}_{$deviceid}", 60 * 3, function() use ($fcmtoken, $userDeviceModel){
                $userDeviceModel->fcm_token = $fcmtoken;
                $userDeviceModel->ip_address = clientIP();
                $userDeviceModel->save();
                return $fcmtoken;
            });
            return $next($request);
        }
        return response()->json([
            'message' => $userDeviceModel->status == StatusDevice::BLOCKED ? __("device.blocked") :  __("device.inactive"),
            'device_id' => $deviceid,
            'reason' => $userDeviceModel->reason_blocked,
            'news' => $userDeviceModel->news,
            'status' => $userDeviceModel->status,
            'data' => $userDeviceModel
        ], 403);
    }
}
