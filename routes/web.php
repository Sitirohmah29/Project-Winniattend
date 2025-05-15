<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;

// Auth routes (no change)
Route::get('/', [AuthController::class, 'splash'])->name('splash');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/verif-code', [AuthController::class, 'verificationCode'])->name('verif.code');
Route::get('/new-pw', [AuthController::class, 'newPassword'])->name('new.password');

// ✅ Sanctum Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('Dashboard');

    Route::get('/indexProfile', [AuthController::class, 'mainProfile'])->name('Profile');
    Route::get('/editProfile', [AuthController::class, 'editProfile'])->name('Edit Profile');
    Route::get('/personInfo', [AuthController::class, 'personalInformation'])->name('Personal Information');
    Route::get('/changePw', [AuthController::class, 'changePassword'])->name('Change Password');
    Route::get('/changeFace', [AuthController::class, 'changeFaceID'])->name('Change Face ID');
    Route::get('/faceVerified', [AuthController::class, 'faceVerification'])->name('Face Verification');
    Route::get('/indexReport', [AuthController::class, 'report'])->name('Attedance Report');
    Route::get('/detailsReport', [AuthController::class, 'detailsReportDay'])->name('Details Report');
    Route::get('/notification', [AuthController::class, 'notify'])->name('notification');

    Route::get('/attendance/check-in', [AttendanceController::class, 'showCheckInPage'])->name('attendance.check-in');
    Route::get('/attendance/check-out', [AttendanceController::class, 'showCheckOutPage'])->name('attendance.check-out');
    Route::get('/face-verification', [AttendanceController::class, 'showfaceVerificationPage'])->name('verification.face-verification');
    Route::get('/face-register', [AttendanceController::class, 'faceVerificationPage'])->name('verification.face-register');
});
