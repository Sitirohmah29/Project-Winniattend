<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Personal Information</title>

    @vite('resources/css/app.css')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

</head>
<body class="container">
    {{-- page information --}}
    <div class="page-title-container">
        <i class="fa-solid fa-chevron-left cursor-pointer hover:text-blue-600" page-title-back
        onclick="window.location.href='{{url('/indexProfile')}}'"></i>
        <h2 class="text-title text-center w-full">Personal Information</h2>
    </div>

    {{-- image profile --}}
    <div class="pt-4 mt-4 flex flex-col items-center text-center">
        <img src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : asset('images/risma-cantik.jpg') }}"
             alt="Foto Profil {{ $user->name }}"
             class="profile-image w-32 h-32 rounded-full object-cover border-4 border-gray-300" />
    </div>

    {{-- personal information --}}
    <div class="grid-con-rounded">
        <div>
            <label class="text-reg-bold text-pink-500">ID Employee</label>
            <p class="text-reg">{{$user->id}}</p>
        </div>
        <div>
            <label class="text-reg-bold text-pink-500">Name</label>
            <p class="text-reg">{{$user->name}}</p>
        </div>
        <div>
            <label class="text-reg-bold text-pink-500">E-mail</label>
            <p class="text-reg">{{$user->email}}</p>
        </div>
        <div>
            <label class="text-reg-bold text-pink-500">Birth Date</label>
            <p class="text-reg">{{$user->birth_date}}</p>
        </div>
        <div>
            <label class="text-reg-bold text-pink-500">No. Telepon</label>
            <p class="text-reg">{{$user->phone}}</p>
        </div>
        <div>
            <label class="text-reg-bold text-pink-500">Address</label>
            <p class="text-reg">{{$user->address}}</p>
        </div>
    </div>
</body>
</html>
