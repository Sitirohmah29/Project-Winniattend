@extends('management_system.templates.layouts')
@section('content')
    <!-- Header -->
    <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
        <h1 class="text-xl font-semibold">Security & Settings</h1>
    </div>

    <!-- Report Section -->
    <div>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Report</h2>
        <div class="flex gap-4">
            <button
                class="flex items-center justify-center w-[250px] gap-2 px-6 py-8 rounded-lg bg-gradient-to-r from-pink-400 to-blue-500 text-white font-medium shadow-lg hover:opacity-90">
                <i class="fa-solid fa-file text-white"></i>
                <span class="text-lg">Log Activity</span>
            </button>

            <button
                class="flex items-center justify-center w-[250px] gap-2 px-6 py-8 rounded-lg bg-gradient-to-r from-pink-400 to-purple-500 text-white font-medium shadow-lg hover:opacity-90">
                <i class="fa-solid fa-file-invoice-dollar text-white"></i>
                <span class="text-lg">Data Encryption</span>
            </button>

            <button
                class="flex items-center justify-center w-[250px] gap-2 px-6 py-8 rounded-lg bg-gradient-to-r from-pink-400 to-purple-500 text-white font-medium shadow-lg hover:opacity-90">
                <i class="fa-solid fa-file-invoice-dollar text-white"></i>
                <span class="text-lg">Data Backup</span>
            </button>
        </div>
    </div>
@endsection
