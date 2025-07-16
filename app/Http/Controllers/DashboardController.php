<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Ambil 10 data presensi terakhir + user-nya
        $attendances = Attendance::with('user')->orderByDesc('date')->limit(10)->get();

        // Hitung total user (pegawai)
        $totalEmployees = User::count();

        $totalWorkSeconds = \App\Models\Attendance::whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->get()
            ->reduce(function ($carry, $attendance) {
                $checkIn = \Carbon\Carbon::parse($attendance->date . ' ' . $attendance->check_in);
                $checkOut = \Carbon\Carbon::parse($attendance->date . ' ' . $attendance->check_out);
                $diff = $checkOut->diffInSeconds($checkIn);
                return $carry + $diff;
            }, 0);

        // Konversi ke jam:menit:detik
        $hours = floor($totalWorkSeconds / 3600);
        $minutes = floor(($totalWorkSeconds % 3600) / 60);
        $seconds = $totalWorkSeconds % 60;
        $totalWorkTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        // Hitung berdasarkan role
        $laravelCount = User::whereHas('role', fn($q) => $q->where('name', 'Laravel Developer'))->count();
        $fullstackCount = User::whereHas('role', fn($q) => $q->where('name', 'Fullstack Developer'))->count();
        $copywriterCount = User::whereHas('role', fn($q) => $q->where('name', 'Copy Writer'))->count();
        $frontendCount = User::whereHas('role', fn($q) => $q->where('name', 'Frontend Developer'))->count();
        $backendCount = User::whereHas('role', fn($q) => $q->where('name', 'Backend Developer'))->count();
        $adminCount = User::whereHas('role', fn($q) => $q->where('name', 'Admin'))->count();

        // Top 5 Attendance berdasarkan jumlah check-in
        $topAttendances = Attendance::selectRaw('user_id, COUNT(*) as total_checkin')
            ->whereNotNull('check_in')
            ->groupBy('user_id')
            ->orderByDesc('total_checkin')
            ->with('user')
            ->limit(5)
            ->get();

        return view('management_system.dashboardWeb', compact(
            'attendances',
            'totalEmployees',
            'laravelCount',
            'fullstackCount',
            'copywriterCount',
            'frontendCount',
            'backendCount',
            'adminCount',
            'topAttendances',
            'totalWorkTime'
        ));
    }

    public function index()
    {
        $user = Auth::user();

        // Ambil shift user
        $shift = $user->shift; // Pastikan ada relasi/field shift di tabel user

        // Tentukan jam kerja berdasarkan shift
        $workingHours = $shift == 1 ? '08.00 am - 04.00 pm' : '02.00 pm - 09.00 pm';

        // Ambil data time track (misal dari tabel attendance)
        // Contoh: hitung jumlah status per hari dalam seminggu terakhir
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $attendanceData = \App\Models\Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->format('D');
            });

        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $onTime = [];
        $late = [];
        $permission = [];
        $absent = [];
        foreach ($days as $day) {
            $records = $attendanceData->get($day, collect());
            $onTime[] = $records->where('status', 'onTime')->count();
            $late[] = $records->where('status', 'late')->count();
            $permission[] = $records->where('status', 'permission')->count();
            $absent[] = $records->where('status', 'absent')->count();
        }

        return view('pwa.dashboard', compact(
            'user',
            'workingHours',
            'onTime',
            'late',
            'permission',
            'absent'
        ));
    }
}
