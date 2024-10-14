<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CounselingController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\EmergencyCallController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::prefix('auth')->group(function () {
    // Login
    Route::prefix('login')->group(function () {
        Route::get('', [LoginController::class, 'index'])->middleware('guest')->name('login');
        Route::post('', [LoginController::class, 'login'])->middleware('guest');
    });

    // Logout
    Route::get('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    // Panggilan Darurat
    Route::get('emergency-call', [EmergencyCallController::class, 'index'])->middleware('can:emergency_call_access')->name('emergency_call');

    // Pengaduan
    Route::prefix('pengaduan')->group(function () {
        Route::get('/all', [ReportingController::class, 'index'])->middleware('can:read_all_reportings')->name('reportings.all');
        Route::get('', [ReportingController::class, 'indexReportingUser'])->middleware('can:read_reportings')->name('reportings.user');
        Route::get('/create', [ReportingController::class, 'create'])->middleware('can:create_reportings')->name('reportings.create');
        Route::post('/store', [ReportingController::class, 'store'])->middleware('can:create_reportings')->name('reportings.store');

        // Detail & Download
        Route::get('/show', [ReportingController::class, 'show'])->middleware('can:show_detail_reportings')->name('reportings.show');

        // Progress Pengaduan
        Route::get('/{id}/progress', [ReportingController::class, 'indexReportingProgress'])->middleware('can:read_reporting_progress')->name('reportings.progress');
        Route::post('/progress/create', [ReportingController::class, 'storeReportingProgress'])->middleware('can:create_reporting_progress')->name('reportings.progress.create');

        // Archive / Unarchive
        Route::patch('/{id}/status', [ReportingController::class, 'updateReportingStatus'])->middleware('can:update_reporting_status')->name('reportings.status.update');
    });

    Route::prefix('user')->group(function () {
        Route::get('', [UserController::class, 'index'])->middleware('can:read_users')->name('users.index');
        Route::post('/store', [UserController::class, 'store'])->middleware('can:create_users')->name('users.store');
        Route::get('/show', [UserController::class, 'show'])->middleware('can:read_users')->name('users.show');
        Route::get('/profile', [UserController::class, 'indexProfile'])->middleware('can:update_profile')->name('users.profile');
        Route::patch('/update-profile', [UserController::class, 'updateProfile'])->name('users.update.profile');
        Route::patch('/{user}/update', [UserController::class, 'update'])->name('users.update');
        Route::patch('/{id}/status', [UserController::class, 'updateUserStatus'])->middleware('can:update_user_status')->name('users.status.update');
        Route::delete('/{user}/delete', [UserController::class, 'destroy'])->middleware('can:delete_users')->name('users.destroy');
    });

    Route::prefix('konseling')->group(function () {
        Route::get('/', [CounselingController::class, 'index'])->name('counselings.index');
        Route::post('/send', [CounselingController::class, 'sendMessage'])->name('counselings.send');
        // Route::get('/{receiverId}', [CounselingController::class, 'getMessages'])->name('counselings.messages');
        // Route::get('/{receiverId}', [CounselingController::class, 'getMessages'])->name('messages');
        // Route::post('/{receiverId}', [CounselingController::class, 'counselings.Message'])
    // ->name('counselings.messages');
        // event(new CounselingMessages('hello world'));
    });
});
