<?php

use App\Http\Controllers\Dashboard\Admin\Event\Stage;
use Illuminate\Support\Facades\Route;

// Route::prefix('{event}/stage')->as('stage.')->group(function () {
//     Route::get('/', [EventStageController::class, 'index'])->name('index');
//     Route::get('create', [EventStageController::class, 'create'])->name('create');
//     Route::post('/', [EventStageController::class, 'store'])->name('store');
//     // Route::get('{eventStage}', [EventStageController::class, 'show'])->name('show');
//     Route::get('{eventStage}/edit', [EventStageController::class, 'edit'])->name('edit');
//     Route::put('{eventStage}', [EventStageController::class, 'update'])->name('update');
//     Route::delete('{eventStage}', [EventStageController::class, 'destroy'])->name('destroy');
//     Route::get('{eventStage}/download', [EventStageController::class, 'download'])->name('download');

//     Route::namespace('Stage')->group(function () {
Route::prefix('{event}/stage')->as('stage.')->group(function () {
    Route::get('/', Stage\IndexController::class)->name('index');
    Route::get('create', Stage\CreateController::class)->name('create');
    Route::post('/', Stage\StoreController::class)->name('store');
    Route::get('{eventStage}/edit', Stage\EditController::class)->name('edit');
    Route::put('{eventStage}', Stage\UpdateController::class)->name('update');
    Route::delete('{eventStage}', Stage\DestroyController::class)->name('destroy');
    Route::get('{eventStage}/download', Stage\DownloadController::class)->name('download');

    include 'session.php';

    Route::prefix('{eventStage}/participant')->as('participant.')->group(function () {
        Route::get('/', [Stage\EventSessionParticipantController::class, 'index'])->name('index');
    });
});
// });
// });
