<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function showMainProfile()
    {
        $user = Auth::user()->load('role');
        return view('profile.indexProfile', compact('user'));
    }

    public function showEditProfile()
    {
        $user = Auth::user();
        return view('profile.page.editProfile', compact('user'));
    }

    public function updatePersonalInfo(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'fullname' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'phone' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
            ]);

            $data = [
                'fullname' => $request->fullname,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'address' => $request->address,
            ];

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');

                // Validasi file
                if ($file->isValid()) {
                    // Hapus foto lama jika ada
                    if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                        Storage::disk('public')->delete($user->profile_photo);
                        Log::info('Old profile photo deleted: ' . $user->profile_photo);
                    }

                    // Generate nama file unik
                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'profile_' . $user->id . '_' . time() . '.' . $extension;

                    // Simpan file baru
                    $path = $file->storeAs('profile_photos', $fileName, 'public');

                    if ($path) {
                        $data['profile_photo'] = $path;
                        Log::info('New profile photo saved: ' . $path);
                    } else {
                        Log::error('Failed to save profile photo');
                        return redirect()->back()->with('error', 'Gagal menyimpan foto profil.');
                    }
                } else {
                    Log::error('Invalid file uploaded');
                    return redirect()->back()->with('error', 'File yang diupload tidak valid.');
                }
            }

            // Update user data
            $user->update($data);
            Log::info('User profile updated for user ID: ' . $user->id);

            return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }
    }

    public function showPersonalInfo()
    {
        $user = Auth::user();
        return view('profile.page.personInfo', compact('user'));
    }
}
