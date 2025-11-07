<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->middleware(['auth', 'CheckRoleUser', 'CheckMaintenance'])->group(function () {
    Route::prefix('register-project')->name('register-project.')->group(function () {
        Route::get('', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\Service\ServicePESController::class, 'index'])->name('index');
        Route::get('get', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\Service\ServicePESController::class, 'getData'])->name('getData');
        Route::post('store', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\Service\ServicePESController::class, 'store'])->name('store');
        Route::get('edit/{project_no}', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\Service\ServicePESController::class, 'edit'])->name('edit');
        Route::post('update/{project_no}', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\Service\ServicePESController::class, 'update'])->name('update');
    });

    Route::prefix('pes')->name('pes.')->group(function () {
        Route::get('', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\ProjectExecutionSheetController::class, 'index'])->name('index');
        Route::get('get/{action}', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\ProjectExecutionSheetController::class, 'getData'])->name('getData');
        Route::get('show/{id}', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\ProjectExecutionSheetController::class, 'show'])->name('show');
        Route::get('create', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\ProjectExecutionSheetController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\ProjectExecutionSheetController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\ProjectExecutionSheetController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\ProjectExecutionSheetController::class, 'update'])->name('update');
        Route::post('delete', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\ProjectExecutionSheetController::class, 'destroy'])->name('destroy');

        Route::prefix('service')->name('service.')->group(function () {
            Route::get('{project_no}', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\Service\ServicePESController::class, 'index'])->name('index');
            Route::post('store', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\Service\ServicePESController::class, 'store'])->name('store');
            Route::get('edit/{project_no}', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\Service\ServicePESController::class, 'edit'])->name('edit');
            Route::post('update/{project_no}', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\Service\ServicePESController::class, 'update'])->name('update');
        });
    });
});
