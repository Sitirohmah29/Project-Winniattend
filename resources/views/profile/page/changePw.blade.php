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

<body class="container bottom-3 ">
    {{-- page information --}}
    <div class="page-title-container">
        <i class="fa-solid fa-chevron-left" page-title-back
        onclick="window.location.href='{{url('/indexProfile')}}'"></i>
        <h2 class="text-title text-center w-full">Change Password</h2>
    </div>

        {{-- welcome change pw --}}
    <div class="flex flex-col items-center justify-center text-center pb-2">
        <img src="{{ asset('images/Animasi-Gembok.png') }}" class="w-40 md:w-56 mb-4" alt="Gembok Animasi">

        <div>
          <p class="text-xl md:text-2xl font-semibold text-gray-800">Keep your Account Safe</p>
          <p class="text-sm md:text-base text-gray-600">with a new password</p>
        </div>
      </div>

    {{-- input pw --}}
    <form method="POST" action="{{route('Update.password')}}">
    @csrf

        <div class="p-3 grid gap-3 pb-20">
            {{-- input password --}}
            <div class="grid gap-2" x-data="{ passwordVisible: false }">
                <label class="text-reg-bold">Current Password</label>
                <div class="relative">
                    <input
                        :type="passwordVisible ? 'text' : 'password'"
                        class="input w-full pr-10"
                        id="password"
                        name="current_password"
                        required
                    >

                    <button
                        type="button"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-black"
                        @click="passwordVisible = !passwordVisible"
                    >
                        <i class="fa" :class="passwordVisible ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('current_password')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>

            {{-- input new password --}}
            <div>
                <label class="text-reg-bold">New Password</label>
                <input class="input" type="password" name="new_password" required>
                @error('new_password')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>

            {{-- input confirm password --}}
            <div>
                <label class="text-reg-bold">Confirm Password</label>
                <input class="input" type="password" name="new_password_confirmation" required>
            </div>

            @if(session('success'))
                <div class="text-green-500 text-center font-bold">{{ session('success') }}</div>
            @endif
        </div>

        {{-- button save --}}
        <div class="fixed-bottom-button">
            <button type="submit" class="long-button">
                Save
            </button>
        </div>


    </form>
</body>
</html>
