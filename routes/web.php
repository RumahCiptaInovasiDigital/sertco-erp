<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login');

Route::post('/auth', [App\Http\Controllers\Auth\AuthController::class, 'store'])->name('authenticate');
Route::get('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

Route::post('/clear-notifications', [App\Http\Controllers\System\Notification\NotificationController::class, 'clear']);
Route::post('/read-notifications', [App\Http\Controllers\System\Notification\NotificationController::class, 'clear']);

Route::prefix('v1')->name('v1.')->middleware(['auth'])->group(function () {
    Route::prefix('audit')->name('auditTrail.')->middleware(['auth'])->group(function () {
        Route::get('getData', [App\Http\Controllers\System\AuditTrail\AuditController::class, 'getData'])->name('getData');
        Route::get('', [App\Http\Controllers\System\AuditTrail\AuditController::class, 'index'])->name('index');
        Route::post('pdf', [App\Http\Controllers\System\AuditTrail\AuditController::class, 'generatePdf'])->name('generatePdf');
    });

    Route::prefix('contact')->name('contact.')->group(function () {
        Route::get('', [App\Http\Controllers\System\ContactUs\ContactUsController::class, 'index'])->name('index');
    });
});

require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/api.php';
