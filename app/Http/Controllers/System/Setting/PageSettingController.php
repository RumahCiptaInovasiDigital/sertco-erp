<?php

namespace App\Http\Controllers\System\Setting;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceMode;
use App\Services\System\LogActivityService;
use Illuminate\Http\Request;

class PageSettingController extends Controller
{
    public function index()
    {
        $maintenanceMode = MaintenanceMode::where('compCode', 'SQ.01')->first();
        $routes = \Route::getRoutes()->getRoutesByName();
        // $mtMenu = MaintenanceMenuMode::all();

        return view('page.admin.settings.index', compact('maintenanceMode', 'routes'));
    }

    public function store(Request $request)
    {
        $time = $request->idle_time;
        $mode = $request->maintenance_mode;
        // $url = $request->url_hris;
        $reason = $request->reason;

        $data = MaintenanceMode::first();
        if ($data->maintenance == false) {
            if ($mode == 'true') {
                (new LogActivityService())->handle([
                    'perusahaan' => 'Application',
                    'user' => 'SYSTEM',
                    'tindakan' => 'Enabled',
                    'catatan' => 'Started Maintenance Mode by System!',
                ]);
            }
        } elseif ($data->maintenance == true) {
            if ($mode == 'false') {
                (new LogActivityService())->handle([
                    'perusahaan' => 'Application',
                    'user' => 'SYSTEM',
                    'tindakan' => 'Disabled',
                    'catatan' => 'Finished Maintenance Mode by System!',
                ]);
            }
        }

        if (MaintenanceMode::where('compCode', 'SQ.01')->first() === null) {
            MaintenanceMode::create([
                'compCode' => 'SQ.01',
                'maintenance' => $mode,
                'reason' => $reason,
                'idle_time' => $time,
            ]);
        } else {
            MaintenanceMode::where('compCode', 'SQ.01')
                ->update([
                    'maintenance' => $mode,
                    'reason' => $reason,
                    'idle_time' => $time,
                ]);
        }

        // try {
        //     DB::beginTransaction();

        //     // Hapus semua data lama (jika memang ingin clear semua dulu)
        //     MaintenanceMenuMode::truncate();

        //     // Insert ulang berdasarkan input
        //     foreach ($request->input('urls', []) as $url) {
        //         MaintenanceMenuMode::create([
        //             'url_menu' => $url,
        //         ]);
        //     }

        //     DB::commit();
        // } catch (\Throwable $th) {
        //     DB::rollBack();

        //     return response()->json([
        //         'success' => false,
        //         'message' => $th->getMessage(),
        //     ]);
        // }

        return response()->json([
            'success' => true,
            'message' => 'Updated Successfully!',
            'redirect' => route('admin.setting.index'),
        ]);
    }
}
