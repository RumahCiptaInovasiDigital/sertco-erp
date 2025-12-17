<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresensiRequest;
use App\Models\BranchOffice;
use App\Models\Presensi;
use App\Traits\FormatResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;


class PresensiController extends Controller
{
    use FormatResponse;

    public function listPeriode(Request $request)
    {
        $karyawan = $request->user()->karyawan;

        $presensis = Presensi::query()->where([
            "data_karyawan_id" => $karyawan->id,
        ])->selectRaw('YEAR(tanggal) as year, MONTH(tanggal) as month')->distinct()->get();

        return response()->json([
            "data" => $presensis->map(function($item){
                return $item->year . "-" . str_pad($item->month, 2, "0", STR_PAD_LEFT);
            }),
        ]);
    }

    public function today(Request $request)
    {
        $karyawan = $request->user()->karyawan;

        $presensi = Presensi::query()
            ->with([
                'originOfficeMasuk' => function($sub){
                return $sub->select(["id", "name", "city", "address", "coordinates", "ip_registered"]);
            }, 'originOfficePulang'=>function($sub){
                return $sub->select(["id","name", "city", "address", "coordinates", "ip_registered"]);
            }, 'officeMasuk' => function($sub){
                return $sub->select(["id","name", "city", "address"]);
            }, 'officePulang'=>function($sub){
                return $sub->select(["id","name", "city", "address"]);
            }
                ])
            ->where([
                "data_karyawan_id" => $karyawan->id,
                "tanggal" => date("Y-m-d"),
        ])->first();

        if(empty($presensi)){
            return response()->json([
                "message" => "Presensi belum diatur",
            ], 400);
        }

        return response()->json([
            "data" => $presensi,
            "time" => Carbon::now()->format("Y-m-d H:i:s"),
            "ip_client" => clientIP(),
        ]);
    }

    public function tomorrow(Request $request)
    {
        $karyawan = $request->user()->karyawan;

        $presensi = Presensi::query()
            ->with([
                'originOfficeMasuk' => function($sub){
                return $sub->select(["id", "name", "city", "address", "coordinates", "ip_registered"]);
            }, 'originOfficePulang'=>function($sub){
                return $sub->select(["id","name", "city", "address", "coordinates", "ip_registered"]);
            }, 'officeMasuk' => function($sub){
                return $sub->select(["id","name", "city", "address"]);
            }, 'officePulang'=>function($sub){
                return $sub->select(["id","name", "city", "address"]);
            }
                ])
            ->where('data_karyawan_id', $karyawan->id)
            ->whereBetween('tanggal', [
                Carbon::tomorrow()->format('Y-m-d'),
                Carbon::tomorrow()->addDays(6)->format('Y-m-d'),
            ])->get();


        if(empty($presensi)){
            return response()->json([
                "message" => "Presensi belum diatur",
            ], 400);
        }

        return response()->json([
            "data" => $presensi,
            "time" => Carbon::now()->format("Y-m-d H:i:s"),
            "ip_client" => clientIP(),
        ]);
    }

    public function history(Request $request, $periode = null)
    {
        $karyawan = $request->user()->karyawan;

        $dt = Carbon::createFromFormat('Y-m', $periode);

        $awal = $dt->copy()->startOfMonth();
        $akhir = $dt->copy()->endOfMonth();
        $skr = Carbon::today();

        if($awal->greaterThanOrEqualTo($skr ) ){
            $awal = $skr->copy();
        }
        if($akhir->greaterThanOrEqualTo($skr ) ){
            $akhir = $skr->copy();
        }

        $presensis = Presensi::query()->where([
            "data_karyawan_id" => $karyawan->id,
        ])  ->with([
            'originOfficeMasuk' => function($sub){
                return $sub->select(["id", "name", "city", "coordinates", "ip_registered"]);
            }, 'originOfficePulang'=>function($sub){
                return $sub->select(["id","name", "city", "coordinates", "ip_registered"]);
            }, 'officeMasuk' => function($sub){
                return $sub->select(["id","name", "city"]);
            }, 'officePulang'=>function($sub){
                return $sub->select(["id","name", "city"]);
            }

        ])->whereBetween('tanggal', [
           $awal->format('Y-m-d'),
           $akhir->format('Y-m-d'),
        ])->get();

        return response()->json([
            "data" => $presensis,
            'awal' => $awal->format('Y-m-d'),
            'akhir' => $akhir->format('Y-m-d'),
        ]);
    }

