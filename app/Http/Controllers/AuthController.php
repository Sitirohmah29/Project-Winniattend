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
        return view('pwa.splashScreen');
    }

    public function showLoginForm()
    {
        return view('pwa.auth.login');
    }

    public function showAdminLoginForm()
    {
        return view('management_system.signIn');
    }

    public function loginAdmin(Request $request)
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

        // Check if user has admin role
        if (!$user->role || $user->role->name !== 'Admin') {
            throw ValidationException::withMessages([
                'email' => ['You are not authorized to login as admin.'],
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
        return redirect()->intended('dashboardWeb');
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

    public function notify()
    {
        return view('pwa.notification');
    }

    public function changeFaceID()
    {
        return view('profile.page.changeFaceID.changeFace');
    }

    public function faceVerification()
    {
        return view('profile.page.changeFaceID.faceVerified');
    }

    public function report()
    {
        return view('pwa.report.indexReport');
    }

    public function detailsReportDay()
    {
        return view('pwa.report.detailsReport');
    }

    //logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    //forgot password
    public function showForgotPasswordForm()
    {
        return view('pwa.forgot-password.forgot-password');
    }

    public function verificationCode()
    {
        return view('pwa.forgot-password.verif-code');
    }

    public function newPassword()
    {
        return view('pwa.forgot-password.new-pw');
    }

    public function forgotPassword(Request $request)
    {
        // Password reset logic would go here
        // This typically involves sending a reset link via email

        return back()->with('status', 'We have sent you a password reset link!');
    }
}
