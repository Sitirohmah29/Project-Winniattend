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

    <style>
        .popup-overlay {
            transition: all 0.3s ease;
        }

        .popup-overlay.show {
            display: flex !important;
        }

        .popup-content {
            transform: scale(0.8) translateY(-20px);
            transition: all 0.3s ease;
        }

        .popup-overlay.show .popup-content {
            transform: scale(1) translateY(0);
        }
    </style>

</head>

<body class="bg-[#F5FAFF] font-poppins">
    <div class="px-2 py-2">
        <!-- Welcome Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-lg font-semibold text-pink-400">Welcome!</h1>
                <p class="text-xl text-gray-800 font-medium">{{ $user->fullname }}</p>
            </div>
            <div class="bg-gray-800 text-white rounded-full p-2 w-7 h-7 flex items-center justify-center">
                <a href="{{ url('/notification') }}"><i class="fa fa-bell"></i></a>
            </div>
        </div>

        <!-- Time Card -->
        <div id="infoPersonal" class="bg-blue-500 text-white rounded-xl p-2 mb-4">
            <div class="text-center mb-1">
                <h2 id="Time" class="text-xl font-semibold">12.14</h2>
                <div class="flex flex-row gap-1 items-center justify-center">
                    <p id="Day" class="text-sm font-thin"></p>
                    <p>,</p>
                    <p id="Date" class="text-sm font-thin"></p>
                </div>
                <p class="text-sm font-thin">Your Working hours are {{ $workingHours }}</p>
            </div>

            <script>
                // Fungsi untuk menambahkan angka 0 di depan angka < 10
                function pad(number) {
                    return number < 10 ? '0' + number : number;
                }

                function infoPersonal() {
                    const now = new Date();

                    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                    const hari = days[now.getDay()]; // getDay() mengembalikan 0-6 (Minggu-Sabtu)

                    const tanggal = `${pad(now.getDate())}-${pad(now.getMonth() + 1)}-${now.getFullYear()}`;

                    // Format waktu hh:mm:ss (24 jam)
                    const waktu = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

                    // Update elemen hari, tanggal, dan waktu
                    document.getElementById('Day').textContent = hari;
                    document.getElementById('Date').textContent = tanggal;
                    document.getElementById('Time').textContent = waktu;
                }

                // Panggil fungsi sekali saat halaman dimuat
                infoPersonal();

                // Update waktu, tanggal, dan hari setiap 1 detik
                setInterval(infoPersonal, 1000);
            </script>

            <div class="grid grid-cols-2 gap-3 mt-4">
                <button type="button" onclick="window.location.href='{{ url('/attendance/check-in') }}'"
                    class="bg-white text-black py-2 px-4 rounded-full text-xs font-semibold hover:bg-black hover:text-blue-400">
                    Check In
                </button>

                <button
                    class="bg-white text-black py-2 rounded-full text-xs font-semibold hover:bg-black hover:text-blue-400"
                    onclick="window.location.href='{{ url('/attendance/check-out') }}'">
                    Check Out
                </button>
            </div>

            <button
                class="w-full bg-black text-white py-2 rounded-full mt-3 text-xs font-semibold hover:bg-white hover:text-pink-400"
                onclick="showPermissionPopUp()">
                Permission
            </button>
            <script>
                function showPermissionPopUp() {
                    // Tampilkan tanggal hari ini di popup
                    const now = new Date();
                    const pad = n => n < 10 ? '0' + n : n;
                    const tanggal = `${pad(now.getDate())}-${pad(now.getMonth() + 1)}-${now.getFullYear()}`;
                    document.getElementById('permissionDate').textContent = tanggal;

                    const popup = document.getElementById('permissionPopUp');
                    popup.style.display = 'flex';
                    setTimeout(() => {
                        popup.classList.add('show');
                    }, 10);
                }

                function submitPermission() {
                    fetch('{{ route('attendance.permission') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({})
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                closePermissionPopUp();
                                showPermissionSuccessPopUp();
                            } else {
                                alert('Gagal mencatat permission!');
                            }
                        });
                }

                function closePermissionPopUp() {
                    const popup = document.getElementById('permissionPopUp');
                    popup.classList.remove('show');
                    setTimeout(() => {
                        popup.style.display = 'none';
                    }, 300);
                }

                // Popup sukses permission
                function showPermissionSuccessPopUp() {
                    const popup = document.getElementById('permissionSuccessPopUp');
                    popup.style.display = 'flex';
                    setTimeout(() => {
                        popup.classList.add('show');
                    }, 10);
                }

                function closePermissionSuccessPopUp() {
                    const popup = document.getElementById('permissionSuccessPopUp');
                    popup.classList.remove('show');
                    setTimeout(() => {
                        popup.style.display = 'none';
                    }, 300);
                }

                // Tutup popup dengan tombol ESC
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape') {
                        closePermissionPopUp();
                        closePermissionSuccessPopUp();
                    }
                });
            </script>

            <div id="permissionSuccessPopUp"
                class="popup-overlay fixed inset-0 bg-opacity-50 flex items-center justify-center z-[9999]"
                style="display: none;">
                <div class="popup-content bg-white rounded-lg p-6 max-w-sm mx-4 shadow-xl text-center">
                    <i class="fa-solid fa-circle-check text-4xl text-green-500 mb-3"></i>
                    <p class="text-gray-800 mb-3 font-poppins font-semibold">Permission berhasil dicatat!</p>
                    <button onclick="closePermissionSuccessPopUp()"
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-pink-600 transition-colors font-semibold font-poppins mt-2">
                        Tutup
                    </button>
                </div>
            </div>

            <div id="permissionPopUp"
                class="popup-overlay fixed inset-0  bg-opacity-50 flex items-center justify-center z-[9999]"
                style="display: none;">
                <div class="popup-content bg-white rounded-lg p-6 max-w-sm mx-4 shadow-xl">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fa-solid fa-check-circle text-4xl text-blue-500 mb-3"></i>
                            <p class="text-gray-800 mb-3 font-poppins">You have permission on the date</p>
                            <p id="permissionDate" class="text-xl font-bold text-blue-500 font-poppins"></p>
                        </div>
                        <div class="flex justify-center gap-4">
                            <button id="closePermissionBtn" onclick="submitPermission()"
                                class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-pink-600 transition-colors font-semibold font-poppins"
                                aria-label="Konfirmasi Izin">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Time Track -->
        <div class="mb-4">
            <div class="flex px-2 justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 mb-3">Time Track</h2>
                <p class="text-sm font-semibold text-blue-500 mb-3">Year {{ $yearRealtime }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                    <!-- Chart Section -->
                    <div class="w-full md:w-3/4 h-48">
                        <canvas id="timeTrackChart"></canvas>
                    </div>

                    <!-- Legend & Button Section -->
                    <div class="w-full md:w-1/2 flex flex-col justify-between gap-6">

                        <!-- Legend -->
                        <div class="grid lg:grid-cols-1 md:grid-cols-2 grid-cols-3 gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-400"></span>
                                <span class="text-xs text-gray-600">On Time</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                <span class="text-xs text-gray-600">Late</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-400"></span>
                                <span class="text-xs text-gray-600">Permission</span>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="flex justify-end mt-auto">
                            <button onclick="window.location.href='{{ url('/indexReport') }}'"
                                class="px-4 py-2 text-xs font-semibold text-white bg-blue-500 rounded-full shadow-lg transition-colors hover:bg-black hover:text-blue-400">
                                See Full Report
                            </button>
                        </div>
                    </div>
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
                        <img src="{{ asset('images/announcement.png') }}" alt="Important Announcement"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="w-64 h-20 flex-shrink-0">
                        <img src="{{ asset('images/announcement.png') }}" alt="Important Announcement"
                            class="w-full h-full object-cover">
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
                <a href="{{ url('/indexProfile') }}" class="text-gray-400">
                    <i class="fa fa-user text-xl"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Chart.js Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('timeTrackChart').getContext('2d');
            const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];


            const data = {
                labels: labels,
                datasets: [{
                        label: 'On Time',
                        data: @json($onTime),
                        borderColor: 'rgb(74, 222, 128)',
                        backgroundColor: 'rgba(74, 222, 128, 0.1)',
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(74, 222, 128)'
                    },
                    {
                        label: 'Late',
                        data: @json($late),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(239, 68, 68)'
                    },
                    {
                        label: 'Permission',
                        data: @json($permission),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(59, 130, 246)'
                    },
                ]
            };


            // Chart options
            const options = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 26,
                        ticks: {
                            stepSize: 4
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


            new Chart(ctx, config);
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
    <!-- Modal Tailwind -->
    <div id="permissionModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
            <h2 class="text-lg font-bold text-red-500 mb-2">Akses Ditolak</h2>
            <p class="text-gray-700 mb-4">
                @{{ modalMessage }}
            </p>
            <button onclick="closePermissionModal()"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Tutup</button>
        </div>
    </div>

    <script>
        let modalMessage = 'Akses tidak diizinkan.';

        function showPermissionModal(message) {
            modalMessage = message;
            document.querySelector('#permissionModal p').innerHTML = message;
            document.getElementById('permissionModal').classList.remove('hidden');
        }

        function closePermissionModal() {
            document.getElementById('permissionModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route('attendance.statusToday') }}')
                .then(res => res.json())
                .then(data => {
                    // Disable tombol Permission jika sudah check-in atau sudah permission
                    const permissionBtn = document.querySelector('button[onclick*="showPermissionPopUp"]');
                    if (data.permission) {
                        // Sudah permission, disable check-in, check-out, permission
                        const checkInBtn = document.querySelector('button[onclick*="check-in"]');
                        const checkOutBtn = document.querySelector('button[onclick*="check-out"]');
                        if (checkInBtn) {
                            checkInBtn.disabled = true;
                            checkInBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            checkInBtn.onclick = () => showPermissionModal(
                                'Anda sudah mengajukan permission hari ini.<br>Tidak bisa check-in atau check-out.'
                                );
                        }
                        if (checkOutBtn) {
                            checkOutBtn.disabled = true;
                            checkOutBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            checkOutBtn.onclick = () => showPermissionModal(
                                'Anda sudah mengajukan permission hari ini.<br>Tidak bisa check-in atau check-out.'
                                );
                        }
                        if (permissionBtn) {
                            permissionBtn.disabled = true;
                            permissionBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            permissionBtn.onclick = () => showPermissionModal(
                                'Anda sudah mengajukan permission hari ini.<br>Tidak bisa mengajukan permission lagi.'
                                );
                        }
                    } else if (data.check_in) {
                        // Sudah check-in, disable permission
                        if (permissionBtn) {
                            permissionBtn.disabled = true;
                            permissionBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            permissionBtn.onclick = () => showPermissionModal(
                                'Anda sudah check-in hari ini.<br>Tidak bisa mengajukan permission.');
                        }
                    }
                });
        });
    </script>

</body>

</html>
