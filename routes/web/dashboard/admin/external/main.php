<?php

use App\Http\Controllers\Dashboard\Admin\External\Rank;
use Illuminate\Support\Facades\Route;

Route::middleware('role:coach')->namespace('External')->prefix('external')->as('external.')->group(function () {
    Route::namespace('Rank')->prefix('rank')->as('rank.')->group(function () {
        Route::get('/', [Rank\IndexController::class])->name('index');
        // Route::get('create', [Rank\CreateController::class])->name('create');
        // Route::post('/', [Rank\StoreController::class])->name('store');
        // Route::get('{rank}/edit', [Rank\EditController::class])->name('edit');
        // Route::put('restore-batch', [Rank\RestoreBatchController::class])->name('restore-batch');
        // Route::put('{rank}', [Rank\UpdateController::class])->name('update');
        // Route::delete('destroy-batch', [Rank\DestroyBatchController::class])->name('destroy-batch');
        // Route::delete('{rank}', [Rank\DestroyController::class])->name('destroy');

        Route::get('import', [Rank\ImportController::class])->name('import');
        Route::post('import', [Rank\ImportProcessController::class])->name('import-process');
        Route::get('export', [Rank\ExportController::class])->name('export');
    });
});
