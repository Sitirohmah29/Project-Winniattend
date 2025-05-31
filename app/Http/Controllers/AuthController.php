<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function splash()
    {
        return view('splashScreen');
    }

    public function showLoginForm()
    {
         return view('auth.login');
    }

    public function notify(){
        return view('notification');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user exists and is active
        if (!$user || !$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['This account does not exist or has been deactivated.'],
            ]);
        }

        // Attempt login
        if (!Auth::attempt($request->only('email', 'password'), $request->remember)) {
            return back()->withErrors([
                'email' => 'Email or password is incorrect.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Redirect to dashboard or intended location
        return redirect()->intended('dashboard');
    }


    public function changeFaceID() {
        return view('profile.page.changeFaceID.changeFace');
    }

    public function faceVerification()  {
        return view('profile.page.changeFaceID.faceVerified');
    }

    public function report (){
        return view('report.indexReport');
    }

    public function detailsReportDay() {
        return view('report.detailsReport');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showForgotPasswordForm()
    {
        return view('forgot-password.forgot-password');
    }

    public function verificationCode () {
        return view('forgot-password.verif-code');
    }

    public function newPassword (){
        return view('forgot-password.new-pw');
    }

    public function forgotPassword(Request $request)
    {
        // Password reset logic would go here
        // This typically involves sending a reset link via email

        return back()->with('status', 'We have sent you a password reset link!');
    }
}
