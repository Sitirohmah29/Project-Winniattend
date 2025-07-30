<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\FaceRegistration;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    // Halaman check-in
    public function showCheckInPage()
    {
        $user = Auth::user()->load('role'); // Pastikan role sudah dimuat

        //mengambil shift user
        $shift = $user->shift;
        // Tentukan jam kerja berdasarkan shift
        if ($shift == 'shift-1') {
            $workingHours = '08.00 am - 04.00 pm';
        } elseif ($shift == 'shift-2') {
            $workingHours = '02.00 pm - 09.00 pm';
        } else {
            $workingHours = '08.00 am - 04.00 pm'; // Default
        }

        //mengambil role user
        $role_id = $user->role_id;


        return view('pwa.attendance.check-in', compact('user', 'workingHours'));
    }

    // Halaman check-out
    public function showCheckOutPage()
    {
        return view('pwa.attendance.check-out');
    }

    // Logic check-out
    public function clockCheckOut(Request $request)
    {
        $request->validate([
            'checkout_time' => 'required|date_format:Y-m-d H:i:s',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('check_in', now()->toDateString())
            ->first();

        if ($attendance) {
            $attendance->check_out = $request->checkout_time;
            $attendance->check_out_location = $request->latitude && $request->longitude
                ? $request->latitude . ',' . $request->longitude
                : null;
            $attendance->save();
        }

        return redirect()->back()->with('success', 'Check out Successfully!');
    }


    // Halaman verifikasi wajah
    public function showfaceVerificationPage()
    {
        $user = Auth::user();

        // Ambil shift dan tentukan jam kerja
        $shiftLabel = 'Shift 1';
        $shiftTime = '08.00am - 04.00pm';

        if ($user->shift == 'shift-2') {
            $shiftLabel = 'Shift-2';
            $shiftTime = '02.00pm - 09.00pm';
        }

        $faceImages = FaceRegistration::where('user_id', $user->id)->get()->map(function ($item) {
            return [
                'name' => pathinfo($item->image_name, PATHINFO_FILENAME),
                'path' => Storage::url($item->image_path),
            ];
        });

        return view('pwa.verification.face-verification', compact('faceImages', 'user', 'shiftLabel', 'shiftTime'));
    }

    // Halaman registrasi wajah
    public function faceVerificationPage()
    {
        return view('pwa.verification.face-register');
    }


    private function getLocationName($latitude, $longitude)
    {
        try {
            $response = file_get_contents("https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}");
            $data = json_decode($response, true);
            return $data['display_name'] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
    public function faceVerificationAbsen(Request $request)
    {
        try {
            if ($request->isJson()) {
                $data = $request->json()->all();
                $request->merge($data);
            }

            Log::info('Face Verification Request', $request->all());

            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            $userId = $request->user_id;
            $today = now()->toDateString();

            // Cek apakah sudah absen hari ini
            $existingAttendance = Attendance::where('user_id', $userId)
                ->where('date', $today)
                ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'User has already checked in today',
                    'data' => $existingAttendance
                ], 409);
            }

            // Simpan absen baru
            $locationName = null;
            if ($request->latitude && $request->longitude) {
                $locationName = $this->getLocationName($request->latitude, $request->longitude);
            }

            //cek waktu check-in
            $checkInTime = $request->input('check_in_time') ?? now()->format('H:i:s');

            $user = \App\Models\User::findOrFail($userId); // Pastikan user terambil

            $shiftStartTime = '08:00:00'; // default shift-1
            if ($user->shift === 'shift-2') {
                $shiftStartTime = '14:00:00';
            }

            // Konversi ke Carbon object
            $checkIn = Carbon::parse($checkInTime);
            $shiftStart = Carbon::parse($shiftStartTime);

            // Bandingkan check-in dengan shift start
            $status = $checkIn->gt($shiftStart) ? 'Late' : 'onTime';
            
            $attendance = Attendance::create([
                'user_id' => $userId,
                'date' => now()->toDateString(),
                'status' => $status,
                'check_in' => $checkInTime,
                'check_in_location' => $locationName,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            Log::info('Attendance created successfully', [
                'user_id' => $userId,
                'attendance_id' => $attendance->id,
                'location' => $attendance->check_in_location
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absen berhasil',
                'data' => [
                    'id' => $attendance->id,
                    'user_id' => $userId,
                    'check_in' => $attendance->check_in,
                    'location' => $attendance->check_in_location,
                    'status' => $attendance->status
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in face verification', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Unexpected error in face verification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    public function faceVerificationCheckOut(Request $request)
    {
        try {
            if ($request->isJson()) {
                $data = $request->json()->all();
                $request->merge($data);
            }

            Log::info('Face Verification Check Out Request', $request->all());

            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'checkout_time' => 'required|date_format:Y-m-d H:i:s',
            ]);

            $userId = $request->user_id;
            $today = now()->toDateString();

            // Cari attendance hari ini
            $attendance = Attendance::where('user_id', $userId)
                ->where('date', $today)
                ->first();

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum melakukan check-in hari ini',
                ], 404);
            }


            // Update data check out
            $locationName = null;
            if ($request->latitude && $request->longitude) {
                $locationName = $this->getLocationName($request->latitude, $request->longitude);
            }

            // Gunakan waktu dari request (checkout_time) atau waktu server
            $attendance->check_out = Carbon::parse($request->checkout_time)->format('H:i:s');
            $attendance->check_out_location = $locationName;
            $attendance->latitude = $request->latitude;
            $attendance->longitude = $request->longitude;
            // Jangan update status ke 'checked_out', biarkan tetap 'onTime' atau 'Late'
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Check out berhasil',
                'data' => [
                    'id' => $attendance->id,
                    'user_id' => $userId,
                    'check_out' => $attendance->check_out,
                    'location' => $attendance->check_out_location,
                    'status' => $attendance->status
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in face verification check out', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Unexpected error in face verification check out', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    public function setPermission(Request $request)
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        // Cari atau buat attendance berdasarkan user_id dan date (BUKAN check_in)
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $userId, 'date' => $today],
            // Tambahkan default value lain jika perlu, misal shift
            ['shift' => 'Shift 1']
        );

        $attendance->permission = true;
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil dicatat',
            'data' => $attendance
        ]);
    }


    //MANAGEMENT ATTEDANCE WEB
    public function indexAttendanceWeb(Request $request)
    {
        $query = Attendance::with('user.role');

        // Filter pencarian (search)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('fullname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                    ->orWhereHas('user.role', function ($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('date', 'like', "%{$search}%");
            });
        }

        // Filter tahun, default tahun sekarang
        $year = $request->input('year', date('Y'));
        if (is_numeric($year)) {
            $query->whereYear('date', $year);
        }

        // Filter bulan kecuali 'All'
        $month = $request->input('month');
        if (!empty($month) && $month !== 'All' && is_numeric($month)) {
            $query->whereMonth('date', $month);
        }

        // Data
        $attendances = $query->orderBy('date', 'desc')->get();

        return view('management_system.attedance_management.indexAttedance', compact('attendances'));
    }

    public function showCheckInDetail($id)
    {
        $attendance = Attendance::with('user.role')->findOrFail($id);

        return view('management_system.attedance_management.checkinAttedance', compact('attendance'));
    }
}
