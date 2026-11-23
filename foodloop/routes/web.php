<?php
use App\Http\Controllers\{PageController,ReportController};
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/impact', [PageController::class, 'impact'])->name('impact');
Route::get('/map', [ReportController::class, 'map'])->name('map');
Route::get('/report', [ReportController::class, 'create'])->name('report.create');
Route::post('/report', [ReportController::class, 'store'])->name('report.store');
Route::get('/status', [ReportController::class, 'status'])->name('status');
Route::post('/reports/{report}/{action}', [ReportController::class, 'act'])
    ->whereIn('action', ['claim', 'start', 'complete', 'reroute'])->name('report.act');
