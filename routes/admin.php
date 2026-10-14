<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/reservations', fn () => view('pages.admin.reservations'))->name('reservations');
Route::get('/gallery',      fn () => view('pages.admin.gallery'))->name('gallery');
Route::get('/pricing',      fn () => view('pages.admin.pricing'))->name('pricing');
Route::get('/history', fn () => view('pages.admin.history'))->name('history');
