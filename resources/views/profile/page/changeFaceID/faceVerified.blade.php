<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Face Verification</title>

    @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="container">
    {{-- information page --}}
    <div class="page-title-container">
        <i class="fa-solid fa-chevron-left" page-title-back
        onclick="window.location.href='{{url('/changeFace')}}'"></i>
        <h2 class="text-title text-center w-full">Face Verification</h2>
    </div>

    {{-- welcome face verification --}}
    <div class="p-15 pt-15 text-center">
        <p class="text-reg text-gray-600">Follow the instructions to complete face verification!</p>
        <p class="text-title text-red-600">Warning!</p>
        <p class="text-reg text-gray-600">changes cannot be reversed within one month</p>
    </div>

    {{-- pop up alert --}}
    <div class="flex-con-rounded">
        <i class="fa-solid fa-circle-check fa-2x" style="color: #63E6BE;"></i>
        <p class="text-title">Face ID has been succesfully Uploaded!</p>
    </div>

    {{-- button done --}}
    <div class="fixed-bottom-button">
        <button class="long-button" onclick="window.location.href='{{url('/indexProfile')}}'">
            Done
        </button>
    </div>
</body>
</html>
