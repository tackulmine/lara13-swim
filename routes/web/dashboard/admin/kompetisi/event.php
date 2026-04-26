<?php

use App\Http\Controllers\Dashboard\Admin\Event;
use App\Http\Controllers\Dashboard\Admin\Event\Book;
use App\Http\Controllers\Dashboard\Admin\Event\Category;
use App\Http\Controllers\Dashboard\Admin\Event\Estafet;
use App\Http\Controllers\Dashboard\Admin\Event\Registration;
use App\Http\Controllers\Dashboard\Admin\Event\Type;
use Illuminate\Support\Facades\Route;

// Route::resource('event', EventController::class)->except(['show']);

// Route::prefix('event')->as('event.')->group(function () {
//     // Route::get('{event}/download', [EventController::class, 'download'])->name('download');
//     Route::get('{event}/download-event-book', [EventController::class, 'downloadEventBook'])->name('download_event_book');
//     Route::get('{event}/download-report-book', [EventController::class, 'downloadReportBook'])->name('download_report_book');
//     Route::get('{event}/import', [EventController::class, 'import'])->name('import');
//     Route::post('{event}/import', [EventController::class, 'importProcess'])->name('import-process');

Route::prefix('event')->as('event.')->group(function () {
    Route::get('/', Event\IndexController::class)->name('index');
    Route::get('create', Event\CreateController::class)->name('create');
    Route::post('/', Event\StoreController::class)->name('store');
    Route::get('{event}/edit', Event\EditController::class)->name('edit');
    Route::put('restore-batch', Event\RestoreBatchController::class)->name('restore-batch');
    Route::put('{event}', Event\UpdateController::class)->name('update');
    Route::delete('destroy-batch', Event\DestroyBatchController::class)->name('destroy-batch');
    Route::delete('{event}', Event\DestroyController::class)->name('destroy');

    Route::prefix('{event}/type')->as('type.')->group(function () {
        Route::get('/', Type\IndexController::class)->name('index');
        Route::get('edit', Type\EditController::class)->name('edit');
        Route::put('/', Type\UpdateController::class)->name('update');
        Route::put('set-ordering', Type\SetOrderingController::class)->name('set-ordering');
    });

    Route::prefix('{event}/category')->as('category.')->group(function () {
        Route::get('/', Category\IndexController::class)->name('index');
        Route::get('edit', Category\EditController::class)->name('edit');
        Route::put('/', Category\UpdateController::class)->name('update');
        Route::put('set-ordering', Category\SetOrderingController::class)->name('set-ordering');
        Route::get('{masterMatchCategory}/edit', Category\EditTypeController::class)->name('edit-type');
        Route::put('{masterMatchCategory}', Category\UpdateTypeController::class)->name('update-type');
    });

    Route::prefix('{event}/book')->as('book.')->group(function () {
        Route::get('/', Book\IndexController::class)->name('index');
        Route::get('download', Book\DownloadController::class)->name('download');
        Route::put('set-ordering', Book\SetOrderingController::class)->name('set-ordering');
    });

    Route::prefix('{event}/registration')->as('registration.')->group(function () {
        Route::get('/', Registration\IndexController::class)->name('index');
        Route::get('atlet', Registration\AtletController::class)->name('atlet');
        Route::get('atlet/{eventRegistration}/type/edit', Registration\AtletTypeEditController::class)->name('atlet-type-edit');
        Route::put('atlet/{eventRegistration}/type', Registration\AtletTypeUpdateController::class)->name('atlet-type-update');
        Route::get('school', Registration\SchoolController::class)->name('school');
        Route::get('download', Registration\DownloadController::class)->name('download');
        Route::delete('destroy-batch', Registration\DestroyBatchController::class)->name('destroy-batch');
        Route::delete('destroy-batch-atlet', Registration\DestroyBatchAtletController::class)->name('destroy-batch-atlet');
    });

    Route::get('{event}/import', Event\ImportController::class)->name('import');
    Route::post('{event}/import', Event\ImportProcessController::class)->name('import-process');

    Route::get('{event}/download-event-book', Event\DownloadEventBookController::class)->name('download_event_book');
    Route::get('{event}/download-report-book', Event\DownloadReportBookController::class)->name('download_report_book');
    Route::get('{event}/view-medal-participant', Event\ViewMedalParticipantController::class)->name('view_medal_participant');

    include 'event/stage.php';

    Route::prefix('{event}/participant')->as('participant.')->group(function () {
        Route::get('/', [Event\EventSessionParticipantController::class, 'index'])->name('index');
    });

    Route::prefix('{event}/estafet')->as('estafet.')->group(function () {
        Route::get('edit', Estafet\EditController::class)->name('edit');
        Route::put('update', Estafet\UpdateController::class)->name('update');

        Route::get('get-event-session', Estafet\AjaxGetEventSessionController::class)->name('ajax-get-event-session');
        Route::get('get-event-session-participant', Estafet\AjaxGetEventSessionParticipantController::class)->name('ajax-get-event-session-participant');
    });
});
// });
