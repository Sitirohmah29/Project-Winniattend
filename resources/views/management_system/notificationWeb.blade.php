@extends('management_system.templates.layouts')
@section('content')
    <!-- Header -->
    <div class="flex flex-row gap-2 items-center bg-blue-500 text-white p-4 rounded-lg">
        <button class="text-lg" onclick="window.location.href='{{ url('/dashboardWeb') }}'">Dashboard</button>
        <i class="fa-solid fa-angle-right"></i>
        <h1 class="text-xl font-semibold">Notifications</h1>
    </div>

    <div class="notif flex flex-col py-5 px-4 gap-6 rounded-2xl shadow-lg w-full mx-auto">
        <!-- Header 2-->
        <div class="flex flex-row gap-4 items-center">
            <div class="px-3 py-2 rounded-full shadow-lg cursor-pointer"
                onclick="window.location.href='{{ url('/dashboardWeb') }}'">
                <i class="fa-solid fa-angle-left fa-xl"></i>
            </div>
            <p class="text-black text-xl font-semibold">Notifications</p>
            <div class="px-3 py-2 rounded-full shadow-lg">
                <p class="font-semibold text-base text-red-600">15</p>
            </div>
        </div>

        <!-- Tanggal -->
        <p class="italic text-sm text-gray-500">Monday, 01 Feb 2025</p>

        <!-- Notifikasi 1 -->
        <div class="border-t border-blue-500 pt-2 relative">
            <p>Daily Attendance <span class="text-blue-500 underline">Report has been generated</span>.</p>
            <p class="mt-2">Summary:</p>
            <ul class="list-disc list-inside text-sm">
                <li>Total Employees: 50</li>
                <li>Present: 44</li>
                <li>Late: 3</li>
                <li>Absent Without Notice: 2</li>
                <li>On Leave: 1</li>
            </ul>
            <p class="text-sm pl-4 mt-1">The report can be downloaded in CSV/PDF/Excel format from the Reports &
                Analysis page.</p>
            <p class="text-sm italic text-pink-500 absolute right-0 bottom-0">05.30 PM</p>
        </div>

        <!-- Notifikasi 2 -->
        <div class="border-t border-blue-500 pt-2 relative">
            <p>Dewi Lestari (HRD) successfully <span class="font-semibold text-blue-600">checked in on time</span>,
                Location: Head Office, Average check-in time of employees today: 08:07 AM</p>
            <p class="text-sm italic text-pink-500 absolute right-0 bottom-0">08.00 AM</p>
        </div>

        <!-- Notifikasi 3 -->
        <div class="border-t border-blue-500 pt-2 relative">
            <p>Five employees <span class="text-red-500 font-medium">have not checked in yet</span>:</p>
            <ul class="list-disc list-inside text-sm mt-1">
                <li>Rizky Hidayat (Marketing)</li>
                <li>Tia Ramadhani (Finance)</li>
                <li>Bayu Saputra (IT Support)</li>
                <li>Dinda Maharani (Customer Service)</li>
                <li>Arif Wibowo (Logistics)</li>
            </ul>
            <p class="text-blue-500 text-sm mt-1 pl-4">A reminder notification will be sent automatically to the
                employees.</p>
            <p class="text-sm italic text-pink-500 absolute right-0 bottom-0">09.00 AM</p>
        </div>
    @endsection
