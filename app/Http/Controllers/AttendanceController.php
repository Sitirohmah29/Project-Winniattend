<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    //show page check in
    public function showCheckInPage()
    {
        return view('attendance.check-in');
    }

    //show page check out
    public function showCheckOutPage()
    {
        return view('attendance.check-out');
    }

    //logic checkout realtime
    public function clockCheckOut(Request $request) {
        //validasi
        $request->validate([
            'checkout_time' => 'required|date_format:Y-m-d H:i:s',
        ]);

        //simpan data ke db
        $attendance = Attendance::where('user_id', auth()->id())
        ->whereDate('date', now()->toDateString())
        ->first();

        if ($attendance) {
            $attendance->checkout_time = $request->checkout_time;
            $attendance->latitude = $request->latitude;
            $attendance->longitude = $request->longitude;
            $attendance->save();
        } else {
            // handle jika data tidak ditemukan
        }

        return redirect()->back()->with('success', 'Check out Successfully!');
    }

    public function showfaceVerificationPage()
    {
        return view('verification.face-verification');
    }
    public function faceVerificationPage()
    {
        return view('verification.face-register');
    }

}
