<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Profile</title>

        <!-- Tailwind CSS -->
        @vite('resources/css/profile.css')
        @vite('resources/css/components/button.css')
        @vite('resources/css/components/textInput.css')
        <!-- Alpine.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="py-8 px-8 grid gap-8">
    <div class="page-title-back">
        <a href="{{ route('indexProfile') }}">
            <i class="fas fa-chevron-left text-gray-600"></i>
        </a>
        <h2 class="font-bold font-xl">Edit Profile</h2>
    </div>

<div class="flex justify-center items-center">
    <div class="flex relative w-fit">
        <img src="{{ asset('images/risma-cantik.jpg') }}"
             alt="Foto Risma Cantik"
             class="profile-image" />
        <i class="fa-solid fa-pen-to-square absolute bottom-1 right-1 p-1"></i>
    </div>
</div>


    <div class="grid gap-5">
        <div class="grid gap-2">
            <label for="name">Name</label>
            <input class="input">
        </div>
        <div class="grid gap-2">
            <label for="birthdate">Birth Date</label>
            <input class="input">
        </div>
        <div class="grid gap-2">
            <label for="telp">No. Telp</label>
            <input class="input">
        </div>
        <div class="grid gap-2">
            <label for="address">Address</label>
            <input class="input">
        </div>
    </div>
    <div class="mt-5 flex justify-center">
        <button class="long-button">Save</button>
    </div>
</body>
</html>
