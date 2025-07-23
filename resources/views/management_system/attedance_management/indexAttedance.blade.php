@extends('management_system.templates.layouts')
@section('content')
    <!-- Header -->
    <div class="bg-blue-500 text-white p-4 rounded-lg">
        <h1 class="text-xl font-semibold">Attendance Management</h1>
    </div>

    <div class="flex gap-4 items-center justify-between">
        <form method="GET" action="{{ route('attendances.index') }}" class="flex gap-4 items-center w-full">
            {{-- SEARCH --}}
            <div class="flex items-center bg-white rounded-full shadow-md px-4 py-2 w-[400px]">
                <i class="fa fa-search text-gray-500 mr-2"></i>
                <input type="text" name="search" placeholder="Search by name, role, or date"
                    value="{{ request('search') }}"
                    class="w-full bg-transparent outline-none text-base italic text-gray-700" />
            </div>

            {{-- MONTH FILTER --}}
            <div x-data="{
                open: false,
                selected: '{{ request('month', 'All') }}',
                months: [
                    { label: 'All', value: 'All' },
                    { label: 'January', value: '1' },
                    { label: 'February', value: '2' },
                    { label: 'March', value: '3' },
                    { label: 'April', value: '4' },
                    { label: 'May', value: '5' },
                    { label: 'June', value: '6' },
                    { label: 'July', value: '7' },
                    { label: 'August', value: '8' },
                    { label: 'September', value: '9' },
                    { label: 'October', value: '10' },
                    { label: 'November', value: '11' },
                    { label: 'December', value: '12' }
                ]
            }" class="relative w-48">
                <input type="hidden" name="month" :value="selected">
                <button type="button" @click="open = !open"
                    class="flex items-center bg-white rounded-full shadow-md px-4 py-2 cursor-pointer w-full justify-between border border-gray-300">
                    <span class="italic text-gray-700"
                        x-text="months.find(m => m.value == selected)?.label || 'All'"></span>
                    <i class="fa fa-chevron-down text-gray-500 text-sm"></i>
                </button>
                <ul x-show="open" @click.away="open = false"
                    class="absolute left-0 right-0 mt-2 w-full bg-white border border-gray-200 rounded-md shadow-lg z-20 max-h-72 overflow-y-auto">
                    <template x-for="month in months" :key="month.value">
                        <li @click="selected = month.value; open = false; $nextTick(() => $el.closest('form').submit())"
                            class="px-4 py-2 hover:bg-blue-100 cursor-pointer text-gray-700">
                            <span x-text="month.label"></span>
                        </li>
                    </template>
                </ul>
            </div>

            {{-- YEAR FILTER --}}
            <div x-data="{
                open: false,
                selected: '{{ request('year', date('Y')) }}',
                years: Array.from({ length: {{ date('Y') - 2022 + 1 }} }, (_, i) => (2023 + i).toString())
            }" class="relative w-32">
                <input type="hidden" name="year" :value="selected">
                <button type="button" @click="open = !open"
                    class="flex items-center bg-white rounded-full shadow-md px-4 py-2 cursor-pointer w-full justify-between border border-gray-300">
                    <span class="italic text-gray-700" x-text="selected"></span>
                    <i class="fa fa-chevron-down text-gray-500 text-sm"></i>
                </button>
                <ul x-show="open" @click.away="open = false"
                    class="absolute left-0 right-0 mt-2 w-full bg-white border border-gray-200 rounded-md shadow-lg z-20 max-h-60 overflow-y-auto">
                    <template x-for="year in years" :key="year">
                        <li @click="selected = year; open = false; $nextTick(() => $el.closest('form').submit())"
                            class="px-4 py-2 hover:bg-blue-100 cursor-pointer text-gray-700" x-text="year"></li>
                    </template>
                </ul>
            </div>
        </form>

    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg shadow-lg">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-blue-500 text-white text-left text-sm">
                    <th class="px-4 py-3 font-semibold">Employee</th>
                    <th class="px-4 py-3 font-semibold">Position</th>
                    <th class="px-4 py-3 font-semibold">Date</th>
                    <th class="px-4 py-3 font-semibold">Check in</th>
                    <th class="px-4 py-3 font-semibold">Check out</th>
                    <th class="px-4 py-3 font-semibold">Duration</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Action</th>
                </tr>
            </thead>

            <tbody class="text-sm text-gray-700">
                @forelse ($attendances as $attendance)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $attendance->user->fullname ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $attendance->user->role->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            {{ $attendance->date ? \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-2 underline text-blue-500">
                            @if ($attendance->check_in)
                                <a href="{{ route('attendance.detail.checkin', $attendance->id) }}">
                                    {{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-2 underline">
                            {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}
                        </td>
                        <td class="px-4 py-2">
                            @if ($attendance->check_in && $attendance->check_out)
                                {{ \Carbon\Carbon::parse($attendance->check_in)->diffAsCarbonInterval(\Carbon\Carbon::parse($attendance->check_out))->forHumans(['short' => true]) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if (isset($attendance->status))
                                {{ $attendance->status == 'onTime' ? 'On time' : 'Late' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-2 text-blue-500">
                            <a href="">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            No attendance records found for the selected criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex ml-58 fixed bottom-0 left-0 right-0 justify-between items-center py-8 px-8">
        <button class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200">Previously</button>
        <div class="flex gap-2">
            <button class="px-3 py-1 text-sm rounded-lg">1</button>
            <button class="px-3 py-1 text-sm rounded-lg ">2</button>
            <button class="px-3 py-1 text-sm bg-pink-500 text-white font-semibold rounded-full">3</button>
            <button class="px-3 py-1 text-sm rounded-lg">4</button>
            <button class="px-3 py-1 text-sm rounded-lg">5</button>
        </div>
        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Next</button>
    </div>
@endsection
