<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Details Report</title>

    @vite('resources/css/app.css')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="container">
     {{-- information page --}}
     <div class="page-title-container">
        <i class="fa-solid fa-chevron-left" page-title-back
        onclick="window.location.href='{{url('/indexReport')}}'"></i>
        <h2 class="text-title text-center w-full">01 Feb Monday</h2>
    </div>

    {{-- Details Report --}}
    <div>
        <p class="text-big text-blue-600"> Details </p>

        {{--location report --}}
        <div class="grid-con-rounded">
            <span >
                <p class="text-title">IBI Kesatuan</p>
                <p class="text-reg">Jl. Rangga gading no.1 RT.02/09, Gudang, Kec.Bogor Tengah., Bogor, Indonesia 16123 </p>
            </span>
        </div>

        {{-- working hours report  --}}
        <div class="grid-con-rounded">

            {{-- check in --}}
            <span>
                <p class="text-reg">Check in</p>
                <p class="text-title">01.00 am</p>
            </span>

            {{-- check out --}}
            <span>
                <p class="text-reg">Check out</p>
                <p class="text-title">06.00 am</p>
            </span>

            {{-- total working hours --}}
            <span class="grid justify-end">
                <p class="text-reg">Total Working hours</p>
                <p class="text-big">4 Hours</p>
            </span>
        </div>

        {{-- face id report --}}
        <div class="grid-con-rounded">
            <p class="text-reg">Face ID</p>
            <div class="flex-container justify-center items-center">
                <p class="text-title2">Success Complete</p>
                <i class="fa-solid fa-circle-check fa-lg" style="color: #63E6BE;"></i>
            </div>
        </div>
    </div>

</body>
</html>
