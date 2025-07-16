@extends('management_system.templates.layouts')
@section('content')
    <!-- Header -->
    <div class="bg-blue-500 text-white p-4 rounded-lg">
        <h1 class="text-xl font-semibold">Dashboard</h1>
    </div>

    <!-- Welcome Section -->
    <div class="flex flex-row justify-between items-center">
        <div>
            <h2 class="text-3xl text-pink-600 italic font-semibold">
                Hello, <span class="text-black not-italic">Admin!</span>
            </h2>
        </div>

        <div class="rounded-full bg-black p-3" onclick="window.location.href='{{ url('/notificationWeb') }}'">
            <svg width="24" height="24" viewBox="0 0 26 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M25.4534 21.4031C25.3411 21.2648 25.2308 21.1264 25.1226 20.9928C23.6343 19.1515 22.7338 18.0402 22.7338 12.8276C22.7338 10.129 22.1026 7.91469 20.8585 6.25397C19.9412 5.02712 18.7011 4.09643 17.0667 3.40862V3.40862C17.025 3.38491 17.0044 3.33823 16.9903 3.29239C16.3859 1.31751 14.7935 0 13.0002 0C11.2064 0 9.61435 1.31809 9.01028 3.29171C8.99655 3.33659 8.97579 3.38193 8.935 3.40516V3.40516C5.12082 5.01121 3.26718 8.09252 3.26718 12.8256C3.26718 18.0402 2.36809 19.1515 0.878414 20.9907C0.770172 21.1243 0.6599 21.2599 0.547599 21.4011C0.257511 21.7589 0.073716 22.1942 0.0179662 22.6556C-0.0377836 23.1169 0.0368451 23.5849 0.23302 24.0042C0.650429 24.9038 1.54004 25.4622 2.55549 25.4622H23.4523C24.463 25.4622 25.3465 24.9045 25.7653 24.0091C25.9623 23.5897 26.0376 23.1213 25.9824 22.6594C25.9271 22.1975 25.7435 21.7616 25.4534 21.4031ZM13.0002 31C13.9778 30.9992 14.9369 30.7278 15.7759 30.2145C16.6149 29.7013 17.3024 28.9653 17.7655 28.0848C17.7873 28.0426 17.7981 27.9953 17.7968 27.9476C17.7955 27.8999 17.7821 27.8533 17.758 27.8124C17.7339 27.7715 17.6999 27.7377 17.6593 27.7143C17.6186 27.6908 17.5727 27.6785 17.526 27.6786H8.47565C8.42892 27.6784 8.38295 27.6906 8.3422 27.714C8.30146 27.7374 8.26733 27.7712 8.24315 27.8121C8.21897 27.853 8.20555 27.8996 8.20421 27.9474C8.20287 27.9952 8.21364 28.0425 8.23549 28.0848C8.69859 28.9652 9.386 29.7011 10.2248 30.2143C11.0637 30.7276 12.0227 30.9991 13.0002 31Z"
                    fill="#F5FAFF" />
            </svg>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="statistics flex flex-col items-center gap-4">
        <h3 class="text-xl text-gray-600 text-center font-semibold">Employee Attendance Statistics</h3>

        <!-- Charts Container -->
        <div class="flex flex-row gap-6 w-full">
            <!-- Today Chart -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex-1">
                <h4 class="text-lg font-semibold mb-4">Today</h4>
                <canvas id="todayChart" width="400" height="200"></canvas>
            </div>

            <!-- Month Chart -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex-1">
                <h4 class="text-lg font-semibold mb-4">Month</h4>
                <canvas id="monthChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Cards Section -->
    <div class="flex flex-row justify-between">
        <!-- Top 5 Attendance -->
        <div class="bg-white flex flex-col w-[450px] h-auto gap-4 items-start p-6 rounded-2xl shadow-lg">
            <h3 class="text-blue-500 text-xl font-bold">
                Top 5 <span class="font-normal text-base">Attendance Records</span>
            </h3>

            <div class="w-full space-y-3">
                @foreach ($topAttendances as $i => $top)
                    <div class="flex flex-row w-full justify-between items-center py-2">
                        <span class="text-base text-black font-medium">
                            {{ $i + 1 }}. {{ $top->user->fullname ?? '-' }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ $top->total_checkin }} days
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Employee Count -->
        <div class="bg-white flex flex-col gap-4 w-[280px] h-auto items-center justify-between p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg text-blue-500 font-semibold">Number of employees</h3>

            <div class="flex flex-col items-center">
                <span class="text-black text-4xl font-bold">{{ $totalEmployees }}</span>
                <span class="text-black text-base">Employees</span>
            </div>

            <div class="flex flex-col w-full gap-3">
                <div class="flex flex-row w-full justify-between items-center">
                    <span class="text-black text-sm">Laravel Developer</span>
                    <span class="text-gray-500 text-sm">{{ $laravelCount }}/{{ $totalEmployees }}</span>
                </div>
                <div class="flex flex-row w-full justify-between items-center">
                    <span class="text-black text-sm">Fullstack Developer</span>
                    <span class="text-gray-500 text-sm">{{ $fullstackCount }} / {{ $totalEmployees }}</span>
                </div>
                <div class="flex flex-row w-full justify-between items-center">
                    <span class="text-black text-sm">Copy Writer</span>
                    <span class="text-gray-500 text-sm">{{ $copywriterCount }} / {{ $totalEmployees }}</span>
                </div>
            </div>
        </div>

        <!-- Overtime -->
        <div class="bg-white flex flex-col gap-4 items-center justify-between p-6 rounded-2xl shadow-lg min-w-[220px]">
            <h3 class="text-2xl text-red-500 font-bold">Overtime</h3>
            <div class="text-4xl font-bold text-black text-center pb-30">{{ $totalWorkTime }}</div>
            <div class="text-xs text-gray-500 text-center">Total working hours of all employees</div>
        </div>
    </div>

    <!-- Employee Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-lg font-semibold text-white">Employee</th>
                        <th class="px-6 py-4 text-left text-lg font-semibold text-white">Check In</th>
                        <th class="px-6 py-4 text-left text-lg font-semibold text-white">Check Out</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-800 font-medium">
                                {{ $attendance->user->fullname ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $attendance->check_in ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $attendance->check_out ?? '-' }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


{{-- <script>
        // Today Chart
        const todayCtx = document.getElementById('todayChart').getContext('2d');
        const todayChart = new Chart(todayCtx, {
            type: 'bar',
            data: {
                labels: ['Morning', 'Afternoon', 'Evening', 'Absent'],
                datasets: [{
                    data: [250, 150, 100, 50],
                    backgroundColor: ['#3B82F6', '#EC4899', '#06B6D4', '#EF4444'],
                    borderRadius: 8,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 300,
                        ticks: {
                            stepSize: 50
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Month Chart
        const monthCtx = document.getElementById('monthChart').getContext('2d');
        const monthChart = new Chart(monthCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Present',
                        data: [300, 280, 320, 310, 290, 330, 350, 340, 300, 310, 290, 320],
                        backgroundColor: '#10B981',
                        stack: 'stack1'
                    },
                    {
                        label: 'Late',
                        data: [50, 60, 40, 45, 55, 35, 30, 40, 50, 45, 60, 40],
                        backgroundColor: '#F59E0B',
                        stack: 'stack1'
                    },
                    {
                        label: 'Absent',
                        data: [20, 25, 15, 20, 30, 20, 15, 20, 25, 20, 25, 15],
                        backgroundColor: '#EF4444',
                        stack: 'stack1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 400,
                        stacked: true
                    },
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Update overtime timer
        function updateOvertime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            // This is just a demo - in real app you'd calculate actual overtime
            document.querySelector('.text-4xl.font-bold.text-black').textContent = `${hours} : ${minutes} : ${seconds}`;
        }

        // Update every second
        setInterval(updateOvertime, 1000);
    </script> --}}
