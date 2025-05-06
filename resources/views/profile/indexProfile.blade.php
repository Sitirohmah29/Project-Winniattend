<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

      <!-- Tailwind CSS -->
      @vite('resources/css/profile.css')
      <!-- Alpine.js -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
      <!-- Font Awesome -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="py-4 px-4 relative">
    <h2 class="font-bold font-xl text-center">Profile</h2>

    {{-- username profile --}}
    <div class="pt-10 mt-4 flex flex-col items-center text-center">
        <img src="{{ asset('images/risma-cantik.jpg') }}"
             alt="Foto Risma Cantik"
             class="profile-image" />

        <h2 class="text-title">Risma Handayani</h2>
        <p class="text-sm text-gray-600">Laravel Developer</p>

        <button class="profile-btn ">
            Edit Profile
        </button>
    </div>


    {{-- card account --}}
    <div>
        <h2 class="text-title flex-start ">Account</h2>
        <div class="profile-card">
            <div class="profile-icon">
                <i class="fa-solid fa-info fa-xs" ></i>
             </div>
            <p class="text-white text-md">Personal Information</p>
        </div>

        <div class="profile-card">
            <div class="profile-icon">
                <i class="fa-solid fa-lock fa-xs" ></i>
             </div>
            <p class="text-white text-md">Change Passowrd</p>
        </div>

        <div class="profile-card">
            <div class="profile-icon">
                <img src={{url("https://cdn-icons-png.freepik.com/512/6022/6022815.png")}} class="w-4 h-4">
             </div>
            <p class="text-white text-md">Change Face ID</p>
        </div>

    </div>

    {{-- card settings --}}
    <div>
        <h2 class="text-title flex-start">Settings</h2>
        <div class="profile-card">
            <div class="profile-icon">
                <i class="fa-solid fa-bell fa-xs"></i>
             </div>
            <p class="text-white text-md">Notification Reminder</p>
        </div>
        <div class="profile-card">
            <div class="profile-icon">
                <i class="fa-solid fa-bell fa-xs"></i>
             </div>
            <p class="text-white text-md">Dark Mode</p>
        </div>
    </div>

    {{-- logout --}}
    <button></button>

    {{-- Bottom Navigation --}}
    <div class="fixed bottom-6 left-0 right-0 flex justify-center">
        <div class="bg-white rounded-full shadow-lg px-6 py-2 flex space-x-8">
            <a href="{{url('/dashboard')}}" class="text-gray-400">
                <i class="fa fa-home text-xl"></i>
            </a>
            <a href="#" class="text-blue-500" >
                <i class="fa fa-user text-xl"></i>
            </a>
        </div>
    </div>
</div>
</body>
</html>
