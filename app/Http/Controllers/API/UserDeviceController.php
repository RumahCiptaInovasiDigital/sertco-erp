<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserDeviceRequest;
use App\Models\UserDevice;
use App\Traits\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserDeviceController extends Controller
{
    use FormatResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nik = request()->user()->nik;
        $deviceid = \request()->header('Device-Id');
        $device = UserDevice::query()->where([
            'user_credential_nik' => $nik
        ])->get([ 'id', 'device_name', 'user_credential_nik', 'device_id', 'status',
                'activate_at', 'blocked_at', 'reason_blocked', 'news', 'created_at', 'updated_at', 'register_new'
            ]
        );

        return $this->successOrError(
            data: $device->isEmpty() ? null : $device
        );
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
    public function store(UserDeviceRequest $request)
    {
        $userdevices = UserDevice::query()->where('user_credential_nik', request()->user()->nik)->first();
        if(!$userdevices){
            $v = $request->validated();
            $v->id = Str::uuid()->toString();
            $v->register_new = $v;
            $r = UserDevice::query()->create($v);
            return $this->success(
                data: $r
            );
        }else{
            return $this->update($request, $userdevices);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserDevice $userDevice)
    {
        return $this->successOrError(
            data: $userDevice
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserDevice $userDevice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserDeviceRequest $request, UserDevice $userDevice)
    {
        $v = $request->validated();

        $userDevice->update([
            'register_new' => $v
        ]);
        return $this->success(
            data: $userDevice
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserDevice $userDevice)
    {
        $userDevice->delete();
        return $this->success(
            message: 'User device deleted successfully.'
        );
    }
}
