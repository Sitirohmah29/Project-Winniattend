<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Face ID</title>

      <!-- Tailwind CSS -->
      @vite('resources/css/app.css')
      <!-- Alpine.js -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
      <!-- Font Awesome -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="container place-items-center">
    {{-- information page --}}
    <div class="page-title-container">
        <i class="fa-solid fa-chevron-left cursor-pointer hover:text-blue-600" page-title-back
        onclick="window.location.href='{{url('/indexProfile')}}'"></i>
        <h2 class="text-title text-center w-full">Change Face ID</h2>
    </div>

    {{-- welcome change face id --}}
    <div class="pt-10 place-items-center">
        <img src="{{asset('images/Scan-faceid.png')}}">
        <span class="text-center">
            <p class="text-title">Need a new Face ID?</p>
            <p class="text-reg text-gray-600">Scan your face again for better security</p>
        </span>
    </div>

    {{-- button next --}}
    <div class="fixed-bottom-button">
        <button class="long-button" onclick="window.location.href='{{url('/faceVerified')}}'">
            Next
        </button>
    </div>
</body>
</html>
