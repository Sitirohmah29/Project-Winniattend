{{-- filepath: resources/views/management_system/report_analytics/payroll-report-pdf.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Payroll Report PDF</title>
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
    <h2>Payroll Report - {{ $month }}/{{ $year }}</h2>
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Position</th>
                <th>Daily Salary</th>
                <th>Number of working days</th>
                <th>Total Salary</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payrollData as $row)
                <tr>
                    <td>{{ $row['fullname'] }}</td>
                    <td>{{ $row['position'] }}</td>
                    <td>{{ number_format($row['salary_perday'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['workingDays'], 0, ',', '.') }}</td>
                    <td>{{ number_format($row['totalSalary'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
