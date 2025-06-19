<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\FaceVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    // Method untuk halaman check-in
    public function showCheckInPage()
    {
        return view('pwa.attendance.check-in');
    }

    // Method untuk halaman check-out
    public function showCheckOutPage()
    {
        return view('pwa.attendance.check-out');
    }

    // Logic untuk check-out
    public function clockCheckOut(Request $request)
    {
        $request->validate([
            'checkout_time' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', now()->toDateString())
            ->first();

        if ($attendance) {
            $attendance->checkout_time = $request->checkout_time;
            $attendance->latitude = $request->latitude;
            $attendance->longitude = $request->longitude;
            $attendance->save();
        }

        return redirect()->back()->with('success', 'Check out Successfully!');
    }

    // Method untuk halaman verifikasi wajah
    public function showfaceVerificationPage()
    {

        $users = \App\Models\User::select('name', 'profile_photo')->get();
        return view('pwa.verification.face-verification', compact('users'));

    }
    
    // Method untuk halaman registrasi wajah
    public function faceVerificationPage()
    {
        return view('pwa.verification.face-register');
    }

public function faceCheckIn(Request $request)
{
    $request->validate([
        'name' => 'required|string'
    ]);

    $user = \App\Models\User::where('name', $request->name)->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    // Cek apakah sudah absen hari ini
    $today = now()->toDateString();
    $attendance = \App\Models\Attendance::firstOrCreate(
        ['user_id' => $user->id, 'punch_in' => $today],
        [
            'user_roles_id' => $user->roles()->first()?->id,
            'status' => 'present',
            'punch_in' => now(),
            'shift' => null,
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'Absen berhasil',
        'data' => $attendance
    ]);
    
}

public function faceVerificationAbsen(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    $user = \App\Models\User::where('name', $request->name)->first();
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    $today = now()->toDateString();
    $attendance = \App\Models\Attendance::firstOrCreate(
        ['user_id' => $user->id, 'punch_in' => $today],
        [
            'user_roles_id' => $user->roles()->first()?->id,
            'status' => 'present',
            'punch_in' => now(),
            'punch_in_location' => $request->latitude && $request->longitude ? $request->latitude . ',' . $request->longitude : null,
            'shift' => null,
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'Absen berhasil',
        'data' => $attendance
    ]);
}

  
}