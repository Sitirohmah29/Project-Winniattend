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
    @vite('resources/css/components/button.css')

    <!-- Alpine.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-white font-poppins">
    <div 
        x-data="{
            showForm: false,
            passwordVisible: false,
            handleScroll() {
                if (window.scrollY > 50 && this.showForm) {
                    this.showForm = false;
                }
            }
        }"
        @scroll.window="handleScroll()"
        class="flex flex-col items-center justify-center min-h-screen p-6 relative overflow-hidden"
    >
        <div class="flex flex-col items-center justify-center flex-1 text-center w-full max-w-md">
            <!-- Logo -->
            <div :class="showForm ? 'transform -translate-y-10 scale-100 transition-all duration-500' : 'mt-10 transition-all duration-500'">
                <img src="{{ asset('images/logo.png') }}" alt="WinniCode Garuda Teknologi Logo" class="w-50">
            </div>

            <!-- Welcome Text -->
            <h1 class="text-xl font-semibold" :class="showForm ? 'hidden' : ''">
                <span class="text-blue-500">Welcome to </span>
                <span class="text-pink-500">WinniAttend!</span>
            </h1>

            <p class="mb-14 text-sm text-gray-700" :class="showForm ? 'hidden' : ''">
                Your Workday Starts Here
            </p>

            <!-- Get Started Button -->
            <a 
                href="{{ route('login') }}"
                class="py-2 px-12 h-8 text-xs text-white transition-colors bg-blue-500 hover:bg-black hover:text-blue-400 rounded-3xl shadow-lg w-55 font-semibold flex items-center justify-center mt-60 block"
            >
                Get Started
        </div>
    </div>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function (registration) {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(function (error) {
                        console.log('ServiceWorker registration failed:', error);
                    });
            });
        }
    </script>
</body>
</html>