    public function store(PresensiRequest $request)
    {
        $karyawan = $request->user()->karyawan;
        $force = $request->input('force', false);
        $ismocked = $request->header('Is-Mocked', 1);
        $coordinateClient = json_decode(  base64_decode( request()->header('coordinate') ) );
        $lat = floatval($coordinateClient?->lat ?? 0);
        $lng = floatval($coordinateClient?->lng ?? 0);

        if($ismocked == 1){
            return response()->json([
                "message" => __("gps.invalid"),
            ], 400);
        }

        $presensi = Presensi::query()->where([
            "data_karyawan_id" => $karyawan->id,
            "tanggal" => date("Y-m-d"),
        ])->first();

        if(empty($presensi)){
            return response()->json([
                "message" => __('attendance.unset'),
            ], 400);
        }

        $ismasuk = $presensi->jam_masuk == null;
        if(!$ismasuk && $presensi->jam_pulang != null){
            return $this->error(message: __('attendance.finish'));
        }


        if($presensi->jam_masuk == null && $presensi->isDiluarJamMasuk() && !$force) {
            return $this->error(
                message: __('attendance.warn.checkin'),
                data: [
                    "jam_harus_masuk_awal" => $presensi->jam_harus_masuk_awal,
                    "jam_harus_masuk_akhir" => $presensi->jam_harus_masuk_akhir,
                    "current_time" => Carbon::now()->format('H:i'),
                    "force_available" => true,
                ]
            );
        }elseif( $presensi->jam_masuk != null &&  ($bap = $presensi->cekBAPCount($karyawan) )< 2){
            return $this->error(
                message: __('attendance.warn.bap_required'),
                data: [
                    'bap_required' => 2,
                    'bap_submitted' => $bap,
                    'karyawan_id' => $karyawan->id,
                ],
            );
        }else if($presensi->jam_masuk != null && $presensi->jam_pulang == null && $presensi->isDiluarJamPulang() && !$force){
            return $this->error(
                message: __('attendance.warn.checkout'),
                data: [
                    "jam_harus_pulang_awal" => $presensi->jam_harus_pulang_awal,
                    "jam_harus_pulang_akhir" => $presensi->jam_harus_pulang_akhir,
                    "current_time" => Carbon::now()->format('H:i'),
                    "force_available" => true,
                ]
            );
        }


        if($presensi->type_presensi == "WFO"){
            $kantor = BranchOffice::query()->find($presensi->origin_branchoffice_masuk_id, ['coordinates', 'ip_registered']);
            if(empty($kantor)){
                return $this->error(
                    message: __('attendance.office.unset'),
                );
            }

            $dist = null;
            if( ! $kantor->isIPValid(clientIP()) ){
                if( ! $kantor->isInRadiusCoordinate( $lat, $lng, $dist )) {
                    return $this->error(
                        message: __('attendance.outside_office'),
                    );
                }
            }

            $presensi->branch_office_masuk_id = $kantor->id;
        }

        $jenis = $ismasuk ? 'masuk' : 'pulang';
        $presensi->{"jam_$jenis"} = date("H:i:s");
        $presensi->{"ip_$jenis"} = clientIP();
        $presensi->{"device_$jenis"} = $request->header('Device-Id');
        $presensi->{"koordinat_$jenis"} = $request->input('koordinat_masuk');
        $presensi->status = 'uncompleted';
        if($jenis == 'pulang'){
            $presensi->status = $presensi->getStatusFinal();
        }

        if($presensi->save()){
            return response()->json([
                "data" => $presensi,
                "message" => $jenis == 'masuk' ? __('attendance.success.checkin') : __('attendance.success.checkout'),
                "ip_client" => clientIP(),
                "time" => Carbon::now()->format("Y-m-d H:i:s"),
            ]);
        }else{
            return $this->error(message: __('attendance.fail'));
        }


    }
}
