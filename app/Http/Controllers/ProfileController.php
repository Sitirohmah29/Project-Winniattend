<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showMainProfile(){
        return view('profile.indexProfile');
    }

    public function showEditProfile () {
        $user= Auth::user();
        return view('profile.page.editProfile', compact('user'));
    }

    public function updatePersonalInfo (Request $request) {
        $user = Auth::user();

        $request->validate([
            'name'=>'required|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('Profile')->with('success', 'Profile updated successfully.');
    }

    public function showPersonalInfo () {
        $user = Auth::user(); // ambil user yang sedang login
        return view('profile.page.personInfo', compact('user'));
    }
}
