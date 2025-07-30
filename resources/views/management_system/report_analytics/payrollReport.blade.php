@extends('management_system.templates.layouts')
@section('content')
    <!-- Header -->
    <div class="flex flex-row gap-2 items-center bg-blue-500 text-white p-4 rounded-lg">
        <button class="text-lg" onclick="window.location.href='{{ url('/indexReportWeb') }}'">Report & Analytics</button>
        <i class="fa-solid fa-angle-right"></i>
        <h1 class="text-xl font-semibold">Payroll Report</h1>
    </div>

    <div class="flex flex-row gap-4 items-center justify-between">
        <div class="flex flex-row items-center gap-3">
            <div class="px-3 py-2 rounded-full shadow-lg cursor-pointer"
                onclick="window.location.href='{{ url('/indexReportWeb') }}'">
                <i class="fa-solid fa-angle-left fa-xl"></i>
            </div>
            <p class="text-black text-xl font-bold">Payroll Report</p>
        </div>

        <div x-data="{
            open: false,
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
            selected: {
                name: '{{ \Carbon\Carbon::create()->month($month)->format('F') }}',
                value: {{ $month }}
            },
            openYear: false,
            years: Array.from({ length: 6 }, (_, i) => new Date().getFullYear() - i),
            selectedYear: {{ $year }},
            // Add auto reload (gunakan route name/URL sesuai)
            init() {
                this.$watch('selected', value => {
                    window.location.href = `{{ route('Payroll Report') }}?month=${value.value}&year=${this.selectedYear}`;
                });
                this.$watch('selectedYear', value => {
                    window.location.href = `{{ route('Payroll Report') }}?month=${this.selected.value}&year=${value}`;
                });
            }
        }" class="relative flex items-center gap-2">
            <button @click="open = !open"
                class="flex items-center bg-white rounded-full shadow-md px-4 py-2 cursor-pointer w-[200px] justify-between">
                <div class="flex items-
                center gap-2">
                    <i class="fa fa-filter text-gray-500"></i>
                    <span class="italic text-gray-700" x-text="selected.name"></span>
                </div>
                <i class="fa fa-chevron-down text-gray-500 text-sm"></i>
            </button>
            <ul x-show="open" @click.away="open = false"
                class="absolute mt-2 w-full bg-white border border-gray-200 rounded-md shadow-lg z-10">
                <template x-for="month in months" :key="month.value">
                    <li @click="selected = month; open = false"
                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-gray-700">
                        <span x-text="month.name"></span>
                    </li>
                </template>
            </ul>
            <!-- Year Dropdown -->
            <div class="relative ml-2">
                <button @click="openYear = !openYear"
                    class="flex items-center bg-white rounded-full shadow-md px-4 py-2 cursor-pointer w-[120px] justify-between">
                    <span class="italic text-gray-700" x-text="selectedYear"></span>
                    <i class="fa fa-chevron-down text-gray-500 text-sm"></i>
                </button>
                <ul x-show="openYear" @click.away="openYear = false"
                    class="absolute mt-2 w-full bg-white border border-gray-200 rounded-md shadow-lg z-10">
                    <template x-for="year in years" :key="year">
                        <li @click="selectedYear = year; openYear = false"
                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-gray-700">
                            <span x-text="year"></span>
                        </li>
                    </template>
                </ul>
            </div>
            <!-- Export Button -->
            <a :href="'{{ route('payroll.export') }}?month=' + selected.value +
                '&year=' + selectedYear"
                class="ml-4 text-white text-md bg-pink-400 rounded-2xl px-5 py-3 font-semibold shadow-md hover:bg-pink-500 transition"
                target="_blank">
                <i class="fa fa-file-pdf mr-2"></i> Export
            </a>
            <script>
                // Optional: reload page on filter change
                $watch('selected', value => {
                    window.location.href = `{{ route('Payroll Report') }}?month=${value.value}&year=${$data.selectedYear}`;
                });
                $watch('selectedYear', value => {
                    window.location.href = `{{ route('Payroll Report') }}?month=${$data.selected.value}&year=${value}`;
                });
            </script>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-blue-500 text-white text-left text-sm">
                    <th class="px-4 py-3 font-semibold text-center">Employee</th>
                    <th class="px-4 py-3 font-semibold text-center">Position</th>
                    <th class="px-4 py-3 font-semibold text-center">Daily Salary</th>
                    <th class="px-4 py-3 font-semibold text-center">Number of Working Days</th>
                    <th class="px-4 py-3 font-semibold text-center">Total Salary</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @foreach ($payrollData as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $row['fullname'] }}</td>
                        <td class="px-4 py-2">{{ $row['position'] }}</td>
                        <td class="px-4 py-2 text-center">{{ number_format($row['salary_perday'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center">{{ number_format($row['workingDays'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center">{{ number_format($row['totalSalary'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
