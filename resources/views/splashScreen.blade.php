<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>WinniAttend - Your Workday Starts Here</title>
    
    <!-- PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="WinniAttend">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    
    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')
</head>
<body class="bg-white font-poppins">
    <div class="flex flex-col items-center justify-center min-h-screen p-6">
        <div class="flex flex-col items-center justify-center flex-1 text-center ">
            <!-- Logo -->
            <div class="mt-10">
                <img src="{{ asset('images/logo.png') }}" alt="WinniCode Garuda Teknologi Logo" class="w-50">
            </div>
            
            <!-- Welcome Text -->
            <h1 class="text-xl font-semibold ">
                <span class="text-blue-500">Welcome to </span>
                <span class="text-pink-500">WinniAttend!</span>
            </h1>
            
            <p class="mb-14 text-sm text-gray-700">Your Workday Starts Here</p>
            
            <!-- Get Started Button -->
            <a href="" class="py-2 px-12 h-8 text-xs text-white transition-colors bg-blue-500 rounded-3xl shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 w-55 font-semibold flex items-center justify-center mt-60">
    Get Started
</a>
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
</body>
</html>