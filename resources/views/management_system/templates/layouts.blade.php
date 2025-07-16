<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Management - Dashboard</title>
    @vite('resources/css/app.css')
    <!-- Alpine.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="navbar fixed left-0 top-0 h-full flex flex-col justify-start bg-white shadow-lg px-6 py-8 w-58 z-10">
        @include('management_system.templates.sidebar.navigation')
    </div>

    <div class="content flex flex-col gap-6 ml-58 p-6 w-auto overflow-y-auto">
        @yield('content')
    </div>
</body>

</html>
