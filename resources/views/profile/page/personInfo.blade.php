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
        <i class="fa-solid fa-chevron-left" page-title-back
        onclick="window.location.href='{{url('/indexProfile')}}'"></i>
        <h2 class="text-title text-center w-full">Personal Information</h2>
    </div>

    {{-- image profile --}}
    <div class="pt-4 mt-4 flex flex-col items-center text-center">
        <img src="{{ asset('images/risma-cantik.jpg') }}"
             alt="Foto Risma Cantik"
             class="profile-image" />
    </div>

    {{-- personal information --}}
    <div class="grid-con-rounded">
        <div>
            <label class="text-reg-bold text-pink-500">ID Employee</label>
            <p class="text-reg">FD222310015</p>
        </div>
        <div>
            <label class="text-reg-bold text-pink-500">Name</label>
            <p class="text-reg">Risma Handayani</p>
        </div>
        <div>
            <label class="text-reg-bold text-pink-500">E-mail</label>
            <p class="text-reg">rismahandayani801@gmail.com</p>
        </div>
        <div>
            <label class="text-reg-bold text-pink-500">Birth Date</label>
            <p class="text-reg">24-06-2004</p>
        </div>
        <div>
            <label class="text-reg-bold text-pink-500">No. Telepon</label>
            <p class="text-reg">0814-1322-5122</p>
        </div>
        <div>
            <label class="text-reg-bold text-pink-500">Address</label>
            <p class="text-reg">Kp. Sari Gading rt.05/rw.07</p>
        </div>
    </div>
</body>
</html>
