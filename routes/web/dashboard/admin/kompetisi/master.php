<?php

use App\Http\Controllers\Dashboard\Admin\Master\Participant;
use App\Http\Controllers\Dashboard\Admin\Master\School;
use App\Http\Controllers\Dashboard\Admin\MasterMatchCategoryController;
use App\Http\Controllers\Dashboard\Admin\MasterMatchTypeController;
use App\Http\Controllers\Dashboard\Admin\MasterParticipantController;
use App\Http\Controllers\Dashboard\Admin\MasterSchoolController;
use Illuminate\Support\Facades\Route;

Route::prefix('master')->as('master.')->group(function () {
    Route::prefix('category')->as('category.')->group(function () {
        Route::get('create-batch', [MasterMatchCategoryController::class, 'createBatch'])->name('create-batch');
        Route::post('store-batch', [MasterMatchCategoryController::class, 'storeBatch'])->name('store-batch');
        Route::get('merger', [MasterMatchCategoryController::class, 'merger'])->name('merger');
        Route::put('update-merger', [MasterMatchCategoryController::class, 'updateMerger'])->name('update-merger');
        Route::delete('delete-batch', [MasterMatchCategoryController::class, 'destroyBatch'])->name('destroy-batch');
    });
    Route::resource('category', MasterMatchCategoryController::class)->except(['show']);

    Route::prefix('type')->as('type.')->group(function () {
        Route::get('create-batch', [MasterMatchTypeController::class, 'createBatch'])->name('create-batch');
        Route::post('store-batch', [MasterMatchTypeController::class, 'storeBatch'])->name('store-batch');
        Route::get('merger', [MasterMatchTypeController::class, 'merger'])->name('merger');
        Route::put('update-merger', [MasterMatchTypeController::class, 'updateMerger'])->name('update-merger');
        Route::delete('delete-batch', [MasterMatchTypeController::class, 'destroyBatch'])->name('destroy-batch');
    });
    Route::resource('type', MasterMatchTypeController::class)->except(['show']);

    Route::prefix('school')->as('school.')->group(function () {
        Route::get('merger', [MasterSchoolController::class, 'merger'])->name('merger');
        Route::put('update-merger', [MasterSchoolController::class, 'updateMerger'])->name('update-merger');
        Route::delete('delete-batch', [MasterSchoolController::class, 'destroyBatch'])->name('destroy-batch');
    });
    Route::resource('school', MasterSchoolController::class)->except(['index', 'show']);

    Route::prefix('participant')->as('participant.')->group(function () {
        Route::get('merger', [MasterParticipantController::class, 'merger'])->name('merger');
        Route::put('update-merger', [MasterParticipantController::class, 'updateMerger'])->name('update-merger');
        Route::delete('delete-batch', [MasterParticipantController::class, 'destroyBatch'])->name('destroy-batch');
    });
    Route::resource('participant', MasterParticipantController::class)->except(['index', 'show']);
});
Route::prefix('master')->as('master.')->group(function () {
    Route::prefix('participant')->as('participant.')->group(function () {
        Route::get('/', Participant\IndexController::class)->name('index');
    });
    Route::prefix('school')->as('school.')->group(function () {
        Route::get('/', School\IndexController::class)->name('index');
    });
});
