<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApprovalPendingController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\ReadingTestController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});


// Authentication routes
require __DIR__ . '/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Approval pending routes
    Route::get('/approval-pending', [ApprovalPendingController::class, 'index'])->name('approval.pending');
    Route::get('/approval-check', [ApprovalPendingController::class, 'check'])->name('approval.check');

    // Home route (requires approval)
    Route::get('/home', [HomeController::class, 'index'])
        ->middleware(['approved'])
        ->name('home');

    Route::get('/reading-test', [HomeController::class, 'readingTest'])->name('reading-test');

    Route::get('/reading-test/{slug}', [ReadingTestController::class, 'readingTestHandle'])->name('reading-test-handle');

    Route::get('/reading-test/{slug}/handle', [ReadingTestController::class, 'handle'])->name('reading-test.handle');

    Route::post('/reading-test/{slug}/submit', [ReadingTestController::class, 'submit'])->name('reading-test.submit');

    Route::get('/listening-test', [HomeController::class, 'listeningTest'])->name('listening-test');
});

// Admin routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users/approval', [UserApprovalController::class, 'index'])->name('users.approval');
    Route::get('/users/pending-count', [UserApprovalController::class, 'checkPendingCount'])->name('users.pending-count');
    Route::post('/users/{user}/approve', [UserApprovalController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [UserApprovalController::class, 'reject'])->name('users.reject');
    Route::post('/users/bulk-approve', [UserApprovalController::class, 'bulkApprove'])->name('users.bulk-approve');
    Route::post('/users/bulk-reject', [UserApprovalController::class, 'bulkReject'])->name('users.bulk-reject');
    Route::get('/users/logout-all', [UserApprovalController::class, 'logoutAll'])->name('users.logout-all');
});
