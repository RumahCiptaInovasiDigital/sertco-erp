<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->middleware(['auth', 'CheckMaintenance', 'CheckRoleUser'])->group(function () {
    Route::prefix('project-register')->name('project-register.')->controller(App\Http\Controllers\Page\Marketing\ProjectRegister\ProjectRegisterController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('get', 'getData')->name('getData');
        Route::get('create', 'create')->name('create');
        Route::post('store/{id_karyawan}/{id_sserti}', 'store')->name('store');
        Route::get('edit/{project_no}', 'edit')->name('edit');
        Route::post('update/{project_no}', 'update')->name('update');
    });

    Route::prefix('pes')->name('pes.')->group(function () {
        Route::get('/', [App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\ProjectExecutionSheetController::class, 'index'])->name('index');
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

    Route::prefix('project-draft')->name('pes.draft.')->controller(App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\DraftController::class)
    ->group(function () {
        Route::get('get', 'getDraft')->name('getDraft');
        Route::get('', 'index')->name('index');
    });

    // Route::prefix('review')->name('review.')->group(function () {
    //     Route::prefix('pes')->name('pes.')->controller(App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\ReviewController::class)->group(function () {
    //         Route::get('show/{id}', 'show')->name('show');
    //         Route::post('store', 'store')->name('store');
    //     });
    // });

    Route::prefix('approval')->name('approval.')->group(function () {
        Route::prefix('pes')->name('pes.')->controller(App\Http\Controllers\Page\Approval\ApprovalProjectExecutionSheetController::class)->group(function () {
            Route::get('get', 'getData')->name('getData');
            Route::get('', 'index')->name('index');
            Route::get('show/{id}', 'show')->name('show');
            Route::post('response', 'approveOrReject')->name('ApproveOrReject');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
        });
    });
});

Route::prefix('v1/pes/show/')->controller(App\Http\Controllers\Page\Marketing\ProjectExecutionSheet\CommentController::class)->group(function () {
    Route::get('comment/{project_no}', 'load');
    Route::post('comment', 'store');
    Route::post('comment/{id}/like', 'toggleLike');
});


// Chunk upload untuk upload lampiran pes big size
Route::post('/chunk-upload', [App\Http\Controllers\System\FileUploadPES\ChunkUploadController::class, 'uploadChunk']);
Route::post('/chunk-complete', [App\Http\Controllers\System\FileUploadPES\ChunkUploadController::class, 'completeChunk']);
