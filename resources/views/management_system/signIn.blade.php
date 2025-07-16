<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Management - Sign In</title>

    @vite('resources/css/app.css')
    <!-- Alpine.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="container-web max-w-screen mx-auto px-4 font-poppins overflow-x-hidden">
    <div class="hero flex flex-row gap-[90px] items-center justify-between">
        <div class="welcome flex-col gap-[20px]">
            <p class="text-gray-500 italic text-3xl">Welcome to the</p>
            <p class="text-5xl font-bold text-pink-500">Winni<span class="text-blue-500">attend</span></p>
            <p class="text-5xl font-bold text-gray-700">Management System</p>
        </div>
        <form action="{{ route('admin.login.attempt') }}" method="POST"
            class="form w-xl flex flex-col gap-[50px] py-[40px] px-[70px] rounded-2xl shadow-2xl">
            @csrf
            <p class="text-5xl text-center text-blue-500 font-bold">Admin Login</p>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif


            <div class="flex flex-row gap-[15px]">
                <svg width="49" height="50" viewBox="0 0 49 50" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2.45831 42.625C2.45831 39.5087 3.61943 36.52 5.68624 34.3165C7.75305 32.1129 10.5562 30.875 13.4791 30.875H35.5208C38.4437 30.875 41.2469 32.1129 43.3137 34.3165C45.3805 36.52 46.5417 39.5087 46.5417 42.625C46.5417 44.1831 45.9611 45.6775 44.9277 46.7793C43.8943 47.881 42.4927 48.5 41.0312 48.5H7.96873C6.50728 48.5 5.10568 47.881 4.07228 46.7793C3.03887 45.6775 2.45831 44.1831 2.45831 42.625Z"
                        stroke="#5271FF" stroke-width="3" stroke-linejoin="round" />
                    <path
                        d="M24.5 19.125C29.065 19.125 32.7656 15.1795 32.7656 10.3125C32.7656 5.44549 29.065 1.5 24.5 1.5C19.935 1.5 16.2344 5.44549 16.2344 10.3125C16.2344 15.1795 19.935 19.125 24.5 19.125Z"
                        stroke="#5271FF" stroke-width="3" />
                </svg>
                <input type="email" id="email" name="email"
                    class="w-xl px-[20px] rounded-md border-2 border-gray-400">
            </div>

            <div class="flex flex-col gap-2">
                <div class="flex flex-row gap-[15px]">
                    <svg width="52" height="51" viewBox="0 0 52 51" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.875 20.5009V15.7509C11.875 14.9418 11.9407 14.1501 12.0721 13.3759M40.375 20.5009V15.7509C40.3752 12.8563 39.4939 10.0304 37.8483 7.64903C36.2028 5.26771 33.8711 3.44397 31.1636 2.42051C28.456 1.39704 25.5009 1.22239 22.6916 1.9198C19.8824 2.61721 17.3521 4.1536 15.4375 6.32451M23.75 49.0009H16.625C9.9085 49.0009 6.54787 49.0009 4.46262 46.9133C2.375 44.828 2.375 41.4674 2.375 34.7509C2.375 28.0344 2.375 24.6738 4.46262 22.5885C6.54787 20.5009 9.9085 20.5009 16.625 20.5009H35.625C42.3415 20.5009 45.7021 20.5009 47.7874 22.5885C49.875 24.6738 49.875 28.0344 49.875 34.7509C49.875 41.4674 49.875 44.828 47.7874 46.9133C45.7021 49.0009 42.3415 49.0009 35.625 49.0009H33.25"
                            stroke="#5271FF" stroke-width="3" stroke-linecap="round" />
                    </svg>
                    <input type="password" id="password" name="password"
                        class="w-xl px-[20px] rounded-md border-2 border-gray-400">
                </div>

                <div class="flex justify-between">
                    <div class="remember flex flex-row gap-3">
                        <input type="checkbox">
                        <p class="text-lg text-gray-400">Remember me</p>
                    </div>

                    <p class="text-lg text-blue-500">Forget password?<span class="text-lg text-red-500"> Click
                            here</span></p>
                </div>
            </div>

            <button
                class="p-[10px] w-auto  bg-blue-500 hover:bg-pink-500 text-white font-semibold text-center rounded-md"
                type="submit">Login</button>
        </form>
    </div>
</body>

</html>
