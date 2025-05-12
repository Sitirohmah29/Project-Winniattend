<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Password</title>

    @vite('resources/css/app.css')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="container place-items-center fixed bottom-3 ">
    {{-- page information --}}
    <div class="page-title-container">
        <i class="fa-solid fa-chevron-left" page-title-back
        onclick="window.location.href='{{url('/indexProfile')}}'"></i>
        <h2 class="text-title text-center w-full">Change Password</h2>
    </div>

    {{-- welcome change pw --}}
    <div class="place-items-center">
        <img src="{{asset('images/Animasi-Gembok.png')}}">
        <span class="text-center">
            <p class="text-title">Keep your Account Safe</p>
            <p class="text-reg text-gray-600">with a new password</p>
        </span>
    </div>

    {{-- input pw --}}
    <div class="p-3 grid gap-3 pb-20">
        {{-- input password --}}
        <div class="grid gap-2" x-data="{ passwordVisible: false }">
            <label class="text-reg-bold">Password</label>

            <div class="relative">
                <input
                    :type="passwordVisible ? 'text' : 'password'"
                    class="input w-full pr-10"
                    id="password"
                    name="password"
                >

                <button
                    type="button"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-black"
                    @click="passwordVisible = !passwordVisible"
                >
                    <i class="fa" :class="passwordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        {{-- input new password --}}
        <div>
            <label class="text-reg-bold">New Password</label>
            <input class="input" type="password">
        </div>

        {{-- input confirm password --}}
        <div>
            <label class="text-reg-bold">Confirm Password</label>
            <input class="input" type="password">
        </div>
    </div>

    {{-- button save --}}
    <div class="fixed-bottom-button">
        <button class="long-button">
            Save
        </button>
    </div>

</body>
</html>
