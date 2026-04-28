<?php

use App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event;
use App\Http\Controllers\Dashboard\Admin\Kejuaraan\Event\Participant;
use App\Http\Controllers\Dashboard\Admin\Kejuaraan\Gaya;
use App\Http\Controllers\Dashboard\Admin\Kejuaraan\Report;
use Illuminate\Support\Facades\Route;

Route::prefix('kejuaraan')->as('kejuaraan.')->group(function () {
    Route::middleware('role:coach')->prefix('event')->as('event.')->group(function () {
        Route::get('/', Event\IndexController::class)->name('index');
        Route::get('create', Event\CreateController::class)->name('create');
        Route::post('/', Event\StoreController::class)->name('store');
        Route::get('{event}/edit', Event\EditController::class)->name('edit');
        Route::put('restore-batch', Event\RestoreBatchController::class)->name('restore-batch');
        Route::put('{event}', Event\UpdateController::class)->name('update');
        Route::delete('destroy-batch', Event\DestroyBatchController::class)->name('destroy-batch');
        Route::delete('{event}', Event\DestroyController::class)->name('destroy');

        Route::prefix('{event}/participant')->as('participant.')->group(function () {
            Route::get('/', Participant\IndexController::class)->name('index');
            Route::get('create', Participant\CreateController::class)->name('create');
            Route::get('create-batch', Participant\CreateBatchController::class)->name('create-batch');
            Route::post('/', Participant\StoreController::class)->name('store');
            Route::post('store-batch', Participant\StoreBatchController::class)->name('store-batch');
            Route::get('{user_championship}/edit', Participant\EditController::class)->name('edit');
            Route::put('{user_championship}', Participant\UpdateController::class)->name('update');
            Route::delete('{user_championship}', Participant\DestroyController::class)->name('destroy');
        });
    });
    Route::middleware('role:coach')->prefix('gaya')->as('gaya.')->group(function () {
        Route::get('/', Gaya\IndexController::class)->name('index');
        Route::get('create', Gaya\CreateController::class)->name('create');
        Route::post('/', Gaya\StoreController::class)->name('store');
        Route::get('{gaya}/edit', Gaya\EditController::class)->name('edit');
        Route::put('restore-batch', Gaya\RestoreBatchController::class)->name('restore-batch');
        Route::put('{gaya}', Gaya\UpdateController::class)->name('update');
        Route::delete('destroy-batch', Gaya\DestroyBatchController::class)->name('destroy-batch');
        Route::delete('{gaya}', Gaya\DestroyController::class)->name('destroy');
    });
    Route::middleware('role:coach,member')->prefix('report')->as('report.')->group(function () {
        Route::get('/', Report\IndexController::class)->name('index');
        Route::post('/', Report\IndexController::class)->name('index');
        Route::get('/ajax-gaya', Report\AjaxGayaController::class)->name('ajax-get-gaya');
    });
});
