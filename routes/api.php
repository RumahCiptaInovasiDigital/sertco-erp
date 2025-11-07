<?php

use App\Http\Controllers\API\ApiDataKaryawan;

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
