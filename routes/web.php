<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

// Route untuk Guest (Belum Login)
Route::middleware('guest')->group(function () {
    // 1. Tampilkan Halaman Login (GET)
    Route::get('/', [AuthController::class, 'showlogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showlogin']);

    // 2. Proses Kirim Form Login (POST)
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route untuk Auth (Sudah Login)
Route::middleware('auth')->group(function () {
    // Dashboard (GET)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Logout (POST)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});