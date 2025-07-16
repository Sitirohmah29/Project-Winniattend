{{-- filepath: resources/views/pdf/attendance-report.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Attendance Report PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: center;
        }

        th {
            background: #2563eb;
            color: #fff;
        }
    </style>
</head>

<body>
    <h2>Attendance Report - {{ $month }}/{{ $year }}</h2>
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Position</th>
                <th>Number of working days</th>
                <th>On Time</th>
                <th>Late</th>
                <th>Absent</th>
                <th>Permission</th>
                <th>Total Work Duration</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData as $report)
                <tr>
                    <td>{{ $report['user']->fullname }}</td>
                    <td>{{ $report['user']->role->name ?? '-' }}</td>
                    <td>{{ $report['totalDays'] }}</td>
                    <td>{{ $report['onTime'] }}</td>
                    <td>{{ $report['late'] }}</td>
                    <td>{{ $report['absent'] }}</td>
                    <td>{{ $report['permission'] }}</td>
                    <td>{{ $report['workDuration'] }} hours</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
