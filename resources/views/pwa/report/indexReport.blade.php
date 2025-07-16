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

        <i class="fa-solid fa-chevron-left cursor-pointer hover:text-blue-600" page-title-back
            onclick="window.location.href='{{ url('/dashboard') }}'"></i>
        <h2 class="text-title text-center w-full">Attedance Report</h2>
    </div>

    {{-- Welcome title --}}
    <p class="text-xl font-semibold">
        View your <span class="text-pink-500">attendance records</span>,
        for up to the past year!
    </p>

    {{-- Summary report attendance --}}
    <div>
        <p class="text-title">Month</p>
        <div class="p-5 grid grid-cols-3 gap-10 items-center justify-between shadow-xl rounded-2xl">
            {{-- present --}}
            <div>
                <p class="text-reg">Present</p>
                <span class="flex-container">
                    <i class="fa-solid fa-chart-pie fa-lg" style="color: #5271FF;"></i>
                    <p class="text-base font-extrabold">{{ $presentDays }} Days</p>
                </span>
            </div>
            {{-- on time --}}
            <div>
                <p class="text-reg">On Time</p>
                <span class="flex-container">
                    <i class="fa-solid fa-clock fa-lg" style="color: #63E6BE;"></i>
                    <p class="text-base font-extrabold">{{ $onTimeCount }} Days</p>
                </span>
            </div>
            {{-- late --}}
            <div>
                <p class="text-reg">Late</p>
                <span class="flex-container">
                    <i class="fa-solid fa-clock fa-lg" style="color: #FF66C4;"></i>
                    <p class="text-base font-extrabold">{{ $lateCount }} Days</p>
                </span>
            </div>
            {{-- working hours --}}
            <div>
                <p class="text-reg">Working hours</p>
                <span class="flex-container">
                    <i class="fa-solid fa-chart-pie fa-lg" style="color: #5271FF;"></i>
                    <p class="text-base font-extrabold">
                        {{ floor($totalWorkMinutes / 60) }}h {{ $totalWorkMinutes % 60 }}m
                    </p>
                </span>
            </div>
        </div>
    </div>

    {{-- attendance report --}}
    <div class="grid gap-2">
        <div class="flex-container justify-between">
            <p>Accending - Decending</p>
            <i class="fa-solid fa-arrow-up-wide-short"></i>
        </div>
        <div class="mt-4 h-[500px] overflow-y-auto pr-2">
            <div class="grid gap-3">
                @foreach ($attendances as $attendance)
                    @php
                        $date = \Carbon\Carbon::parse($attendance->date);
                        $isBlue = $loop->iteration % 2 == 1;
                        $status = $attendance->status_label ?? ucfirst($attendance->status);
                        $checkIn = $attendance->check_in
                            ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i')
                            : '-';
                        $checkOut = $attendance->check_out
                            ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i')
                            : '-';
                        $workMinutes =
                            $attendance->check_in && $attendance->check_out
                                ? \Carbon\Carbon::parse($attendance->check_in)->diffInMinutes(
                                    \Carbon\Carbon::parse($attendance->check_out),
                                )
                                : 0;
                        $workHours = $workMinutes ? floor($workMinutes / 60) . 'h ' . $workMinutes % 60 . 'm' : '-';
                    @endphp
                    <a href="{{ route('report.details', ['id' => $attendance->id]) }}">
                        <div
                            class="flex-con-rounded-b {{ $isBlue ? 'bg-[#5271FF]' : 'bg-white' }} cursor-pointer hover:shadow">
                            <span class="{{ $isBlue ? 'text-white' : '' }}">
                                <p class="text-xl font-bold">{{ $date->format('d M') }}</p>
                                <p class="text-xl font-bold">{{ $date->format('l') }}</p>
                            </span>
                            <span class="text-reg {{ $isBlue ? 'text-white' : '' }}">
                                <p>{{ $status }}</p>
                                <p>{{ $checkIn }} - {{ $checkOut }}</p>
                                <p class="text-xs">Working: {{ $workHours }}</p>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>
