<?php

use Illuminate\Support\Facades\Route;

// Admin Zone
Route::prefix('admin')->name('admin.')->middleware(['auth', 'CheckRoleUser'])->group(function () {
    // permission
    Route::prefix('permission')->name('permission.')->group(function () {
        Route::get('get', [App\Http\Controllers\System\Permission\PermissionController::class, 'getData'])->name('getData');
        Route::get('', [App\Http\Controllers\System\Permission\PermissionController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\System\Permission\PermissionController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\System\Permission\PermissionController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\System\Permission\PermissionController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\System\Permission\PermissionController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\Permission\PermissionController::class, 'destroy'])->name('destroy');
        Route::post('refresh', [App\Http\Controllers\System\Permission\PermissionController::class, 'refresh'])->name('refresh');
    });
    // notification
    Route::prefix('notification')->name('notification.')->group(function () {
        Route::get('get', [App\Http\Controllers\System\Notification\NotificationController::class, 'getData'])->name('getData');
        Route::get('getEmployee/{id}', [App\Http\Controllers\System\Notification\NotificationController::class, 'getEmployee'])->name('getEmployee');
        Route::get('search', [App\Http\Controllers\System\Notification\NotificationController::class, 'searchEmployee'])->name('searchEmployee');
        Route::get('', [App\Http\Controllers\System\Notification\NotificationController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\System\Notification\NotificationController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\System\Notification\NotificationController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\System\Notification\NotificationController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\System\Notification\NotificationController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\Notification\NotificationController::class, 'destroy'])->name('destroy');
    });

    // Setting
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Setting\PageSettingController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\System\Setting\PageSettingController::class, 'store'])->name('store');
    });
    
    //Feedback
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Feedback\FeedbackAdminController::class, 'index'])->name('index');
        Route::post('{feedback}/status', [App\Http\Controllers\System\Feedback\FeedbackAdminController::class, 'updateStatus'])->name('updateStatus');
    });
});
