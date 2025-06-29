<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FaceRegistrationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;


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

    // Attendance
    Route::get('/attendance/check-in', [AttendanceController::class, 'showCheckInPage'])->name('attendance.check-in');
    Route::get('/attendance/check-out', [AttendanceController::class, 'showCheckOutPage'])->name('attendance.check-out');
    Route::post('/attendance/check-out', [AttendanceController::class, 'clockCheckOut'])->name('attendance.realtime');
    Route::get('/attendance/face-verification', [AttendanceController::class, 'showFaceVerificationPage'])->name('attendance.face-verification');
    Route::post('/attendance/face-verification', [AttendanceController::class, 'faceCheckIn'])->name('attendance.face-check-in');
    Route::post('/attendance/face-verification', [AttendanceController::class, 'faceVerificationAbsen']);

    // Face Verification (handled by FaceRegistrationController)
    Route::get('/face-verification', [FaceRegistrationController::class, 'index'])->name('face-verification.index');
    Route::post('/face-verification/capture', [FaceRegistrationController::class, 'capture'])->name('face-verification.capture');
    Route::get('/face-verification/history', [FaceRegistrationController::class, 'history'])->name('face-verification.history');
    Route::delete('/face-verification/{id}', [FaceRegistrationController::class, 'destroy'])->name('face-verification.destroy');
    Route::get('/face-verification/statistics', [FaceRegistrationController::class, 'statistics'])->name('face-verification.statistics');


    Route::get('/face-verification', [AttendanceController::class, 'showfaceVerificationPage'])->name('verification.face-verification');
    Route::get('/face-register', [AttendanceController::class, 'faceVerificationPage'])->name('verification.face-register');
});



//WEB
Route::get('/signIn', fn () => view('management_system.signIn'))->name('signin');

Route::get('/dashboardWeb', fn () => view('management_system.dashboardWeb'))->name('Dashboard');
Route::get('/notificationWeb', fn () => view('management_system.notificationWeb'))->name('notifications');

Route::get('/indexAttedance', fn () => view('management_system.attedance_management.indexAttedance'))->name('Attedance Management');
Route::get('/checkinAttedance', fn () => view('management_system.attedance_management.checkinAttedance'))->name('checkin Attedance');

Route::get('/indexManagUser', fn () => view('management_system.user_management.indexManagUser'))->name('User Management');

Route::get('/indexReport', fn () => view('management_system.report_analytics.indexReport'))->name('Report_and_analytics');
Route::get('/attedanceReport', fn () => view('management_system.report_analytics.attedanceReport'))->name('Attedance Report');
Route::get('/payrollReport', fn () => view('management_system.report_analytics.payrollReport'))->name('Payroll Report');

Route::get('/indexSecurity', fn () => view('management_system.security_settings.indexSecurity'))->name('Security_and_Settings');

// //WEB
// Route::get('/signIn', fn () => view('management_system.signIn'))->name('signin');

//     // (Optional) If you still need these for admin or advanced features, keep them:
//     // Route::get('/face-verification/status', [AttendanceController::class, 'getFaceVerificationStatus'])->name('face.verification.status');
//     // Route::post('/face-verification/reset', [AttendanceController::class, 'resetFaceVerification'])->name('face.verification.reset');
//     // Route::get('/face-verification/status/{userId}', [AttendanceController::class, 'getFaceVerificationStatus'])->name('face.verification.status.user')->middleware('can:view-users');
// });


