@extends('management_system.templates.layouts')
@section('content')
    <!-- Header -->
    <div class="flex flex-row gap-2 items-center bg-blue-500 text-white p-4 rounded-lg">
        <button class="text-lg" onclick="window.location.href='{{ url('/indexReportWeb') }}'">Report & Analytics</button>
        <i class="fa-solid fa-angle-right"></i>
        <h1 class="text-xl font-semibold">Attendance Report</h1>
    </div>

    <div class="flex flex-row gap-4 items-center justify-between">
        <div class="flex flex-row items-center gap-3">
            <div class="px-3 py-2 rounded-full shadow-lg cursor-pointer"
                onclick="window.location.href='{{ url('/indexReportWeb') }}'">
                <i class="fa-solid fa-angle-left fa-xl"></i>
            </div>
            <p class="text-black text-xl font-bold">Attendance Report</p>
        </div>

        <!-- FILTER BULAN & TAHUN + EXPORT BUTTON DINAMIS -->
        <div x-data="{
            openMonth: false,
            openYear: false,
            months: [
                { name: 'January', value: 1 },
                { name: 'February', value: 2 },
                { name: 'March', value: 3 },
                { name: 'April', value: 4 },
                { name: 'May', value: 5 },
                { name: 'June', value: 6 },
                { name: 'July', value: 7 },
                { name: 'August', value: 8 },
                { name: 'September', value: 9 },
                { name: 'October', value: 10 },
                { name: 'November', value: 11 },
                { name: 'December', value: 12 }
            ],
            years: Array.from({ length: 6 }, (_, i) => new Date().getFullYear() - i),
            selectedMonth: Number(new URLSearchParams(window.location.search).get('month')) || (new Date().getMonth() + 1),
            selectedYear: Number(new URLSearchParams(window.location.search).get('year')) || new Date().getFullYear(),
        
            // Method untuk reload ke url baru dengan query month & year
            updateUrl() {
                const params = new URLSearchParams(window.location.search);
                params.set('month', this.selectedMonth);
                params.set('year', this.selectedYear);
                // Reload halaman dengan query baru
                window.location.search = params.toString();
            }
        }" class="flex flex-row gap-2 items-center">
            <!-- Dropdown Bulan -->
            <div class="relative">
                <button @click="openMonth = !openMonth"
                    class="flex items-center bg-white rounded-full shadow-md px-4 py-2 cursor-pointer w-[170px] justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa fa-filter text-gray-500"></i>
                        <span class="italic text-gray-700" x-text="months.find(m => m.value === selectedMonth).name"></span>
                    </div>
                    <i class="fa fa-chevron-down text-gray-500 text-sm"></i>
                </button>
                <ul x-show="openMonth" @click.away="openMonth = false"
                    class="absolute mt-2 w-full bg-white border border-gray-200 rounded-md shadow-lg z-10">
                    <template x-for="month in months" :key="month.value">
                        <li @click="selectedMonth = month.value; openMonth = false; updateUrl()"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-gray-700">
                            <span x-text="month.name"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- Dropdown Tahun -->
            <div class="relative">
                <button @click="openYear = !openYear"
                    class="flex items-center bg-white rounded-full shadow-md px-4 py-2 cursor-pointer w-[120px] justify-between">
                    <span class="italic text-gray-700" x-text="selectedYear"></span>
                    <i class="fa fa-chevron-down text-gray-500 text-sm"></i>
                </button>
                <ul x-show="openYear" @click.away="openYear = false"
                    class="absolute mt-2 w-full bg-white border border-gray-200 rounded-md shadow-lg z-10">
                    <template x-for="year in years" :key="year">
                        <li @click="selectedYear = year; openYear = false; updateUrl()"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-gray-700">
                            <span x-text="year"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <!-- Tombol Export PDF -->
            <a :href="'{{ route('attendance.export') }}?month=' + selectedMonth + '&year=' + selectedYear"
                class="ml-4 text-white text-md bg-pink-400 rounded-2xl px-5 py-3 font-semibold shadow-md hover:bg-pink-500 transition"
                target="_blank">
                <i class="fa fa-file-pdf mr-2"></i> Export
            </a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-blue-500 text-white text-left text-sm">
                    <th class="px-4 py-3 font-semibold text-center">Employee</th>
                    <th class="px-4 py-3 font-semibold text-center">Position</th>
                    <th class="px-4 py-3 font-semibold text-center">Number of working days</th>
                    <th class="px-4 py-3 font-semibold text-center">On Time</th>
                    <th class="px-4 py-3 font-semibold text-center">Late</th>
                    <th class="px-4 py-3 font-semibold text-center">Permission</th>
                    <th class="px-4 py-3 font-semibold text-center">Total Work Duration</th>
                </tr>
            </thead>
            @foreach ($reportData as $report)
                <tbody class="text-sm text-gray-700">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $report['user']->fullname }}</td>
                        <td class="px-4 py-2">{{ $report['user']->role->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ $report['totalDays'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $report['onTime'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $report['late'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $report['permission'] }}</td>
                        <td class="px-4 py-2 text-center">{{ $report['workDuration'] }} hours </td>
                    </tr>
                </tbody>
            @endforeach
        </table>
    </div>
@endsection
