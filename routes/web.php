<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaceRegistrationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Models\Attendance;

// PWA ROUTE
Route::get('/', [AuthController::class, 'splash'])->name('splash');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

//Web Route
Route::get('signIn', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('signIn', [AuthController::class, 'loginAdmin'])->name('admin.login.attempt');

// Password Reset
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/verif-code', [AuthController::class, 'verificationCode'])->name('verif.code');
Route::get('/new-pw', [AuthController::class, 'newPassword'])->name('new.password');

// Sanctum Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/notification', fn() => view('pwa.notification'))->name('Notification');
    //Page profile route
    Route::get('/indexProfile', [ProfileController::class, 'showMainProfile'])->name('profile.index');

    Route::get('/personInfo', [ProfileController::class, 'showPersonalInfo'])->name('Personal Information');
    Route::get('/editProfile', [ProfileController::class, 'showEditProfile'])->name('profile.show');
    Route::put('/editProfile', [ProfileController::class, 'updatePersonalInfo'])->name('profile.update');

    // Update password
    Route::get('/changePw', [PasswordController::class, 'changePassword'])->name('Change.Password');
    Route::post('/changePw', [PasswordController::class, 'updatePassword'])->name('Update.password');

    Route::get('/changeFace', [FaceRegistrationController::class, 'changeFaceID'])->name('change.faceID');
    Route::get('/faceVerified', [FaceRegistrationController::class, 'changeFaceVerified'])->name('faceID.verified');
    Route::post('/faceVerified', [FaceRegistrationController::class, 'updateFaceID'])->name('Update.faceid');

    // Attendance
    Route::get('/attendance/check-in', [AttendanceController::class, 'showCheckInPage'])->name('attendance.check-in');
    Route::get('/attendance/check-out', [AttendanceController::class, 'showCheckOutPage'])->name('attendance.check-out');
    Route::post('/attendance/check-out', [AttendanceController::class, 'clockCheckOut'])->name('attendance.realtime');
    Route::post('/attendance/face-verification-checkout', [AttendanceController::class, 'faceVerificationCheckOut'])->name('attendance.face-verification-checkout');
    Route::get('/attendance/face-verification', [AttendanceController::class, 'showFaceVerificationPage'])->name('attendance.face-verification.show');
    Route::post('/attendance/face-verification', [AttendanceController::class, 'faceVerificationAbsen'])->name('attendance.face-verification');
    Route::post('/attendance/permission', [AttendanceController::class, 'setPermission'])->name('attendance.permission');

    // Face Verification (handled by FaceRegistrationController)
    Route::get('/face-verification', [FaceRegistrationController::class, 'index'])->name('face-verification.index');
    Route::post('/face-verification/capture', [FaceRegistrationController::class, 'capture'])->name('face-verification.capture');
    Route::get('/face-verification/history', [FaceRegistrationController::class, 'history'])->name('face-verification.history');
    Route::delete('/face-verification/{id}', [FaceRegistrationController::class, 'destroy'])->name('face-verification.destroy');
    Route::get('/face-verification/statistics', [FaceRegistrationController::class, 'statistics'])->name('face-verification.statistics');

    Route::get('/face-verification', [AttendanceController::class, 'showfaceVerificationPage'])->name('verification.face-verification');
    Route::get('/face-register', [AttendanceController::class, 'faceVerificationPage'])->name('verification.face-register');

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('Dashboard');
    Route::get('/dashboardWeb', [DashboardController::class, 'dashboard'])->name('dashboardWeb');
    Route::get('/notificationWeb', fn() => view('management_system.notificationWeb'))->name('notifications');
    // Route::get('/dashboardWeb/employees', [DashboardController::class, 'countEmployee'])->name('dashboardWeb.employees');

    //MANAGEMENT USER
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/indexManagUser', [UserController::class, 'create'])->name('users.create');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users', [UserController::class, 'search'])->name('users.search');

    // ATTEDANCE MANAGEMENT
    Route::get('/indexAttedance', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/checkin/{Attendance}', [AttendanceController::class, 'showCheckInDetail'])->name('attendance.detail.checkin');
    Route::get('/attedance', [AttendanceController::class, 'search'])->name('attendance.search');
    //REPORT & ANALYTICS
    Route::get('/indexReportWeb', fn() => view('management_system.report_analytics.indexReportWeb'))->name('Report_and_analytics');
    //report & analytics - attedance report
    Route::get('/attedanceReport', fn() => view('management_system.report_analytics.attedanceReport'))->name('Attedance Report');
    Route::get('/attendance/export-pdf', [ReportController::class, 'exportPDF'])
        ->name('attendance.export'); // jika perlu
    Route::get('/attedanceReport', [ReportController::class, 'attendanceReport'])->name('Attendance.report');
    Route::get('/report/attendance', [ReportController::class, 'attendanceReport'])->name('attendance.report');
    Route::get('/indexReport', [ReportController::class, 'indexReport'])->name('indexReport');
    Route::get('/report/details/{id}', [ReportController::class, 'detailsReport'])->name('report.details');

    //report & analytics- payroll report
    Route::get('/payrollReport', [ReportController::class, 'payrollReport'])->name('Payroll Report');
    Route::get('/payroll/export-pdf', [ReportController::class, 'exportPayrollPDF'])->name('payroll.export');

    Route::get('/indexSecurity', fn() => view('management_system.security_settings.indexSecurity'))->name('Security_and_Settings');
});
Route::get('/face-registration/check', function () {
    $registered = \App\Models\FaceRegistration::where('user_id', Auth::id())->exists();
    return response()->json(['registered' => $registered]);
})->middleware('auth');


// //WEB
// Route::get('/signIn', fn () => view('management_system.signIn'))->name('signin');

//     // (Optional) If you still need these for admin or advanced features, keep them:
//     // Route::get('/face-verification/status', [AttendanceController::class, 'getFaceVerificationStatus'])->name('face.verification.status');
//     // Route::post('/face-verification/reset', [AttendanceController::class, 'resetFaceVerification'])->name('face.verification.reset');
//     // Route::get('/face-verification/status/{userId}', [AttendanceController::class, 'getFaceVerificationStatus'])->name('face.verification.status.user')->middleware('can:view-users');
// });
