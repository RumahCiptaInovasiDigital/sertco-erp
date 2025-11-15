<?php

use Illuminate\Support\Facades\Route;

Route::get('v1', [App\Http\Controllers\System\DashboardController::class, 'index'])->middleware(['auth', 'CheckMaintenance'])->name('v1.dashboard');

Route::prefix('v1')->name('v1.')->middleware(['auth', 'CheckMaintenance', 'CheckRoleUser'])->group(function () {
    Route::prefix('service')->name('service.')->group(function () {
        Route::prefix('kategori')->name('kategori.')->group(function () {
            Route::get('get', [App\Http\Controllers\Page\Marketing\Service\Kategori\ServiceKategoriController::class, 'getData'])->name('getData');
            Route::get('', [App\Http\Controllers\Page\Marketing\Service\Kategori\ServiceKategoriController::class, 'index'])->name('index');
            Route::get('create', [App\Http\Controllers\Page\Marketing\Service\Kategori\ServiceKategoriController::class, 'create'])->name('create');
            Route::post('store', [App\Http\Controllers\Page\Marketing\Service\Kategori\ServiceKategoriController::class, 'store'])->name('store');
            Route::get('edit/{id}', [App\Http\Controllers\Page\Marketing\Service\Kategori\ServiceKategoriController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [App\Http\Controllers\Page\Marketing\Service\Kategori\ServiceKategoriController::class, 'update'])->name('update');
            Route::post('destroy', [App\Http\Controllers\Page\Marketing\Service\Kategori\ServiceKategoriController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('type')->name('type.')->group(function () {
            Route::get('get', [App\Http\Controllers\Page\Marketing\Service\Type\ServiceTypeController::class, 'getData'])->name('getData');
            Route::get('', [App\Http\Controllers\Page\Marketing\Service\Type\ServiceTypeController::class, 'index'])->name('index');
            Route::get('create', [App\Http\Controllers\Page\Marketing\Service\Type\ServiceTypeController::class, 'create'])->name('create');
            Route::post('store', [App\Http\Controllers\Page\Marketing\Service\Type\ServiceTypeController::class, 'store'])->name('store');
            Route::get('edit/{id}', [App\Http\Controllers\Page\Marketing\Service\Type\ServiceTypeController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [App\Http\Controllers\Page\Marketing\Service\Type\ServiceTypeController::class, 'update'])->name('update');
            Route::post('destroy', [App\Http\Controllers\Page\Marketing\Service\Type\ServiceTypeController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('barang')->name('barang.')->group(function () {
        Route::prefix('master')->name('master.')->controller(App\Http\Controllers\Page\MasterData\Barang\BarangMasterController::class)->group(function () {
            Route::get('get', 'getData')->name('getData');
            Route::get('', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('destroy', 'destroy')->name('destroy');
        });
        Route::prefix('kategori')->name('kategori.')->controller(App\Http\Controllers\Page\MasterData\Barang\KategoriBarangController::class)->group(function () {
            Route::get('get', 'getData')->name('getData');
            Route::get('', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('destroy', 'destroy')->name('destroy');
        });
        Route::prefix('satuan')->name('satuan.')->controller(App\Http\Controllers\Page\MasterData\Barang\SatuanBarangController::class)->group(function () {
            Route::get('get', 'getData')->name('getData');
            Route::get('', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('destroy', 'destroy')->name('destroy');
        });
    });

    Route::prefix('departemen')->name('departemen.')->group(function () {
        Route::get('get', [App\Http\Controllers\Page\HRGA_IT\Departemen\DepartemenController::class, 'getData'])->name('getData');
        Route::get('', [App\Http\Controllers\Page\HRGA_IT\Departemen\DepartemenController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Page\HRGA_IT\Departemen\DepartemenController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\Page\HRGA_IT\Departemen\DepartemenController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\Page\HRGA_IT\Departemen\DepartemenController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\Page\HRGA_IT\Departemen\DepartemenController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\Page\HRGA_IT\Departemen\DepartemenController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('role')->name('role.')->group(function () {
        Route::get('get', [App\Http\Controllers\Page\HRGA_IT\Role\RoleController::class, 'getData'])->name('getData');
        Route::get('', [App\Http\Controllers\Page\HRGA_IT\Role\RoleController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\Page\HRGA_IT\Role\RoleController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\Page\HRGA_IT\Role\RoleController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\Page\HRGA_IT\Role\RoleController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\Page\HRGA_IT\Role\RoleController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\Page\HRGA_IT\Role\RoleController::class, 'destroy'])->name('destroy');

        Route::prefix('assign')->name('assign.')->group(function () {
            Route::get('{role}/{id}/get', [App\Http\Controllers\Page\HRGA_IT\Role\Assign\AssignRoleController::class, 'getData'])->name('getData');
            Route::get('{role}/{id}', [App\Http\Controllers\Page\HRGA_IT\Role\Assign\AssignRoleController::class, 'index'])->name('index');
            Route::get('{role}/{id}/getEmployee', [App\Http\Controllers\Page\HRGA_IT\Role\Assign\AssignRoleController::class, 'getEmployee'])->name('getEmployee');
            Route::post('{role}/{id}/store', [App\Http\Controllers\Page\HRGA_IT\Role\Assign\AssignRoleController::class, 'store'])->name('store');
            Route::delete('{id}/delete', [App\Http\Controllers\Page\HRGA_IT\Role\Assign\AssignRoleController::class, 'destroy'])->name('destroy');
        });
    });
});

require __DIR__.'/user/hrga.php';
require __DIR__.'/user/marketing.php';
require __DIR__.'/user/hse.php';
