<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan form tambah user (opsional)

    public function create()
    {
        $users = \App\Models\User::with('role')->get();
        $roles = \App\Models\Role::all();
        return view('management_system.user_management.indexManagUser', compact('users', 'roles'));
    }

    // Proses tambah user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullname'      => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'required|string|max:20',
            'password'      => 'required|string|min:6|confirmed',
            'role_id'       => 'required|exists:roles,id',
            'birth_date'    => 'required|date',
            'address'       => 'required|string|max:255',
            'shift'         => 'required|in:shift-1,shift-2,shift-3',
            'is_active'     => 'required|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle upload foto profil jika ada
        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Simpan user baru
        $user = User::create([
            'fullname'      => $validated['fullname'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'password'      => Hash::make($validated['password']),
            'role_id'       => $validated['role_id'],
            'birth_date'    => $validated['birth_date'],
            'address'       => $validated['address'],
            'shift'         => $validated['shift'],
            'is_active'     => $validated['is_active'],
            'profile_photo' => $validated['profile_photo'] ?? null,
        ]);

        return redirect()->route('users.create')->with('success', 'User berhasil ditambahkan!');
    }


    // Tampilkan form edit user
    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $roles = \App\Models\Role::all();
        return view('management_system.user_management.editManagUser', compact('user', 'roles'));
    }

    // Proses update user
    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $validated = $request->validate([
            'fullname'      => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'phone'         => 'required|string|max:20',
            'role_id'       => 'required|exists:roles,id',
            'birth_date'    => 'required|date',
            'address'       => 'required|string|max:255',
            'shift'         => 'required|in:shift-1,shift-2,shift-3',
            'is_active'     => 'required|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->update($validated);

        return redirect()->route('users.create')->with('success', 'User berhasil diupdate!');
    }

    // Hapus user
    public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.create')->with('success', 'User berhasil dihapus!');
    }
    // ...existing code...

    public function search(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('fullname', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
                ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $users = $query->get();
        $roles = Role::all();

        return view('management_system.user_management.indexManagUser', compact('users', 'roles'));
    }

}
