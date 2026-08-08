<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PurchaseTrackerController;
use App\Http\Controllers\UserMonitoringController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;

// =======================
// Public Routes
// =======================

// Home
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =======================
// Protected Routes
// =======================

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Asset routes
    Route::get('/assets/import', [AssetController::class, 'importForm'])->name('assets.import.form');
    Route::post('/assets/import', [AssetController::class, 'import'])->name('assets.import');
    Route::get('/assets/template/download', [AssetController::class, 'downloadTemplate'])->name('assets.template');
    Route::get('/assets/{asset}/details', [AssetController::class, 'details'])->name('assets.details');
    Route::resource('assets', AssetController::class);

    // Purchase Tracker routes
    Route::get('/purchaseTracker/import', [PurchaseTrackerController::class, 'importForm'])->name('purchaseTracker.import.form');
    Route::post('/purchaseTracker/import', [PurchaseTrackerController::class, 'import'])->name('purchaseTracker.import');
    Route::get('/purchaseTracker/template/download', [PurchaseTrackerController::class, 'downloadTemplate'])->name('purchaseTracker.template');
    Route::get('/purchaseTracker/{id}/modal', [PurchaseTrackerController::class, 'showModal'])->name('purchaseTracker.modal');
    Route::get('/purchaseTracker/{purchaseTracker}/details', [PurchaseTrackerController::class, 'details'])->name('purchaseTracker.details');
    Route::resource('purchaseTracker', PurchaseTrackerController::class);

    // User Monitoring
    Route::resource('user-monitoring', UserMonitoringController::class);
    Route::resource('users', UserController::class);

});