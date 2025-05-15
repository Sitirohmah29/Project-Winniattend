<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    
    public function showCheckInPage()
    {
        return view('attendance.check-in');
    }

    public function showCheckOutPage()
    {
        return view('attendance.check-out');
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