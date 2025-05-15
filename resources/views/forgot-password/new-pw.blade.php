<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Passoword</title>

    @vite('resources/css/app.css')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="container">
      {{-- information page --}}
      <div class="page-title-container">
        <i class="fa-solid fa-chevron-left" page-title-back
        onclick="window.location.href='{{url('/verif-code')}}'"></i>
        <h2 class="text-title text-center w-full">New Password</h2>
    </div>

    <div class="flex flex-col gap-y-3">
        <div class="flex flex-col gap-y-2">
            <p class="text-title font-bold">Enter new password</p>
            <input class="input">
        </div>
        <div class="flex flex-col gap-y-2">
            <p class="text-title font-bold">Confirm password</p>
            <input class="input">
        </div>
    </div>

    <div class="mt-5 mb-15 flex justify-center" onclick="window.location.href='{{url('/login')}}'">
        <button class="long-button">Next</button>
    </div>
</body>
</html>
