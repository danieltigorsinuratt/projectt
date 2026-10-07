<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Root redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Archives & Documents
    Route::get('/arsip', [ArchiveController::class, 'index'])->name('archives.index');
    Route::get('/arsip/search', [ArchiveController::class, 'search'])->name('archives.search');
    Route::get('/arsip/upload', [ArchiveController::class, 'create'])->name('archives.create');
    Route::post('/arsip/upload', [ArchiveController::class, 'store'])->name('archives.store');
    Route::get('/arsip/folder/{folder}', [ArchiveController::class, 'folder'])->name('archives.folder');
    Route::get('/dokumen/{document}', [ArchiveController::class, 'show'])->name('archives.show');
    Route::get('/dokumen/{document}/download', [ArchiveController::class, 'download'])->name('archives.download');
    Route::delete('/dokumen/{document}', [ArchiveController::class, 'destroy'])->name('archives.destroy');

    // Profile & Settings
    Route::get('/pengaturan', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/pengaturan', [ProfileController::class, 'update'])->name('profile.update');

    // Petugas Arsip & Admin Routes
    Route::middleware('role:Admin,Petugas Arsip')->group(function () {
        Route::get('/folders', [FolderController::class, 'index'])->name('folders.index');
        Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
        Route::put('/folders/{folder}', [FolderController::class, 'update'])->name('folders.update');
        Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');

        Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    });

    // Admin Only Routes
    Route::middleware('role:Admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
