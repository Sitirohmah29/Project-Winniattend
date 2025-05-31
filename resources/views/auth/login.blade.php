<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>WinniAttend - Login</title>

    <!-- PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="WinniAttend" />
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}" />
    <link rel="manifest" href="{{ asset('/manifest.json') }}" />

    <!-- Tailwind CSS -->
    @vite('resources/css/app.css')
    @vite('resources/css/components/button.css')

    <!-- Alpine.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="container font-poppins">
    <div
    x-data="{
        showForm: true,
        passwordVisible: false,
        hasRedirected: false,
        handleScroll() {
            if (window.scrollY > 50 && !this.hasRedirected) {
                this.hasRedirected = true;
                window.location.href = '{{ url('/') }}';
            }
        }
    }"

        @scroll.window="handleScroll()"
        class="flex flex-col items-center justify-center min-h-screen p-6 relative overflow-hidden"
    >
        <div class="flex flex-col items-center justify-center flex-1 text-center w-full max-w-full fixed">
            <!-- Logo -->
            <div :class="showForm ? 'transform -translate-y-10 scale-100 transition-all duration-500' : 'mt-2 transition-all duration-500'">
                <img src="{{ asset('images/logo.png') }}" alt="WinniCode Garuda Teknologi Logo" class="w-70" />
            </div>

            <!-- Login Form -->
            <div
                x-show="showForm"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-full"
        class="w-full mx-auto pt-8 top-45 shadow-[0_-8px_15px_-3px_rgba(0,0,0,0.08)] rounded-t-3xl bg-white"

            >
            <div class="flex justify-center items-center pt-2 pb-1">
                <button class="w-12 h-1 bg-gray-300 rounded-full" onclick="window.location.href='{{ url('/') }}'"></button>
            </div>

                <h2 class="text-center text-xl font-semibold pt-6 ">
                    <span class="text-blue-500">Sign In</span>
                </h2>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ url('login') }}" method="POST" class="w-full space-y-4 px-6 mt-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold mb-4 text-left">E-mail</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold mb-4 text-left">Password</label>
                        <div class="relative">
                            <input
                                :type="passwordVisible ? 'text' : 'password'"
                                id="password"
                                name="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <button
                                type="button"
                                @click="passwordVisible = !passwordVisible"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"
                            >
                                <i class="fa" :class="passwordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <span class="text-xs text-black-500">
                            Forgot password?
                            <a href="#" class="text-xs text-red-500 hover:text-blue-700">Click Here</a>
                        </span>
                    </div>

                    <button class="long-button mt-30">
                        Next
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(function () {
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
