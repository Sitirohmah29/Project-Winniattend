<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>WinniAttend - Dashboard</title>

    <!-- PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="WinniAttend">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')
    <!-- Alpine.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body class="bg-gray-50 font-poppins">
    <div class="px-2 py-2">
        <!-- Welcome Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-xl font-semibold text-pink-400">Welcome!</h1>
                <p class="text-gray-800 font-medium">Risma Handayani</p>
            </div>
            <div class="bg-gray-800 text-white rounded-full p-2 w-7 h-7 flex items-center justify-center">
                <a href="{{url('/notification')}}"><i class="fa fa-bell"></i></a>
            </div>
        </div>

        <!-- Time Card -->
        <div class="bg-blue-500 text-white rounded-xl p-2 mb-4">
            <div class="text-center mb-1">
                <h2 class="text-xl font-semibold">12.14</h2>
                <p class="text-sm font-thin">Monday, 03 March 2025</p>
                <p class="text-sm font-thin">Your Working hours are 01.00 am - 06.00 am</p>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-4">
               <button type="button" onclick="window.location.href='{{ url('/attendance/check-in') }}'"
               class="bg-white text-black py-2 px-4 rounded-full text-xs font-semibold hover:bg-black hover:text-blue-400">
               Check In
            </button>
            
            <button class="bg-white text-black py-2 rounded-full text-xs font-semibold hover:bg-black hover:text-blue-400" onclick="window.location.href='{{ url('/attendance/check-out') }}'">
                Check Out
            </button>
        </div>
        
        <button class="w-full bg-black text-white py-2 rounded-full mt-3 text-xs font-semibold hover:bg-white hover:text-pink-400">
            Permission
        </button>
    </div>

        <!-- Time Track -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-3">Time Track</h2>
            <div class="bg-white rounded-xl p-4 shadow-sm">
                <div class="h-48">
                    <canvas id="timeTrackChart"></canvas>
                </div>

                <div class="flex justify-between mt-3">
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                            <span class="text-xs text-gray-600">On Time</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="text-xs text-gray-600">Late</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-gray-800"></span>
                            <span class="text-xs text-gray-600">Absent</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                            <span class="text-xs text-gray-600">Permission</span>
                        </div>
                    </div>
                    <button class="bg-blue-500 text-white text-xs py-1 px-3 rounded-full" onclick="window.location.href='{{url('/indexReport')}}'">
                        See Full Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Announcements -->
        <div class="mb-6">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-semibold text-gray-800">Announcements</h2>
                <a href="#" class="text-blue-500 text-sm">See all &gt;</a>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm overflow-x-auto">
                <div class="flex space-x-4 min-w-max">
                    <div class="w-64 h-20 flex-shrink-0">
                        <img src="{{ asset('images/announcement.png') }}" alt="Important Announcement" class="w-full h-full object-cover">
                    </div>
                    <div class="w-64 h-20 flex-shrink-0">
                        <img src="{{ asset('images/announcement.png') }}" alt="Important Announcement" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <div class="fixed bottom-6 left-0 right-0 flex justify-center">
            <div class="bg-white rounded-full shadow-lg px-6 py-2 flex space-x-8">
                <a href="#" class="text-blue-500">
                    <i class="fa fa-home text-xl"></i>
                </a>
                <a href="{{url('/indexProfile')}}" class="text-gray-400">
                    <i class="fa fa-user text-xl"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart.js Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Time Track Chart
            const ctx = document.getElementById('timeTrackChart').getContext('2d');

            // Days of the week
            const labels = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];

            // Chart data
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'On Time',
                        data: [2, 5, 3, 6, 4, 8, 7],
                        borderColor: 'rgb(74, 222, 128)',
                        backgroundColor: 'rgba(74, 222, 128, 0.1)',
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(74, 222, 128)'
                    },
                    {
                        label: 'Late',
                        data: [6, 4, 7, 3, 6, 2, 4],
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(239, 68, 68)'
                    },
                    {
                        label: 'Permission',
                        data: [3, 2, 4, 5, 2, 4, 6],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(59, 130, 246)'
                    },
                    {
                        label: 'Absent',
                        data: [1, 3, 2, 4, 7, 5, 3],
                        borderColor: 'rgb(31, 41, 55)',
                        backgroundColor: 'rgba(31, 41, 55, 0.1)',
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(31, 41, 55)'
                    }
                ]
            };

            // Chart options
            const options = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10,
                        ticks: {
                            stepSize: 2
                        },
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            };

            // Create the chart
            const timeTrackChart = new Chart(ctx, {
                type: 'line',
                data: data,
                options: options
            });
        });
    </script>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function(error) {
                        console.log('ServiceWorker registration failed: ', error);
                    });
            });
        }
    </script>
</body>
</html>
