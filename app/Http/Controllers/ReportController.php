<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{

    public function indexReport(Request $request)
    {
        $user = auth()->user();

        // Ambil parameter sort dari query string
        $orderDirection = $request->get('sort', 'desc'); // 'asc' atau 'desc'

        $attendances = \App\Models\Attendance::where('user_id', $user->id)
            ->orderBy('date', $orderDirection)
            ->take(30)
            ->get();

        // Face ID status
        $faceIdStatus = $attendances->map(function ($a) {
            if ($a->check_in) {
                $a->faceIdStatus = 'Success';
            } elseif ($a->permission) {
                $a->faceIdStatus = 'Permission';
            } else {
                $a->faceIdStatus = ucfirst($a->status_label);
            }
            return $a;
        });

        // Summary kehadiran
        $presentDays = $attendances->filter(fn($a) => $a->check_in !== null)->count();

        // Hitung keterlambatan
        $lateCount = $attendances->filter(function ($a) use ($user) {
            if (!$a->check_in) return false;

            $shiftStart = $user->shift === 'shift-2' ? '14:00:00' : '08:00:00';
            $checkInTime = \Carbon\Carbon::parse($a->check_in)->format('H:i:s');

            return $checkInTime > $shiftStart;
        })->count();

        $totalWorkMinutes = $attendances->sum(function ($a) {
            if ($a->check_in && $a->check_out) {
                return \Carbon\Carbon::parse($a->check_in)->diffInMinutes(\Carbon\Carbon::parse($a->check_out));
            }
            return 0;
        });

        return view('pwa.report.indexReport', compact(
            'attendances',
            'user',
            'presentDays',
            'lateCount',
            'totalWorkMinutes',
            'orderDirection', // diganti dari 'filterDate' agar lebih deskriptif
        ));
    }


    public function detailsReport($id)
    {
        $attendance = \App\Models\Attendance::with('user')->findOrFail($id);

        // Hitung jam kerja
        $workMinutes = ($attendance->check_in && $attendance->check_out)
            ? \Carbon\Carbon::parse($attendance->check_in)->diffInMinutes(\Carbon\Carbon::parse($attendance->check_out))
            : 0;
        $workHours = $workMinutes ? floor($workMinutes / 60) . 'h ' . ($workMinutes % 60) . 'm' : '-';

        return view('pwa.report.detailsReport', compact('attendance', 'workHours'));
    }


    //MANAGEMENT SYSTEM REPORTS
    public function ReportWeb(Request $request)
    {
        // === Perhitungan kehadiran perdivisi (Pie Chart) ===
        $month = $request->input('month', now()->month); // default: bulan sekarang
        $year = now()->year;

        $roles = ['Fullstack Developer', 'Copy Writer', 'Laravel Developer'];
        $colors = [
            'Fullstack Developer' => '#db2777', // pink
            'Copy Writer' => '#0ea5e9',         // sky
            'Laravel Developer' => '#8b5cf6'    // purple
        ];

        $datasets = [];

        foreach ($roles as $role) {
            $userIds = User::whereHas('role', fn($q) => $q->where('name', $role))->pluck('id');

            $onTime = Attendance::whereIn('user_id', $userIds)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'onTime')
                ->count();

            $late = Attendance::whereIn('user_id', $userIds)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'Late')
                ->count();

            $permission = Attendance::whereIn('user_id', $userIds)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('permission', true)
                ->count();

            $datasets[] = [
                'label' => $role,
                'data' => [$onTime, $late, $permission],
                'borderColor' => $colors[$role],
                'backgroundColor' => $colors[$role] . '33',
                'tension' => 0.4,
            ];
        }

        $labels = ['On Time', 'Late', 'Permission'];
        $monthName = Carbon::create()->month($month)->format('F');

        // === Perhitungan rata-rata attendance (Pie Chart) ===
        $totalDays = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->distinct('date')
            ->count('date');

        $totalUsers = User::count();
        $totalPossibleAttendance = $totalDays * $totalUsers;

        $totalOnTime = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'onTime')
            ->count();

        $totalLate = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'Late')
            ->count();

        $totalPermission = Attendance::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('permission', true)
            ->count();

        $totalAttendance = $totalOnTime + $totalLate + $totalPermission;
        $totalAbsent = $totalPossibleAttendance - $totalAttendance;

        // Hitung persentase
        $percentage = [
            'onTime' => $totalPossibleAttendance ? round(($totalOnTime / $totalPossibleAttendance) * 100) : 0,
            'late' => $totalPossibleAttendance ? round(($totalLate / $totalPossibleAttendance) * 100) : 0,
            'permission' => $totalPossibleAttendance ? round(($totalPermission / $totalPossibleAttendance) * 100) : 0,
            'absent' => $totalPossibleAttendance ? round(($totalAbsent / $totalPossibleAttendance) * 100) : 0,
        ];


        return view('management_system.report_analytics.indexReportWeb', compact('datasets', 'labels', 'month', 'monthName', 'percentage'));
    }

    public function exportPDF(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $users = User::with(['attendances' => function ($query) use ($month, $year) {
            $query->whereMonth('date', $month)
                ->whereYear('date', $year);
        }])->get();

        $reportData = $users->map(function ($user) {
            $onTime = 0;
            $late = 0;
            $permission = 0;
            $workDuration = 0; // in hours
            $workingDays = 0;

            foreach ($user->attendances as $attendance) {
                // Hitung permission
                if ($attendance->status_label === 'permission') {
                    $permission++;
                    continue;
                }
                if ($attendance->status == \App\Models\Attendance::STATUS_PERMISSION) {
                    $permission++;
                    continue;
                }
                // Hitung onTime & Late
                if ($attendance->check_in && $user->shift_start) {
                    $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                    $shiftStart = \Carbon\Carbon::createFromFormat('H:i:s', $user->shift_start);

                    if ($checkIn->lessThanOrEqualTo($shiftStart)) {
                        $onTime++;
                    } else {
                        $late++;
                    }
                }
                // Hitung working day
                $workingDays++;
                // Hitung durasi kerja (hanya jika ada check_out)
                if ($attendance->check_in && $attendance->check_out) {
                    $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                    $checkOut = \Carbon\Carbon::parse($attendance->check_out);
                    $duration = $checkOut->diffInMinutes($checkIn) / 60; // convert to hours
                    $workDuration += $duration;
                }
            }

            return [
                'user' => $user,
                'totalDays' => $workingDays,
                'onTime' => $onTime,
                'late' => $late,
                'absent' => 0, // tambahkan logika jika perlu
                'permission' => $permission,
                'overtime' => $user->attendances->sum('overtime_hours') ?? 0,
                'workDuration' => round($workDuration, 1),
            ];
        });


        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('management_system.report_analytics.attendance-report', [
            'reportData' => $reportData,
            'month' => $month,
            'year' => $year
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download("attendance_report_{$month}_{$year}.pdf");
    }

    private function processAttendanceData($records)
    {
        $processed = [];
        foreach ($records as $record) {
            $userId = $record->user_id;
            if (!isset($processed[$userId])) {
                $processed[$userId] = [
                    'user' => $record->user,
                    'total_days' => 0,
                    'on_time' => 0,
                    'late' => 0,
                    'absent' => 0,
                    'permission' => 0,
                    'total_overtime' => 0,
                    'total_work_duration' => 0
                ];
            }
            $processed[$userId]['total_days']++;
            if ($record->status === 'present') {
                if ($record->check_in_time <= '09:00:00') {
                    $processed[$userId]['on_time']++;
                } else {
                    $processed[$userId]['late']++;
                }
            } elseif ($record->status === 'absent') {
                $processed[$userId]['absent']++;
            } elseif ($record->status === 'permission') {
                $processed[$userId]['permission']++;
            }
            if ($record->check_in_time && $record->check_out_time) {
                $workDuration = Carbon::parse($record->check_out_time)
                    ->diffInMinutes(Carbon::parse($record->check_in_time));
                $processed[$userId]['total_work_duration'] += $workDuration;
                if ($workDuration > 480) {
                    $processed[$userId]['total_overtime'] += ($workDuration - 480);
                }
            }
        }
        foreach ($processed as &$data) {
            $data['total_work_duration'] = round($data['total_work_duration'] / 60, 1) . ' hours';
            $data['total_overtime'] = round($data['total_overtime'] / 60, 1) . ' hours';
        }
        return $processed;
    }

    private function getWorkingDaysInMonth($month, $year)
    {
        return Carbon::create($year, $month)->daysInMonth;
    }

    public function getAttendanceData(Request $request)
    {
        $monthParam = $request->get('month', now()->format('F'));
        if (is_numeric($monthParam)) {
            $monthNumber = (int)$monthParam;
            $selectedMonth = Carbon::create()->month($monthNumber)->format('F');
        } else {
            $selectedMonth = $monthParam;
            $monthNumber = Carbon::parse($selectedMonth)->month;
        }
        $year = $request->get('year', now()->year);
        $records = Attendance::whereMonth('date', $monthNumber)
            ->whereYear('date', $year)
            ->with(['user'])
            ->get();
        $groupedRecords = $this->processAttendanceData($records);
        return response()->json([
            'records' => $groupedRecords,
            'month' => $selectedMonth,
            'year' => $year
        ]);
    }

    public function attendanceReport(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $users = User::with(['attendances' => function ($query) use ($month, $year) {
            $query->whereMonth('date', $month)
                ->whereYear('date', $year);
        }])->get();

        $reportData = $users->map(function ($user) {
            $onTime = 0;
            $late = 0;
            $permission = 0;
            $workDuration = 0; // in hours
            $workingDays = 0;

            foreach ($user->attendances as $attendance) {
                // Hitung permission
                if ($attendance->status_label === 'permission') {
                    $permission++;
                    continue;
                }

                if ($attendance->status == Attendance::STATUS_PERMISSION) {
                    $permission++;
                    continue;
                }
                // Hitung onTime & Late
                if ($attendance->check_in && $user->shift_start) {
                    $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                    $shiftStart = \Carbon\Carbon::createFromFormat('H:i:s', $user->shift_start);

                    if ($checkIn->lessThanOrEqualTo($shiftStart)) {
                        $onTime++;
                    } else {
                        $late++;
                    }
                }

                // Hitung working day
                $workingDays++;

                // Hitung durasi kerja (hanya jika ada check_out)
                if ($attendance->check_in && $attendance->check_out) {
                    $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                    $checkOut = \Carbon\Carbon::parse($attendance->check_out);
                    $duration = $checkOut->diffInMinutes($checkIn) / 60; // convert to hours
                    $workDuration += $duration;
                }
            }

            return [
                'user' => $user,
                'totalDays' => $workingDays,
                'onTime' => $onTime,
                'late' => $late,
                'absent' => 0, // kamu bisa tambahkan logika ini nanti
                'permission' => $permission,
                'overtime' => $user->attendances->sum('overtime_hours') ?? 0,
                'workDuration' => round($workDuration, 1),
            ];
        });

        return view('management_system.report_analytics.attedanceReport', compact('reportData', 'month', 'year'));
    }


    public function payrollReport(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Ambil semua user beserta role dan attendance bulan ini
        $users = \App\Models\User::with(['role', 'attendances' => function ($q) use ($month, $year) {
            $q->whereMonth('date', $month)
                ->whereYear('date', $year);
        }])->get();

        $payrollData = $users->map(function ($user) {
            $salary = $user->role->salary ?? 0;
            $absent = $user->attendances->where('status', 'absent')->count();
            $alphaDeduction = $absent * 100000; // contoh: potongan 100rb/hari alpha
            $totalSalary = $salary - $alphaDeduction;

            return [
                'fullname' => $user->fullname,
                'position' => $user->role->name ?? '-',
                'salary' => $salary,
                'alphaDeduction' => $alphaDeduction,
                'totalSalary' => $totalSalary,
            ];
        });

        return view('management_system.report_analytics.payrollReport', compact('payrollData', 'month', 'year'));
    }


    public function exportPayrollPDF(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $users = \App\Models\User::with(['role', 'attendances' => function ($q) use ($month, $year) {
            $q->whereMonth('date', $month)
                ->whereYear('date', $year);
        }])->get();

        $payrollData = $users->map(function ($user) {
            $salary = $user->role->salary ?? 0;
            $absent = $user->attendances->where('status', 'absent')->count();
            $alphaDeduction = $absent * 100000;
            $totalSalary = $salary - $alphaDeduction;

            return [
                'fullname' => $user->fullname,
                'position' => $user->role->name ?? '-',
                'salary' => $salary,
                'alphaDeduction' => $alphaDeduction,
                'totalSalary' => $totalSalary,
            ];
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('management_system.report_analytics.payroll-report-pdf', [
            'payrollData' => $payrollData,
            'month' => $month,
            'year' => $year
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download("payroll_report_{$month}_{$year}.pdf");
    }
    public function indexReportAttendance(Request $request)
    {
        $month = $request->query('month', date('n'));
        $year = $request->query('year', date('Y'));

        $attendances = Attendance::with('user.role')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $reportData = [];
        $users = $attendances->groupBy('user_id');

        foreach ($users as $userId => $attens) {
            $user = $attens->first()->user;
            $totalDays = $attens->count();

            $onTime = $attens->where('status', 'onTime')->count();
            $late = $attens->where('status', 'late')->count();
            $absent = $attens->where('status', 'absent')->count();

            // Ubah cara menghitung permission - gunakan kolom permission (tinyint)
            $permission = $attens->where('permission', 1)->count();

            $totalDurationMinutes = $attens->reduce(function ($carry, $item) {
                if ($item->check_in && $item->check_out) {
                    $in = Carbon::parse($item->check_in);
                    $out = Carbon::parse($item->check_out);
                    return $carry + $in->diffInMinutes($out);
                }
                return $carry;
            }, 0);

            $workDurationHours = round($totalDurationMinutes / 60, 2);

            $reportData[] = [
                'user' => $user,
                'totalDays' => $totalDays,
                'onTime' => $onTime,
                'late' => $late,
                'absent' => $absent,
                'permission' => $permission,
                'workDuration' => $workDurationHours,
            ];
        }

        return view('management_system.report_analytics.attendanceReport', compact('reportData'));
    }
}
