<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Attendance;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.attendance-report');
    }

    public function exportPDF(Request $request)
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

        $records = Attendance::whereMonth('check_in', $monthNumber)
            ->whereYear('check_in', $year)
            ->with(['user'])
            ->get();

        $groupedRecords = $this->processAttendanceData($records);

        $pdf = PDF::loadView('pdf.attendance-report', [
            'records' => $groupedRecords,
            'month' => $selectedMonth,
            'year' => $year,
            'totalWorkingDays' => $this->getWorkingDaysInMonth($monthNumber, $year)
        ]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download("attendance_report_{$selectedMonth}_{$year}.pdf");
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
        $records = Attendance::whereMonth('check_in', $monthNumber)
            ->whereYear('check_in', $year)
            ->with(['user'])
            ->get();
        $groupedRecords = $this->processAttendanceData($records);
        return response()->json([
            'records' => $groupedRecords,
            'month' => $selectedMonth,
            'year' => $year
        ]);
    }
}
