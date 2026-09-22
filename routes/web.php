<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('demo.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::resource('transactions', TransactionController::class)->except(['show']);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/master-data', [MasterController::class, 'index'])->name('masters.index');
    Route::post('/master-data/places', [MasterController::class, 'storePlace'])->name('masters.places.store');
    Route::put('/master-data/places/{place}', [MasterController::class, 'updatePlace'])->name('masters.places.update');
    Route::post('/master-data/categories', [MasterController::class, 'storeCategory'])->name('masters.categories.store');
    Route::put('/master-data/categories/{category}', [MasterController::class, 'updateCategory'])->name('masters.categories.update');
    Route::post('/master-data/sources', [MasterController::class, 'storeSource'])->name('masters.sources.store');
});
