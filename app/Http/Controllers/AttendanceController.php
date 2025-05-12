<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Show the check-in page
     */
    public function showCheckInPage()
    {
        return view('attendance.check-in');
    }

    public function showCheckOutPage()
    {
        return view('attendance.check-out');
    }
    
    
    /**
     * Process the attendance check-in (punch in)
     */
    public function punchIn(Request $request)
    {
        $request->validate([
            'punch_in_location' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'shift' => 'required|string',
        ]);
        
        // Get current authenticated user
        $user = AuthController::email();
        
        // Check if there's already an open attendance record
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereNull('punch_out')
            ->first();
            
        if ($existingAttendance) {
            return redirect()->back()->with('error', 'You already have an active check-in. Please check out first.');
        }
        
        // Create new attendance record
        $attendance = new Attendance();
        $attendance->user_id = $user->id;
        $attendance->user_roles_id = $user->user_roles_id ?? null;
        $attendance->punch_in = Carbon::now();
        $attendance->punch_in_location = $request->punch_in_location;
        $attendance->latitude = $request->latitude;
        $attendance->longitude = $request->longitude;
        $attendance->shift = $request->shift;
        $attendance->status = 'present';
        
        // Handle face photo if uploaded
        if ($request->hasFile('punch_in_photo')) {
            $photo = $request->file('punch_in_photo');
            $photoName = time() . '_' . $user->id . '_in.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/attendance_photos', $photoName);
            $attendance->punch_in_photo = $photoName;
        }
        
        // Check if user is late
        $shiftStartTime = $this->getShiftStartTime($request->shift);
        if ($shiftStartTime) {
            $punchInTime = Carbon::now();
            $isLate = $punchInTime->gt($shiftStartTime);
            
            if ($isLate) {
                $attendance->is_late = true;
                $attendance->late_duration = $punchInTime->diffInMinutes($shiftStartTime);
            }
        }
        
        $attendance->save();
        
        return redirect()->route('dashboard')->with('success', 'Check-in successful!');
    }
    
    /**
     * Process the attendance check-out (punch out)
     */
    public function punchOut(Request $request)
    {
        $request->validate([
            'punch_out_location' => 'required|string',
        ]);
        
        // Get current authenticated user
        $user = AuthController::user();
        
        // Find the latest unchecked-out attendance for the user
        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('punch_out')
            ->latest('punch_in')
            ->first();
            
        if (!$attendance) {
            return redirect()->back()->with('error', 'No active check-in found.');
        }
        
        // Update with checkout data
        $attendance->punch_out = Carbon::now();
        $attendance->punch_out_location = $request->punch_out_location;
        
        // Handle face photo if uploaded
        if ($request->hasFile('punch_out_photo')) {
            $photo = $request->file('punch_out_photo');
            $photoName = time() . '_' . $user->id . '_out.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/attendance_photos', $photoName);
            $attendance->punch_out_photo = $photoName;
        }
        
        $attendance->save();
        
        return redirect()->route('dashboard')->with('success', 'Check-out successful!');
    }
    
    /**
     * Process face verification for attendance
     */
    public function verifyFace(Request $request)
    {
        // In a real application, this would integrate with a face recognition API
        // For now, we'll simulate a successful verification
        
        if ($request->hasFile('face_image')) {
            // Process the face image, verify it, etc.
            // ...
            
            return response()->json([
                'success' => true,
                'message' => 'Face verification successful'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Face image required'
        ], 400);
    }
    
    /**
     * Show attendance history for the authenticated user
     */
    public function history()
    {
        $attendances = Attendance::where('user_id', Auth::id())
            ->orderBy('punch_in', 'desc')
            ->paginate(10);
            
        return view('attendance.history', compact('attendances'));
    }
    
    /**
     * Helper method to get shift start time
     */
    private function getShiftStartTime($shiftName)
    {
        $shifts = [
            'Shift 1 (07:00am - 01:00pm)' => '07:00:00',
            'Frontend Developer' => '07:00:00',
            'Backend Developer' => '09:00:00',
            'UI/UX Designer' => '08:00:00',
            // Add more shifts as needed
        ];
        
        if (isset($shifts[$shiftName])) {
            return Carbon::createFromFormat('H:i:s', $shifts[$shiftName]);
        }
        
        return null;
    }
}