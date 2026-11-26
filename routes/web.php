<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/impact', [PageController::class, 'impact'])->name('impact');
Route::get('/tentang', [PageController::class, 'about'])->name('about');
Route::get('/map', [ReportController::class, 'map'])->name('map');
Route::get('/map/pins', [ReportController::class, 'pins'])->name('map.pins');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});

// Aktor 1: Pemberi makanan
Route::middleware(['auth', 'role:provider'])->group(function () {
    Route::get('/report', [ReportController::class, 'create'])->name('report.create');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
});

// Pemberi & penerima
Route::middleware('auth')->group(function () {
    Route::get('/status', [ReportController::class, 'status'])->name('status');
    Route::get('/history', [ReportController::class, 'history'])->name('history');
    Route::post('/reports/{report}/{action}', [ReportController::class, 'act'])
        ->whereIn('action', ['claim', 'start', 'complete', 'reroute', 'release'])->name('report.act');
});
