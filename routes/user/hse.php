<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->middleware(['auth', 'CheckRoleUser', 'CheckMaintenance'])->group(function () {
    Route::prefix('data-peralatan')->name('data-peralatan.')->controller(App\Http\Controllers\Page\HSE\DataPeralatan\DataPeralatanController::class)->group(function () {
        Route::get('get','getData')->name('getData');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('','index')->name('index');
        Route::get('create','create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}','edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('destroy', 'destroy')->name('destroy');
    });

    Route::prefix('data-peminjaman')->name('data-peminjaman.')->controller(App\Http\Controllers\Page\HSE\PeminjamanAlat\PeminjamanAlatController::class)->group(function () {
        Route::get('get','getData')->name('getData');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('','index')->name('index');
        Route::get('create','create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}','edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('destroy', 'destroy')->name('destroy');
        Route::get('alat/{id}', 'getAlat')->name('getAlat');
    });

    Route::prefix('matrix-personil')->name('matrix-personil.')->controller(App\Http\Controllers\Page\HSE\MatrixPersonil\MatrixPersonilController::class)->group(function () {
        Route::get('get','getData')->name('getData');
        Route::get('','index')->name('index');
        Route::get('show/{id}', 'show')->name('show');
    });

    Route::prefix('input-sertifikat')->name('input-sertifikat.')->controller(App\Http\Controllers\Page\HSE\MatrixPersonil\MatrixPersonilController::class)->group(function () {
        Route::get('karyawan/{id}', 'getKaryawan')->name('getKaryawan');
        Route::get('create','create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
    });
});