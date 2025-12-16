<?php

use App\Http\Controllers\API\ApiDataKaryawan;

use \App\Http\Middleware\API\CekAPIKeyMiddleware;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\LoginController;
use \App\Http\Controllers\API\PresensiController;
use \App\Http\Controllers\API\ProfileController;
use \App\Http\Controllers\API\CalendarEventController;
use \App\Http\Controllers\API\InformationController;
use \App\Http\Controllers\API\BeritaAcaraHarianController;
use \App\Http\Controllers\API\UserDeviceController;
use \App\Http\Middleware\API\CheckDeviceMiddleware;
use \App\Http\Middleware\API\AuthMobileMIddlewareMiddleware;
use \App\Http\Controllers\API\NotifMobileController;

Route::middleware(CekAPIKeyMiddleware::class)->group(function () {

    Route::resource("login", LoginController::class);
    Route::middleware( AuthMobileMIddlewareMiddleware::class)->group(function () {
        Route::resource('profile', ProfileController::class);
        Route::post('photo_profile', [ProfileController::class, 'updateFoto']);
        Route::get("foto/{nik}.jpg", [ProfileController::class, 'getFoto']);

        Route::prefix('presensi')->middleware( CheckDeviceMiddleware::class)->group(function () {
            Route::post('check-in', [PresensiController::class, "store"]);
            Route::get('today', [PresensiController::class, "today"]);
            Route::get("list-periode", [PresensiController::class, "listPeriode"]);
            Route::get('history/{periode?}', [PresensiController::class, "history"]);
            Route::get('tomorrow', [PresensiController::class, "tomorrow"]);
        });
        Route::resource('calendar', CalendarEventController::class);
        Route::resource('information',  InformationController::class);
        Route::resource('user-device',  UserDeviceController::class);

        Route::get('bap/unduh/{bap}',  [BeritaAcaraHarianController::class, 'unduhFile']);
        Route::get("bap-today", [BeritaAcaraHarianController::class, "bapTanggal"]);
        Route::get("bap-tanggal/{tanggal}", [BeritaAcaraHarianController::class, "bapTanggal"]);
        Route::resource('bap',  BeritaAcaraHarianController::class);
        Route::resource('notif-mobile', NotifMobileController::class);
        Route::put('notif-mobile/mark-all-as-read', [NotifMobileController::class, 'markAllAsRead']);


    });
});



// Route::prefix('getEmployee')->group(function () {
//     Route::get('/', [ApiDataKaryawan::class, 'index']);               // get all
//     Route::get('/{id}', [ApiDataKaryawan::class, 'show']);            // get by id
//     Route::get('/nik/{nik}', [ApiDataKaryawan::class, 'getByNik']);   // get by NIK
//     Route::get('/name/{name}', [ApiDataKaryawan::class, 'getByName']); // get by name
//     Route::post('/', [ApiDataKaryawan::class, 'store']);              // create
//     Route::put('/{id}', [ApiDataKaryawan::class, 'update']);          // update
//     Route::delete('/{id}', [ApiDataKaryawan::class, 'destroy']);      // delete
// });
Route::prefix('api')->middleware('check.api.key')->group(function () {
    Route::get('/getAllKaryawan', [ApiDataKaryawan::class, 'index']);               // get all
    Route::get('/getKaryawanById/{id}', [ApiDataKaryawan::class, 'show']);            // get by id
    Route::get('/getKaryawanByNik/{nik}', [ApiDataKaryawan::class, 'getByNik']);   // get by NIK
    Route::get('/getKaryawanByName/{name}', [ApiDataKaryawan::class, 'getByName']); // get by name
});
// Route::post('createKaryawan', [ApiDataKaryawan::class, 'store']);              // create
// Route::put('updateKaryawan/{id}', [ApiDataKaryawan::class, 'update']);          // update
// Route::delete('deleteKaryawan/{id}', [ApiDataKaryawan::class, 'destroy']);      // delete
