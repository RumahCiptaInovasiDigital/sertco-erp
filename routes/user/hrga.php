<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->middleware(['auth', 'CheckMaintenance', 'CheckRoleUser'])->group(function () {
    Route::prefix('data-karyawan')->name('data-karyawan.')->group(function () {
        Route::get('', [App\Http\Controllers\Page\HRGA_IT\DataKaryawan\DataKaryawanController::class, 'index'])->name('index');
        Route::get('get', [App\Http\Controllers\Page\HRGA_IT\DataKaryawan\DataKaryawanController::class, 'getData'])->name('getData');
        Route::get('show/{id}', [App\Http\Controllers\Page\HRGA_IT\DataKaryawan\DataKaryawanController::class, 'show'])->name('show');
        Route::get('create', [App\Http\Controllers\Page\HRGA_IT\DataKaryawan\DataKaryawanController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\Page\HRGA_IT\DataKaryawan\DataKaryawanController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\Page\HRGA_IT\DataKaryawan\DataKaryawanController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\Page\HRGA_IT\DataKaryawan\DataKaryawanController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\Page\HRGA_IT\DataKaryawan\DataKaryawanController::class, 'destroy'])->name('destroy');
    });
});
