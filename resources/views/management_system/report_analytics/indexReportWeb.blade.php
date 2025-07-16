@extends('management_system.templates.layouts')
@section('content')
    <!-- Header -->
    <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
        <h1 class="text-xl font-semibold">Report & Analytics</h1>
    </div>

    <!-- Report Section -->
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Report</h2>
        <div class="flex gap-4">
            <button onclick="window.location.href='{{ url('/attedanceReport') }}'"
                class="flex items-center justify-center w-[250px] gap-2 px-6 py-8 rounded-lg bg-gradient-to-r from-pink-400 to-blue-500 text-white font-medium shadow-lg hover:opacity-90">
                <i class="fa-solid fa-file text-white"></i>
                <span class="text-lg">Attendance Report</span>
            </button>

            <button onclick="window.location.href='{{ url('/payrollReport') }}'"
                class="flex items-center justify-center w-[250px] gap-2 px-6 py-8 rounded-lg bg-gradient-to-r from-pink-400 to-purple-500 text-white font-medium shadow-lg hover:opacity-90">
                <i class="fa-solid fa-file-invoice-dollar text-white"></i>
                <span class="text-lg">Payroll Report</span>
            </button>
        </div>
    </div>

    <!-- Analytics Section -->
    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Analytics</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Line Chart Placeholder -->
            <div class="bg-white p-4 rounded-xl shadow-md">
                <div class="flex justify-between items-center mb-2">
                    <p class="font-semibold text-gray-700">Attendance Report</p>
                    <div class="text-sm text-gray-500">Sort By: <span class="font-medium">Yearly</span></div>
                </div>
                <!-- Replace this with actual chart -->
                <div class="h-[250px] w-full bg-gray-100 flex items-center justify-center rounded-md">
                    <span class="text-gray-400">[ Line Chart Placeholder ]</span>
                </div>
                <div class="flex justify-center gap-4 mt-4 text-sm">
                    <div class="flex items-center gap-1 text-pink-600"><span
                            class="w-3 h-3 bg-pink-600 rounded-full"></span> Fullstack Dev</div>
                    <div class="flex items-center gap-1 text-sky-400"><span class="w-3 h-3 bg-sky-400 rounded-full"></span>
                        Writer</div>
                    <div class="flex items-center gap-1 text-purple-500"><span
                            class="w-3 h-3 bg-purple-500 rounded-full"></span> Laravel Dev</div>
                </div>
            </div>

            <!-- Pie Chart Placeholder -->
            <div class="bg-white p-4 rounded-xl shadow-md flex flex-col items-center justify-center">
                <p class="font-semibold text-gray-700 mb-2">Average Attendance</p>
                <!-- Replace this with actual chart -->
                <div class="h-[200px] w-[200px] bg-gray-100 flex items-center justify-center rounded-full">
                    <span class="text-gray-400 text-sm">[ Pie Chart ]</span>
                </div>
                <div class="mt-4 text-sm space-y-1">
                    <div class="flex gap-2 items-center"><span class="w-3 h-3 bg-blue-500 rounded-full"></span> Present
                        <span class="ml-2 text-blue-500 font-semibold">61%</span>
                    </div>
                    <div class="flex gap-2 items-center"><span class="w-3 h-3 bg-red-500 rounded-full"></span> Late <span
                            class="ml-2 text-red-500 font-semibold">15%</span></div>
                    <div class="flex gap-2 items-center"><span class="w-3 h-3 bg-pink-400 rounded-full"></span> Absent <span
                            class="ml-2 text-pink-400 font-semibold">8%</span></div>
                    <div class="flex gap-2 items-center"><span class="w-3 h-3 bg-green-400 rounded-full"></span> Permission
                        <span class="ml-2 text-green-500 font-semibold">13%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
