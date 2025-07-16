@extends('management_system.templates.layouts')
@section('content')
    <!-- Header -->
    <div class="flex flex-row gap-2 items-center bg-blue-500 text-white p-4 rounded-lg">
        <button class="text-lg" onclick="window.location.href='{{ url('/dashboardWeb') }}'">Attedance Management</button>
        <i class="fa-solid fa-angle-right"></i>
        <h1 class="text-xl font-bold">Check in</h1>
    </div>

    <div class="flex flex-row gap-4 items-center">
        <div class="px-3 py-2 rounded-full shadow-lg cursor-pointer"
            onclick="window.location.href='{{ url('/indexAttedance') }}'">
            <i class="fa-solid fa-angle-left fa-xl"></i>
        </div>
        <p class="text-black text-xl font-semibold">Check in</p>
    </div>

    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-blue-500 text-white text-left text-sm">
                    <th class="px-4 py-3 font-semibold">Employee</th>
                    <th class="px-4 py-3 font-semibold">Date</th>
                    <th class="px-4 py-3 font-semibold">Location</th>
                    <th class="px-4 py-3 font-semibold">Check in</th>
                    <th class="px-4 py-3 font-semibold">Face ID</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $attendance->user->fullname ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $attendance->date }}</td>
                    <td class="px-4 py-2">{{ $attendance->check_in_location ?? '-' }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}</td>
                    <td class="px-4 py-2">FI23RF21MFLl</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
