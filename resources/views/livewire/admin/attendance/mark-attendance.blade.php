<div>
    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Mark Attendance</h2>
            <p class="text-sm text-gray-500 mt-0.5">Select a batch and date to mark attendance</p>
        </div>
        @if ($isSaved && !empty($students))
            <div class="flex items-center gap-2 px-3 py-2 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Already marked — you can update
            </div>
        @endif
    </div>

    {{-- Controls --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Date</label>
                <input wire:model.live="selectedDate" type="date"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Batch</label>
                <select wire:model.live="selectedBatchId"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">
                    <option value="">Select Batch</option>
                    @foreach ($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Bulk actions --}}
        @if (!empty($students))
            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100">
                <span class="text-xs text-gray-500 font-medium">Mark all as:</span>
                <button wire:click="setAll('present')"
                        class="px-3 py-1 text-xs font-medium rounded-lg bg-green-100 text-green-700 hover:bg-green-200 transition-colors">
                    Present
                </button>
                <button wire:click="setAll('absent')"
                        class="px-3 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition-colors">
                    Absent
                </button>
                <button wire:click="setAll('late')"
                        class="px-3 py-1 text-xs font-medium rounded-lg bg-orange-100 text-orange-700 hover:bg-orange-200 transition-colors">
                    Late
                </button>
                <button wire:click="setAll('on_leave')"
                        class="px-3 py-1 text-xs font-medium rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                    On Leave
                </button>
            </div>
        @endif
    </div>

    {{-- Attendance Table --}}
    @if (!empty($students))
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">#</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Roll No</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider" colspan="4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($students as $idx => $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $student['name'] }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $student['code'] }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $student['roll'] }}</td>
                            <td class="px-4 py-3" colspan="4">
                                <div class="flex gap-2 flex-wrap">
                                    @foreach ([
                                        'present'  => ['label' => 'Present',  'selected' => 'bg-green-500 text-white border-green-500',  'default' => 'border-green-300 text-green-700 hover:bg-green-50'],
                                        'absent'   => ['label' => 'Absent',   'selected' => 'bg-red-500 text-white border-red-500',    'default' => 'border-red-300 text-red-700 hover:bg-red-50'],
                                        'late'     => ['label' => 'Late',     'selected' => 'bg-orange-500 text-white border-orange-500', 'default' => 'border-orange-300 text-orange-700 hover:bg-orange-50'],
                                        'on_leave' => ['label' => 'Leave',    'selected' => 'bg-blue-500 text-white border-blue-500',   'default' => 'border-blue-300 text-blue-700 hover:bg-blue-50'],
                                    ] as $statusVal => $config)
                                        <label class="flex items-center gap-1.5 px-3 py-1.5 border rounded-lg cursor-pointer text-xs font-medium transition-colors
                                                      {{ ($attendanceData[$student['id']] ?? 'present') === $statusVal ? $config['selected'] : $config['default'] }}">
                                            <input type="radio"
                                                   wire:model.live="attendanceData.{{ $student['id'] }}"
                                                   value="{{ $statusVal }}"
                                                   class="sr-only">
                                            {{ $config['label'] }}
                                        </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ count($students) }} students in this batch</p>
            <button wire:click="saveAttendance"
                    wire:loading.attr="disabled"
                    class="px-6 py-2.5 text-white font-semibold rounded-xl shadow-sm transition-opacity disabled:opacity-60"
                    style="background-color: #f97316;">
                <span wire:loading.remove>Save Attendance</span>
                <span wire:loading>Saving...</span>
            </button>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-gray-500 font-medium">Select a batch and date to begin</p>
            <p class="text-xs text-gray-400 mt-1">Student attendance list will appear here</p>
        </div>
    @endif
</div>
