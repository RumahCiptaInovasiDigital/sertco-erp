<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NotifMobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifMobileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $model = NotifMobile::query()
                    ->where([
                        'karyawan_id' => \request()->user()?->karyawan?->id,
                        'deleted_at' => null
                    ]);


        $c = request()->query('cari');
        if ($c) {
            $model->whereLikeColumns(['title', 'message'], $c);
        }

        return response()->json([
            'data' => $model->paginate(perPage: 10, columns: [
                "id", "title", DB::raw("LEFT(message,30) as message"),
                "status", "category", "created_at"
            ]),

        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(  $notifMobile )
    {
        $notif =  NotifMobile::query()->where([
            'id' => $notifMobile,
            'karyawan_id' => \request()->user()?->karyawan?->id
        ])->first();


        $notif->status = \App\Models\Enum\StatusNotif::READ;
        $notif->save();


        return response()->json([
            'data' => $notif
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotifMobile $notifMobile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $notifMobile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(  $notifMobile)
    {
        $n = NotifMobile::query()->where([
            'id' => $notifMobile,
            'karyawan_id' => \request()->user()->karyawan_id
        ])->first();
        return response()->json([
            'data' => $n->delete()
        ]);
    }

    public function markAllAsRead(Request $request){
        $data = $request->data;
        if(is_array($data)){
            $updated = NotifMobile::query()
                ->whereIn('id', $data)
                ->where('karyawan_id', $request->user()->karyawan_id)
                ->where('status', \App\Models\Enum\StatusNotif::SENT)
                ->update(['status' => \App\Models\Enum\StatusNotif::READ]);

            return response()->json([
                'message' => __("notif_mobile.mark_all_as_read.success"),
                'updated_count' => $updated
            ]);
        }else if($data == null){
            $updated = NotifMobile::query()
                ->where('karyawan_id', $request->user()->karyawan_id)
                ->where('status', \App\Models\Enum\StatusNotif::SENT)
                ->update(['status' => \App\Models\Enum\StatusNotif::READ]);

            return response()->json([
                'message' => __("notif_mobile.mark_all_as_read.success"),
                'updated_count' => $updated
            ]);
        }

        return response()->json([
            'message' => __("notif_mobile.mark_all_as_read.invalid_data")
        ], 400);
    }
}
