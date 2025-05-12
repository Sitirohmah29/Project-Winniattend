<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>WinniAttend - Notifications</title>
    
    <!-- PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="WinniAttend">
    <link rel="apple-touch-icon" href="images/icons/icon-192x192.png">
    <link rel="manifest" href="manifest.json">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>
<body class="bg-gray-50 font-poppins">
    <div class="max-w-md mx-auto bg-white min-h-screen shadow-sm">
        <!-- Header -->
        <header class="px-6 py-4 relative flex items-center">
            <a href="{{url('/dashboard')}}" class="absolute left-6">
                <i class="fa fa-chevron-left text-gray-700"></i>
            </a>
            <h1 class="mx-auto text-sm font-semibold text-gray-800">Notifications</h1>
        </header>

        <!-- Notification List -->
        <div class="px-4">
            <!-- Monday -->
            <div class="py-2">
                <h2 class="text-gray-600 text-sm font-medium">Monday, 01 Feb 2025</h2>
                <div class="h-1 bg-blue-500 mt-1 mb-3"></div>
                
                <div class="bg-white p-1 rounded-lg shadow-sm mb-4">
                    <p class="text-gray-800">
                        Hai, <span class="text-pink-400 font-medium">Risma Handayani</span>! Anda belum melakukan presensi hari ini.
                    </p>
                    <p class="text-gray-800 mt-1">
                        Silakan lakukan presensi sebelum pukul 01.00 am untuk menghindari keterlambatan.
                    </p>
                </div>
            </div>

            <!-- Thursday -->
            <div class="py-3">
                <h2 class="text-gray-600 text-sm font-medium">Thrusday, 02 Feb 2025</h2>
                <div class="h-1 bg-blue-500 mt-1 mb-3"></div>
                
                <div class="bg-white p-1 rounded-lg shadow-sm mb-4">
                    <p class="text-gray-800">
                        Hai, <span class="text-pink-400 font-medium">Risma Handayani</span>! Anda belum melakukan presensi hari ini.
                    </p>
                    <p class="text-gray-800 mt-1">
                        Silakan lakukan presensi sebelum pukul 01.00 am untuk menghindari keterlambatan.
                    </p>
                </div>
            </div>

            <!-- Wednesday -->
            <div class="py-3">
                <h2 class="text-gray-600 text-sm font-medium">Wednesday, 03 Feb 2025</h2>
                <div class="h-1 bg-blue-500 mt-1 mb-3"></div>
                
                <div class="bg-white p-1 rounded-lg shadow-sm mb-4">
                    <p class="text-gray-800">
                        Hai, <span class="text-pink-400 font-medium">Risma Handayani</span>! Anda belum melakukan presensi hari ini.
                    </p>
                    <p class="text-gray-800 mt-1">
                        Silakan lakukan presensi sebelum pukul 01.00 am untuk menghindari keterlambatan.
                    </p>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Main App JS -->
    <script src="app.js"></script>
</body>
</html>