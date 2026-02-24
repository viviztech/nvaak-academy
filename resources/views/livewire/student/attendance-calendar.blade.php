<div>
    {{-- Month Navigation --}}
    <div class="flex items-center justify-between mb-6">
        <button wire:click="prevMonth"
                class="p-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <h2 class="text-xl font-bold text-gray-900">{{ $monthLabel }}</h2>
        <button wire:click="nextMonth"
                class="p-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-4 gap-3 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-center">
            <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Present</p>
            <p class="text-2xl font-bold text-green-700 mt-1">{{ $stats['present'] }}</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-center">
            <p class="text-xs font-semibold text-red-600 uppercase tracking-wider">Absent</p>
            <p class="text-2xl font-bold text-red-700 mt-1">{{ $stats['absent'] }}</p>
        </div>
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-3 text-center">
            <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Late</p>
            <p class="text-2xl font-bold text-orange-700 mt-1">{{ $stats['late'] }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 text-center">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">On Leave</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">{{ $stats['on_leave'] }}</p>
        </div>
    </div>

    @if (empty($calendar))
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="font-medium">No attendance data available</p>
            <p class="text-xs mt-1">You may not be enrolled in an active batch.</p>
        </div>
    @else
        {{-- Calendar Grid --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-5">
            {{-- Week headers --}}
            <div class="grid grid-cols-7 border-b border-gray-100 bg-gray-50">
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="py-2 text-center text-xs font-semibold text-gray-500">{{ $day }}</div>
                @endforeach
            </div>

            @php
                // Build weeks
                $days        = array_values($calendar);
                $firstDay    = \Carbon\Carbon::parse($days[0]['date']);
                $startDow    = (int)$firstDay->format('w'); // 0=Sun, 6=Sat
                $calDays     = array_merge(array_fill(0, $startDow, null), $days);
                $totalCells  = ceil(count($calDays) / 7) * 7;
                $calDays     = array_pad($calDays, $totalCells, null);
                $weeks       = array_chunk($calDays, 7);
                $today       = today()->toDateString();

                $statusClasses = [
                    'present'  => 'bg-green-100 border-green-300 text-green-800',
                    'absent'   => 'bg-red-100 border-red-300 text-red-800',
                    'late'     => 'bg-orange-100 border-orange-300 text-orange-800',
                    'on_leave' => 'bg-blue-100 border-blue-300 text-blue-800',
                    'holiday'  => 'bg-gray-100 border-gray-200 text-gray-400',
                    'unmarked' => 'bg-white border-gray-100 text-gray-400',
                ];
            @endphp

            @foreach ($weeks as $week)
                <div class="grid grid-cols-7 border-b border-gray-50 last:border-0">
                    @foreach ($week as $dayData)
                        @if ($dayData === null)
                            <div class="h-16 border border-gray-50 bg-gray-50/50"></div>
                        @else
                            @php
                                $status       = $dayData['status'];
                                $isToday      = $dayData['date'] === $today;
                                $isFuture     = $dayData['is_future'];
                                $cellClass    = $isFuture && $status === 'unmarked'
                                    ? 'bg-white border-gray-100 text-gray-300'
                                    : ($status === 'unmarked' && !$isFuture
                                        ? 'bg-yellow-50 border-yellow-100 text-gray-500'
                                        : ($statusClasses[$status] ?? 'bg-white border-gray-100 text-gray-500'));
                                $ring         = $isToday ? 'ring-2 ring-inset ring-indigo-500' : '';
                            @endphp
                            <div class="h-16 border {{ $cellClass }} {{ $ring }} flex flex-col items-center justify-center p-1 transition-all">
                                <span class="text-sm font-semibold">{{ $dayData['day'] }}</span>
                                @if (!$isFuture && $status !== 'unmarked')
                                    <span class="text-xs mt-0.5 capitalize leading-none">
                                        @if ($status === 'present') P
                                        @elseif ($status === 'absent') A
                                        @elseif ($status === 'late') L
                                        @elseif ($status === 'on_leave') OL
                                        @elseif ($status === 'holiday') H
                                        @endif
                                    </span>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    @endif

    {{-- Overall Percentage --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-5">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm font-semibold text-gray-700">Overall Attendance</p>
            <p class="text-lg font-bold {{ $overallPercentage >= 75 ? 'text-green-600' : ($overallPercentage >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                {{ number_format($overallPercentage, 1) }}%
            </p>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="h-3 rounded-full transition-all duration-500 {{ $overallPercentage >= 75 ? 'bg-green-500' : ($overallPercentage >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                 style="width: {{ min(100, $overallPercentage) }}%"></div>
        </div>
        @if ($overallPercentage < 75)
            <p class="text-xs text-red-500 mt-2">
                Attendance below 75%. Please maintain regular attendance.
            </p>
        @endif
    </div>

    {{-- Legend --}}
    <div class="flex flex-wrap gap-3 text-xs">
        @foreach ([
            ['bg-green-100 border-green-300', 'Present'],
            ['bg-red-100 border-red-300', 'Absent'],
            ['bg-orange-100 border-orange-300', 'Late'],
            ['bg-blue-100 border-blue-300', 'On Leave'],
            ['bg-gray-100 border-gray-200', 'Holiday/Sunday'],
            ['bg-yellow-50 border-yellow-100', 'Unmarked'],
        ] as [$cls, $label])
            <div class="flex items-center gap-1.5">
                <div class="w-4 h-4 rounded border {{ $cls }}"></div>
                <span class="text-gray-600">{{ $label }}</span>
            </div>
        @endforeach
    </div>
</div>
