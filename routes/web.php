<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function(){
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['web','auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AuthController::class, 'dashboard'])->name('dashboard.admin');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['web','auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
