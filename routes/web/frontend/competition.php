<?php

use App\Http\Controllers\Frontend\Competition;
use App\Http\Controllers\Frontend\Competition\Ajax;
use Illuminate\Support\Facades\Route;

Route::namespace('Competition')->prefix('competition')->as('competition.')->group(function () {
    Route::get('{eventSlug}', [Competition\DetailController::class])->name('detail');
    Route::post('{eventSlug}', [Competition\DetailController::class])->name('detail-inject');
    Route::put('{eventSlug}/update', [Competition\UpdateController::class])->name('update');
    Route::put('{eventSlug}/complete', [Competition\CompleteController::class])->name('complete');
    Route::put('{eventSlug}/done', [Competition\DoneController::class])->name('done');
    // registration
    Route::namespace('Ajax')->group(function () {
        Route::get('{eventSlug}/ajax-get-participants', [Ajax\GetParticipantsController::class])->name('ajax-get-participants');
        Route::get('{eventSlug}/ajax-get-participant-detail', [Ajax\GetParticipantController::class])->name('ajax-get-participant-detail');
        Route::get('{eventSlug}/ajax-get-types', [Ajax\GetTypesController::class])->name('ajax-get-types');
    });
    Route::get('{eventSlug}/registration', [Competition\RegisterController::class])->name('register');
    Route::post('{eventSlug}/registration', [Competition\RegisterSubmitController::class])->name('register-submit');
});
