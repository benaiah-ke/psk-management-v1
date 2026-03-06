<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// Redirect root to appropriate dashboard
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Finance', 'Branch Admin', 'Committee Admin'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('portal.dashboard');
    }
    return redirect()->route('login');
});

// Demo guide
Route::view('you-know-peter', 'you-know-peter');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Include admin and portal routes
require __DIR__.'/admin.php';
require __DIR__.'/portal.php';
