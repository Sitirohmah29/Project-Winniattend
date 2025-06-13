<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;

//MOBILE
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

// Sanctum Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', fn () => view('pwa.dashboard'))->name('Dashboard');

    //Page profile route
    Route::get('/indexProfile', [ProfileController::class, 'showMainProfile'])->name('profile.index');
    Route::get('/personInfo', [ProfileController::class, 'showPersonalInfo'])->name('Personal Information');

    Route::get('/editProfile', [ProfileController::class, 'showEditProfile'])->name('profile.show');
    Route::put('/editProfile', [ProfileController::class, 'updatePersonalInfo'])->name('profile.update');

    // Update password
    Route::get('/changePw', [PasswordController::class, 'changePassword'])->name('Change.Password');
    Route::post('/changePw', [PasswordController::class, 'updatePassword'])->name('Update.password');


    Route::get('/changeFace', [AuthController::class, 'changeFaceID'])->name('Change Face ID');
    Route::get('/faceVerified', [AuthController::class, 'faceVerification'])->name('Face Verification');
    Route::get('/indexReport', [AuthController::class, 'report'])->name('Attedance Report');
    Route::get('/detailsReport', [AuthController::class, 'detailsReportDay'])->name('Details Report');
    Route::get('/notification', [AuthController::class, 'notify'])->name('notification');

    //checkin
    Route::get('/attendance/check-in', [AttendanceController::class, 'showCheckInPage'])->name('attendance.check-in');

    //checkout
    Route::get('/attendance/check-out', [AttendanceController::class, 'showCheckOutPage'])->name('attendance.check-out');
    Route::post('/attendance/check-out', [AttendanceController::class, 'clockCheckOut'])->name('attendance.realtime');


    Route::get('/face-verification', [AttendanceController::class, 'showfaceVerificationPage'])->name('verification.face-verification');
    Route::get('/face-register', [AttendanceController::class, 'faceVerificationPage'])->name('verification.face-register');
});


//WEB
Route::get('/signIn', fn () => view('management_system.signIn'))->name('signin');
