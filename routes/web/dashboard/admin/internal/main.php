<?php

use App\Http\Controllers\Dashboard\Admin;
use App\Http\Controllers\Dashboard\Admin\GayaController;
use App\Http\Controllers\Dashboard\Admin\MasterMemberClassController;
use App\Http\Controllers\Dashboard\Admin\Member;
use App\Http\Controllers\Dashboard\Admin\MemberGayaLimitController;
use App\Http\Controllers\Dashboard\Admin\MemberInvitation;
use App\Http\Controllers\Dashboard\Admin\MemberLimitController;
use App\Http\Controllers\Dashboard\Admin\StaffController;
use Illuminate\Support\Facades\Route;

Route::namespace('Member')->prefix('member')->as('member.')->group(function () {
    Route::middleware('role:coach')->group(function () {
        Route::get('create', [Member\CreateController::class])->name('create');
        Route::post('store', [Member\StoreController::class])->name('store');
        Route::delete('{member}/destroy', [Member\DestroyController::class])->name('destroy');

        Route::delete('destroy-batch', [Member\DestroyBatchController::class])->name('destroy-batch');
        Route::put('restore-batch', [Member\RestoreBatchController::class])->name('restore-batch');
    });

    Route::middleware('role:coach,member')->group(function () {
        Route::get('/', [Member\IndexController::class])->name('index');
        Route::get('{member}', [Member\ShowController::class])->name('show');
        Route::get('{member}/edit', [Member\EditController::class])->name('edit');
        Route::put('{member}', [Member\UpdateController::class])->name('update');
        Route::get('{member}/print', [Member\PrintController::class])->name('print');
    });
});

// Route::resource('member', MemberController::class)->middleware('role:coach')->except([create::class, 'store']);

Route::middleware('role:coach,member')->group(function () {
    Route::prefix('member-report')->as('member-report.')->group(function () {
        Route::get('/', [Admin\MemberReportController::class, 'index'])->name('index');
    });
    Route::prefix('member-limit')->as('member-limit.')->group(function () {
        Route::get('ajax-limit-gaya', [MemberLimitController::class, 'ajaxLimitGayaFromMember'])->name('ajax-limit-gaya');
    });
});

Route::middleware('role:coach')->group(function () {
    Route::resource('staff', StaffController::class)->except(['show']);

    Route::prefix('gaya')->as('gaya.')->group(function () {
        Route::delete('destroy-batch', [GayaController::class, 'destroyBatch'])->name('destroy-batch');
        Route::put('restore-batch', [GayaController::class, 'restoreBatch'])->name('restore-batch');
    });
    Route::resource('gaya', GayaController::class)->except(['show']);

    Route::resource('class', MasterMemberClassController::class)->except(['show']);

    Route::prefix('member-limit')->as('member-limit.')->group(function () {
        Route::get('create-batch', [MemberLimitController::class, 'createBatch'])->name('create-batch');
        Route::post('store-batch', [MemberLimitController::class, 'storeBatch'])->name('store-batch');
        Route::delete('destroy-batch', [MemberLimitController::class, 'destroyBatch'])->name('destroy-batch');
    });
    Route::resource('member-limit', MemberLimitController::class)->except(['show']);

    Route::prefix('member-gaya-limit')->as('member-gaya-limit.')->group(function () {
        Route::get('create-batch', [MemberGayaLimitController::class, 'createBatch'])->name('create-batch');
        Route::post('store-batch', [MemberGayaLimitController::class, 'storeBatch'])->name('store-batch');
        Route::delete('delete-batch', [MemberGayaLimitController::class, 'destroyBatch'])->name('destroy-batch');
    });
    Route::resource('member-gaya-limit', MemberGayaLimitController::class)->except(['show']);

    Route::namespace('MemberInvitation')->prefix('member-invitation')->as('member-invitation.')->group(function () {
        Route::get('/', [MemberInvitation\IndexController::class])->name('index');
        Route::get('{invitation}/edit', [MemberInvitation\EditController::class])->name('edit');
        Route::put('{invitation}', [MemberInvitation\UpdateController::class])->name('update');
        Route::put('{invitation}/rollback', [MemberInvitation\RollbackController::class])->name('rollback');
        Route::delete('destroy-batch', [MemberInvitation\DestroyBatchController::class])->name('destroy-batch');
        Route::delete('{invitation}', [MemberInvitation\DestroyController::class])->name('destroy');
    });
});
