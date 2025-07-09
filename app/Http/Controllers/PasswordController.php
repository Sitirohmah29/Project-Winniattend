<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PasswordController extends Controller
{
    public function changePassword() {
        return view('profile.page.changePw');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:8',
        ]);

        // Cek password lama
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Your Current password is wrong']);
        }

        // Update password baru
        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password Change Succesfully!');
    }
}
