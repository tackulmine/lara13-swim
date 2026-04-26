<?php

use App\Http\Controllers\Dashboard\Admin\Event\Stage\Session\Participant;
use App\Http\Controllers\Dashboard\Admin\Event\Stage\Session\Participant\Detail;
use Illuminate\Support\Facades\Route;

// Route::namespace('Session')->prefix('{eventSession}/participant')->as('participant.')->group(function () {
//     Route::get('/', [EventParticipantController::class, 'index'])->name('index');
//     Route::get('create', [EventParticipantController::class, 'create'])->name('create');
//     Route::post('/', [EventParticipantController::class, 'store'])->name('store');
//     // Route::get('{eventSessionParticipant}', [EventParticipantController::class, 'show'])->name('show');
//     Route::get('{eventSessionParticipant}/edit', [EventParticipantController::class, 'edit'])->name('edit');
//     Route::put('{eventSessionParticipant}', [EventParticipantController::class, 'update'])->name('update');
//     Route::delete('{eventSessionParticipant}', [EventParticipantController::class, 'destroy'])->name('destroy');

Route::prefix('{eventSession}/participant')->as('participant.')->group(function () {
    Route::get('/', Participant\IndexController::class)->name('index');
    Route::get('create', Participant\CreateController::class)->name('create');
    Route::post('/', Participant\StoreController::class)->name('store');
    Route::get('{eventSessionParticipant}/edit', Participant\EditController::class)->name('edit');
    Route::put('{eventSessionParticipant}', Participant\UpdateController::class)->name('update');
    Route::delete('{eventSessionParticipant}', Participant\DestroyController::class)->name('destroy');

    Route::prefix('{eventSessionParticipant}/details')->as('detail.')->group(function () {
        Route::get('edit', Detail\EditController::class)->name('edit');
        Route::put('update', Detail\UpdateController::class)->name('update');
    });
});
// });
