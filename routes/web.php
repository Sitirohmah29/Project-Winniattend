<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
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

Route::get('/', function () {
    return view('splashScreen');
});

Route::post('/api/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if ($user) {
        Auth::login($user);
        return ['token' => $user->createToken('api-token')->plainTextToken];
    }

    return response()->json(['error' => 'Unauthorized'], 401);
});
