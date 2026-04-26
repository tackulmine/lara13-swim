<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\Admin;
use App\Http\Controllers\Dashboard\User;
use App\Http\Controllers\InvitationsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::get('/', function () {
//     return view('welcome');
// });
Route::namespace('Frontend')->group(function () {
    include 'web/frontend/main.php';
});

Auth::routes(['register' => false, 'reset' => false, 'confirm' => false, 'verify' => false]);

Route::get('register', [RegisterController::class, 'showRegistrationForm'])
    ->name('register')
    ->middleware('hasInvitation');
Route::post('register', [RegisterController::class, 'register']);

Route::get('register/request', [RegisterController::class, 'requestInvitation'])->name('requestInvitation');
Route::post('invitations', [InvitationsController::class, 'store'])->middleware('guest')->name('storeInvitation');

Route::middleware('auth')->group(function () {
    // dashboard
    Route::prefix('dashboard')->as('dashboard.')->group(function () {
        // admin
        Route::prefix('admin')->as('admin.')->group(function () {
            Route::middleware('role:coach,jury,external,member')->group(function () {
                Route::get('', [Admin\HomeController::class, 'index'])->name('home');
            });

            Route::middleware('role:coach,jury,external')->group(function () {
                include 'web/dashboard/admin/kompetisi/main.php';
            });

            include 'web/dashboard/admin/kejuaraan/main.php';
            include 'web/dashboard/admin/external/main.php';
            include 'web/dashboard/admin/internal/main.php';
        });
        // user
        Route::prefix('user')->as('user.')->group(function () {
            Route::middleware('role:user')->group(function () {
                Route::get('', [User\HomeController::class, 'index'])->name('home');

                // Route::middleware('role:coach,jury,external')->group(function () {
                //     include 'web/dashboard/user/kompetisi/main.php';
                // });
                // Route::middleware('role:coach')->group(function () {
                //     include 'web/dashboard/user/kejuaraan/main.php';
                //     include 'web/dashboard/user/external/main.php';
                //     include 'web/dashboard/user/internal/main.php';
                // });
            });
        });
    });
});
