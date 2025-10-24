<?php

use Illuminate\Support\Facades\Route;

// Admin Zone
Route::prefix('admin')->name('admin.')->middleware(['auth', 'CheckRoleUser'])->group(function () {
    // Setting
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Setting\PageSettingController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\System\Setting\PageSettingController::class, 'store'])->name('store');
    });
});
