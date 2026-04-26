<?php

use App\Http\Controllers\Dashboard\Admin\Event\Stage\Session;
use Illuminate\Support\Facades\Route;

// Route::prefix('{eventStage}/session')->as('session.')->group(function () {
//     Route::get('/', [EventSessionController::class, 'index'])->name('index');
//     Route::get('create', [EventSessionController::class, 'create'])->name('create');
//     Route::post('/', [EventSessionController::class, 'store'])->name('store');
//     // Route::get('{eventSession}', [EventSessionController::class, 'show'])->name('show');
//     Route::get('{eventSession}/edit', [EventSessionController::class, 'edit'])->name('edit');
//     Route::put('{eventSession}', [EventSessionController::class, 'update'])->name('update');
//     Route::delete('{eventSession}', [EventSessionController::class, 'destroy'])->name('destroy');

Route::prefix('{eventStage}/session')->as('session.')->group(function () {
    Route::get('/', Session\IndexController::class)->name('index');
    Route::get('create', Session\CreateController::class)->name('create');
    Route::post('/', Session\StoreController::class)->name('store');
    Route::get('{eventSession}/edit', Session\EditController::class)->name('edit');
    Route::put('{eventSession}', Session\UpdateController::class)->name('update');
    Route::delete('{eventSession}', Session\DestroyController::class)->name('destroy');

    include 'participant.php';
});
// });
