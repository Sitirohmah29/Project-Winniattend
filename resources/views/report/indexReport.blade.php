<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>

    @vite('resources/css/app.css')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.0/cdn.min.js" defer></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="container">
    {{-- information page --}}
    <div class="page-title-container">
        <i class="fa-solid fa-chevron-left" page-title-back
        onclick="window.location.href='{{url('/dashboard')}}'"></i>
        <h2 class="text-title text-center w-full">Attedance Report</h2>
    </div>

    {{-- Welcome title --}}
    <p class="text-big">
        View your <span class="text-pink-500">attendance records</span>,
       for up to the past year!
      </p>

    {{-- Summary report attedance --}}
    <div >
        <p class="text-title">Month</p>
        <div class="flex-con-rounded">
            {{-- present --}}
            <div>
                <p class="text-reg">Present</p>
                <span class="flex-container">
                    <i class="fa-solid fa-chart-pie fa-lg" style="color: #5271FF;"></i>
                    <p class="text-base font-extrabold">4 Days</p>
                </span>
            </div>

            {{-- late --}}
            <div>
                <p class="text-reg">Late</p>
                <span class="flex-container">
                    <i class="fa-solid fa-chart-pie fa-lg" style="color: #FF66C4;"></i>
                    <p class="text-base font-extrabold">1h 28m</p>
                </span>
            </div>

            {{-- working hours --}}
            <div>
                <p class="text-reg">Working hours</p>
                <span class="flex-container">
                    <i class="fa-solid fa-chart-pie fa-lg" style="color: #5271FF;"></i>
                    <p class="text-base font-extrabold">20h</p>
                </span>
            </div>
        </div>
    </div>

    {{-- attedance report --}}
    <div class="grid gap-2">
        <div class="flex-container justify-between">
            <p>Accending - Decending</p>
            <i class="fa-solid fa-arrow-up-wide-short"></i>
        </div>


        <div class="mt-4 h-[500px] overflow-y-auto pr-2">
            <div class="grid gap-3">
                {{-- day summary --}}
                <div class="flex-con-rounded-b bg-[#5271FF]" onclick="window.location.href='{{url('/detailsReport')}}'">
                    <span class="text-white">
                        <p class="text-xl font-bold">01 Feb</p>
                        <p class="text-xl font-bold">Monday</p>
                    </span>
                    <span class="text-reg text-white">
                        <p>On Time</p>
                        <p>01.00 am - 06.00 pm</p>
                    </span>
                </div>

                {{-- day summary --}}
                <div class="flex-con-rounded-b bg-white">
                    <span>
                        <p class="text-xl font-bold">02 Feb</p>
                        <p class="text-xl font-bold">Tuesday</p>
                    </span>

                    <span class="text-reg">
                        <p>On Time</p>
                        <p>01.00 am - 06.00 pm</p>
                    </span>
                </div>

                {{-- day summary --}}
                <div class="flex-con-rounded-b bg-[#5271FF]">
                    <span class="text-white">
                        <p class="text-xl font-bold">03 Feb</p>
                        <p class="text-xl font-bold">Wednesday</p>
                    </span>

                    <span class="text-reg text-white">
                        <p>On Time</p>
                        <p>01.00 am - 06.00 pm</p>
                    </span>
                </div>

                {{-- day summary --}}
                <div class="flex-con-rounded-b bg-white">
                    <span>
                        <p class="text-xl font-bold">04 Feb</p>
                        <p class="text-xl font-bold">Thrusday</p>
                    </span>

                    <span class="text-reg">
                        <p>On Time</p>
                        <p>01.00 am - 06.00 pm</p>
                    </span>
                </div>

                {{-- day summary --}}
                <div class="flex-con-rounded-b bg-[#5271FF]">
                    <span class="text-white">
                        <p class="text-xl font-bold">05 Feb</p>
                        <p class="text-xl font-bold">Friday</p>
                    </span>

                    <span class="text-reg text-white">
                        <p>On Time</p>
                        <p>01.00 am - 06.00 pm</p>
                    </span>
                </div>

                {{-- day summary --}}
                <div class="flex-con-rounded-b bg-white">
                    <span>
                        <p class="text-xl font-bold">06 Feb</p>
                        <p class="text-xl font-bold">Sunday</p>
                    </span>

                    <span class="text-reg">
                        <p>On Time</p>
                        <p>01.00 am - 06.00 pm</p>
                    </span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
