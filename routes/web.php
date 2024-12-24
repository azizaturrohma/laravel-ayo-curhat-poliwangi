<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CounselingController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\EmergencyCallController;
use App\Http\Controllers\PeriodReportController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ReportingMiddleware;
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
    Route::post('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    // Panggilan Darurat
    Route::get('emergency-call', [EmergencyCallController::class, 'index'])->middleware('can:emergency_call_access')->name('emergency_call');

    // Pengaduan
    Route::prefix('pengaduan')->group(function () {
        Route::get('/all', [ReportingController::class, 'index'])->middleware('can:read_all_reportings')->name('reportings.index');
        Route::get('', [ReportingController::class, 'indexReportingUser'])->middleware('can:read_reportings')->name('reportings.user');
        Route::get('/create', [ReportingController::class, 'create'])->middleware('can:create_reportings')->name('reportings.create');
        Route::post('/store', [ReportingController::class, 'store'])->middleware('can:create_reportings')->name('reportings.store');

        // Detail & Download
        Route::get('/show/{reporting}', [ReportingController::class, 'show'])->middleware(['can:show_detail_reportings', 'protect_reporting'])->name('reportings.show');

        // Progress Pengaduan
        Route::get('/{id}/progress', [ReportingController::class, 'indexReportingProgress'])->middleware(['can:read_reporting_progress', 'protect_reporting'])->name('reportings.progress');
        Route::post('/progress/create', [ReportingController::class, 'storeReportingProgress'])->middleware('can:create_reporting_progress')->name('reportings.progress.create');

        // Archive / Unarchive
        Route::patch('/{id}/status', [ReportingController::class, 'updateReportingStatus'])->middleware('can:update_reporting_status')->name('reportings.status.update');
    });

    // Laporan (Period Report)
    Route::prefix('laporan')->group(function () {
        Route::get('', [PeriodReportController::class, 'index'])->middleware('can:read_period_report')->name('report.index');
        Route::get('/unduh', [PeriodReportController::class, 'download'])->middleware('can:download_period_report')->name('report.download');
    });

    // User
    Route::prefix('user')->group(function () {
        Route::get('', [UserController::class, 'index'])->middleware('can:read_users')->name('users.index');
        Route::post('/store', [UserController::class, 'store'])->middleware('can:create_users')->name('users.store');
        Route::get('/show', [UserController::class, 'show'])->middleware('can:read_users')->name('users.show');
        Route::get('/profile', [UserController::class, 'indexProfile'])->middleware('can:update_profile')->name('users.profile');
        Route::get('/edit-profile', [UserController::class, 'editProfile'])->middleware('can:update_profile')->name('users.edit.profile');
        Route::patch('/update-profile', [UserController::class, 'updateProfile'])->name('users.update.profile');
        Route::patch('/{user}/update', [UserController::class, 'update'])->name('users.update');
        Route::patch('/{id}/status', [UserController::class, 'updateUserStatus'])->middleware('can:update_user_status')->name('users.status.update');
    });

    // Konseling
    Route::prefix('konseling')->group(function () {
        Route::get('/', [CounselingController::class, 'index'])->name('counselings.index');
        Route::post('/send', [CounselingController::class, 'sendMessage'])->name('counselings.send');
        Route::get('/{receiverId}', [CounselingController::class, 'getMessages'])->name('counselings.messages');
    });
});
