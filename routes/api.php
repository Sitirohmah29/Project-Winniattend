<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FaceRegistrationController;
use App\Models\FaceRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// API Routes (jika diperlukan untuk mobile app)
Route::middleware(['auth', 'web'])->prefix('api')->group(function () {
    Route::post('/face/register', [FaceRegistrationController::class, 'register']);
    Route::post('/face/verify', [FaceRegistrationController::class, 'verify']);
    Route::get('/face/status', [FaceRegistrationController::class, 'status']);
    Route::delete('/face/registration', [FaceRegistrationController::class, 'delete']);
});
Route::middleware('auth:sanctum')->get('/face-registration/check', function () {
    $registered = FaceRegistration::where('user_id', Auth::id())->exists();
    return response()->json(['registered' => $registered]);
});

Route::middleware('auth:sanctum')->post('/attendance/face-verification', [AttendanceController::class, 'faceVerificationAbsen']);