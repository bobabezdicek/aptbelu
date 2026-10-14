<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::view('/', 'pages.index')->name('home');

Route::view('/ochrana-osobnich-udaju', 'pages.personalDataProtection')->name('personal-data-protection');
Route::view('/cookies', 'pages.cookies')->name('cookies');
Route::view('/kontakt', 'pages.contact')->name('contact');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.attempt');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::prefix('admin')
    ->as('admin.')
    ->middleware(['web', 'auth', /* smaž 'verified' pokud ho nepoužíváš  'verified',*/ 'admin'])
    ->group(function () {
        require base_path('routes/admin.php');
    });