<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {

        $attendances = Attendance::with('user')->orderByDesc('created_at')->limit(10)->get();

        // Hitung total user (pegawai)
        $totalEmployees = User::count();

        // Ambil semua role beserta jumlah user-nya
        $roles = Role::withCount('users')->get();

        // TOP 5 attendance
        $topAttendances = Attendance::select('user_id')
            ->whereDate('date', '>=', now()->subDays(30))
            ->whereNotNull('check_in')
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(*) as total_checkin')
            ->orderByDesc('total_checkin')
            ->take(5)
            ->get();

        $topUsers = $topAttendances->map(function ($item) {
            $userId = $item->user_id;

            // Ambil data kehadiran user
            $attendances = Attendance::where('user_id', $userId)
                ->whereDate('date', '>=', now()->subDays(30))
                ->whereNotNull('check_in')
                ->whereNotNull('check_out')
                ->get();

            // Hitung total waktu kerja dalam detik
            $workSeconds = $attendances->reduce(function ($carry, $a) {
                $checkIn = Carbon::parse($a->date . ' ' . $a->check_in);
                $checkOut = Carbon::parse($a->date . ' ' . $a->check_out);
                return $carry + $checkOut->diffInSeconds($checkIn);
            }, 0);

            $hours = floor($workSeconds / 3600);
            $minutes = floor(($workSeconds % 3600) / 60);
            $seconds = $workSeconds % 60;
            $workTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            // Hitung total on time
            $onTime = Attendance::where('user_id', $userId)
                ->where('status', 'onTime')
                ->whereDate('date', '>=', now()->subDays(30))
                ->count();

            return [
                'user_id' => $userId,
                'fullname' => User::find($userId)->fullname ?? '-',
                'total_checkin' => $item->total_checkin,
                'total_ontime' => $onTime,
                'total_worktime' => $workTime,
                'total_work_seconds' => $workSeconds, // untuk sorting nanti
            ];
        })
            // SORTING: total_checkin > total_ontime > total_work_seconds
            ->sort(function ($a, $b) {
                if ($a['total_checkin'] === $b['total_checkin']) {
                    if ($a['total_ontime'] === $b['total_ontime']) {
                        return $b['total_work_seconds'] <=> $a['total_work_seconds'];
                    }
                    return $b['total_ontime'] <=> $a['total_ontime'];
                }
                return $b['total_checkin'] <=> $a['total_checkin'];
            })
            ->values() // reset index
            ->take(5); // ambil top 5

        // TODAY STATISTICS
        $today = now()->toDateString();
        $todayStats = [
            'onTime' => Attendance::whereDate('date', $today)->where('status', 'onTime')->count(),
            'Late' => Attendance::whereDate('date', $today)->where('status', 'Late')->count(),
            'permission' => Attendance::whereDate('date', $today)->where('permission', '1')->count(),
        ];

        // MONTH STATISTICS
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $monthOnTime = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])->where('status', 'onTime')->count();
        $monthLate = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])->where('status', 'late')->count();
        $monthPermission = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])->where('status', 'permission')->count();

        $monthPresent = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])->count();
        $totalDays = now()->daysInMonth;
        $totalExpected = $totalEmployees * $totalDays;
        $monthAbsent = $totalExpected - $monthPresent;

        $monthStats = [
            'onTime' => $monthOnTime,
            'late' => $monthLate,
            'permission' => $monthPermission,
            'absent' => $monthAbsent
        ];

        // MONTHLY CHART (Rekap per bulan, seluruh karyawan)
        $months = range(1, 12);
        $onTimeMonthly = [];
        $lateMonthly = [];
        $permissionMonthly = [];

        foreach ($months as $month) {
            $onTimeMonthly[] = Attendance::whereMonth('date', $month)
                ->whereYear('date', now()->year)
                ->where('status', 'onTime') // konsisten huruf besar kecil
                ->count();

            $lateMonthly[] = Attendance::whereMonth('date', $month)
                ->whereYear('date', now()->year)
                ->where('status', 'late')
                ->count();

            $permissionMonthly[] = Attendance::whereMonth('date', $month)
                ->whereYear('date', now()->year)
                ->where('permission', '1')
                ->count();
        }

        return view('management_system.dashboardWeb', compact(
            'attendances',
            'totalEmployees',
            'roles',
            'topAttendances',
            'topUsers',
            'todayStats',
            'monthStats',
            'onTimeMonthly',
            'lateMonthly',
            'permissionMonthly'
        ));
    }


    public function index()
    {
        $user = Auth::user();

        // Ambil shift user
        $shift = $user->shift; // Pastikan ada relasi/field shift di tabel user

        // Tentukan jam kerja berdasarkan shift
        if ($shift == 'shift-1') {
            $workingHours = '08.00 am - 04.00 pm';
        } elseif ($shift == 'shift-2') {
            $workingHours = '02.00 pm - 09.00 pm';
        } else {
            $workingHours = '08.00 am - 04.00 pm'; // Default
        }


        // Ambil data time track (misal dari tabel attendance)
        // Contoh: hitung jumlah status per hari dalam seminggu terakhir
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $attendanceData = \App\Models\Attendance::where('user_id', $user->id)
            ->whereYear('date', now()->year) // filter tahun sekarang
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->format('M'); // contoh: Jan, Feb, dst
            });


        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $onTime = [];
        $late = [];
        $permission = [];

        foreach ($months as $month) {
            $records = $attendanceData->get($month, collect());
            $onTime[] = $records->where('status', 'onTime')->count();
            $late[] = $records->where('status', 'Late')->count();
            $permission[] = $records->where('permission', 1)->count();
        }

        //tahun saat ini
        $yearRealtime = now()->year;


        return view('pwa.dashboard', compact(
            'user',
            'workingHours',
            'onTime',
            'late',
            'permission',
            'yearRealtime',
        ));
    }
}
