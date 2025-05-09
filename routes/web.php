<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Authentication Routes
Route::get('/', [AuthController::class, 'splash'])->name('splash');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/indexProfile', [AuthController::class, 'profile'])->name('indexProfile');

    Route::get('/editProfile', [AuthController::class, 'editP'])->name('editProfile');


    Route::get('/notification', [AuthController::class, 'notify'])->name('notification');
    Route::get('/attendance/check-in', [AttendanceController::class, 'showCheckInPage'])->name('attendance.check-in');
    Route::get('/attendance/check-out', [AttendanceController::class, 'showCheckOutPage'])->name('attendance.check-out');
});   


