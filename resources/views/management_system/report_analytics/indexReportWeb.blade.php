@extends('management_system.templates.layouts')
@section('content')
    <!-- Header -->
    <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
        <h1 class="text-xl font-semibold">Report & Analytics</h1>
    </div>

    <!-- Report Section -->
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Report</h2>
        <div class="flex gap-4">
            <button onclick="window.location.href='{{ url('/attedanceReport') }}'"
                class="flex items-center justify-center w-[250px] gap-2 px-6 py-8 rounded-lg bg-gradient-to-r from-pink-400 to-blue-500 text-white font-medium shadow-lg hover:opacity-90">
                <i class="fa-solid fa-file text-white"></i>
                <span class="text-lg">Attendance Report</span>
            </button>

            <button onclick="window.location.href='{{ url('/payrollReport') }}'"
                class="flex items-center justify-center w-[250px] gap-2 px-6 py-8 rounded-lg bg-gradient-to-r from-pink-400 to-purple-500 text-white font-medium shadow-lg hover:opacity-90">
                <i class="fa-solid fa-file-invoice-dollar text-white"></i>
                <span class="text-lg">Payroll Report</span>
            </button>
        </div>
    </div>

    <!-- Analytics Section -->
    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Analytics</h2>
        <!-- Filter Bulan -->
        <div class="col-span-1 lg:col-span-2">
            <form method="GET" action="{{ route('report.indexWeb') }}" class="flex items-center gap-2 mb-4">
                <label for="month" class="text-sm text-gray-600">Sort by:</label>
                <select name="month" id="month" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Line Chart Placeholder -->
            <div class="bg-white p-4 rounded-xl shadow-md">
                

                <!-- Replace this with actual chart -->
                <div class="h-[250px] w-full bg-gray-100 flex items-center justify-center rounded-md">
                    {{-- <span class="text-gray-400">[ Line Chart Placeholder ]</span> --}}
                    <div class="h-[250px] w-full bg-white">
                        <canvas id="divisiChart" height="250"></canvas>
                    </div>
                </div>
                <div class="flex justify-center gap-4 mt-4 text-sm">
                    <div class="flex items-center gap-1 text-pink-600"><span
                            class="w-3 h-3 bg-pink-600 rounded-full"></span> Fullstack Dev</div>
                    <div class="flex items-center gap-1 text-sky-400"><span class="w-3 h-3 bg-sky-400 rounded-full"></span>
                        Writer</div>
                    <div class="flex items-center gap-1 text-purple-500"><span
                            class="w-3 h-3 bg-purple-500 rounded-full"></span> Laravel Dev</div>
                </div>
            </div>

            <!-- Pie Chart Placeholder -->
            <!-- Pie Chart Section -->
            <div class="bg-white p-4 rounded-xl shadow-md flex flex-col items-center justify-center">
                <p class="font-semibold text-gray-700 mb-2">Average Attendance</p>
                <div class="h-[200px] w-[200px]">
                    <canvas id="pieChart" width="200" height="200"></canvas>
                </div>

                <div class="mt-4 text-sm space-y-2 w-full px-4">
                    <div class="flex items-center justify-between">
                        <div class="flex gap-2 items-center">
                            <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                            <span>Present</span>
                        </div>
                        <span class="text-blue-500 font-semibold">{{ $percentage['onTime'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex gap-2 items-center">
                            <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                            <span>Late</span>
                        </div>
                        <span class="text-red-500 font-semibold">{{ $percentage['late'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex gap-2 items-center">
                            <span class="w-3 h-3 bg-pink-400 rounded-full"></span>
                            <span>Absent</span>
                        </div>
                        <span class="text-pink-400 font-semibold">{{ $percentage['absent'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex gap-2 items-center">
                            <span class="w-3 h-3 bg-green-400 rounded-full"></span>
                            <span>Permission</span>
                        </div>
                        <span class="text-green-500 font-semibold">{{ $percentage['permission'] }}%</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('divisiChart').getContext('2d');

        const data = {
            labels: @json($labels),
            datasets: @json($datasets)
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Attendance Division - Month {{ $monthName }}',
                    font: {
                        size: 16
                    }
                }
            }
        };

        new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });

        // Pie Chart for Average Attendance
        // Pie Chart for Average Attendance
        const pieData = {
            labels: ['Present', 'Late', 'Absent', 'Permission'],
            datasets: [{
                data: [{{ $percentage['onTime'] }}, {{ $percentage['late'] }}, {{ $percentage['absent'] }}, {{ $percentage['permission'] }}],
                backgroundColor: ['#3b82f6', '#ef4444', '#ec4899', '#22c55e']
            }]
        };

        const pieCtx = document.getElementById('pieChart').getContext('2d');

        new Chart(pieCtx, {
            type: 'pie',
            data: pieData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // hide default legend, pakai custom yg di HTML
                    },
                    title: {
                        display: false
                    }
                }
            }
        });
    });
</script>

