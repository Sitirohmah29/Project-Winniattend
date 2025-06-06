<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function showMainProfile(){
        return view('profile.indexProfile');
    }

    public function showEditProfile () {
        $user= Auth::user();
        return view('profile.page.editProfile', compact('user'));
    }

    public function updatePersonalInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
        ]);

        $data = [
            'name' => $request->name,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Simpan foto baru dengan nama unik
            $originalName = $request->file('profile_photo')->getClientOriginalName();
            $extension = $request->file('profile_photo')->getClientOriginalExtension();
            $fileName = time() . '_' . $user->id . '.' . $extension;

            $path = $request->file('profile_photo')->storeAs('profile_photos', $fileName, 'public');
            $data['profile_photo'] = $path;
        }
        $user->update($data);

        return redirect()->route('Profile')->with('success', 'Profile updated successfully.');
    }

    public function showPersonalInfo () {
        $user = Auth::user(); // ambil user yang sedang login
        return view('profile.page.personInfo', compact('user'));
    }
}
