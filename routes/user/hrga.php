<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->middleware(['auth', 'CheckRoleUser', 'CheckMaintenance'])->group(function () {
    Route::prefix('data-karyawan')->name('data-karyawan.')->group(function () {
        Route::get('get', [App\Http\Controllers\Page\Service\Type\ServiceTypeController::class, 'getData'])->name('getData');
        Route::get('', [App\Http\Controllers\Page\Service\Type\ServiceTypeController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Page\Service\Type\ServiceTypeController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\Page\Service\Type\ServiceTypeController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\Page\Service\Type\ServiceTypeController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\Page\Service\Type\ServiceTypeController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\Page\Service\Type\ServiceTypeController::class, 'destroy'])->name('destroy');
    });
});
