<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'index'])->name('login');

Route::post('/auth', [App\Http\Controllers\Auth\AuthController::class, 'store'])->name('authenticate');
Route::get('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

Route::post('/clear-notifications', [App\Http\Controllers\System\Notification\NotificationController::class, 'clear']);
Route::post('/read-notifications', [App\Http\Controllers\System\Notification\NotificationController::class, 'clear']);

require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/api.php';
