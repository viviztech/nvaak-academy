<div>
    @if($saved)
        <div class="mb-5 flex items-start gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-4"
             x-data x-init="setTimeout(() => { $el.remove(); $wire.set('saved', false); }, 4000)">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-green-800">Attendance saved successfully!</p>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Batch</label>
                <select wire:model.live="selectedBatch"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent bg-white">
                    <option value="">Select batch...</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch['id'] }}">{{ $batch['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Date</label>
                <input type="date" wire:model.live="date"
                       max="{{ date('Y-m-d') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent">
            </div>
            <div class="flex items-end">
                @if($selectedBatch)
                    <button wire:click="loadStudents"
                            class="w-full bg-[#1e3a5f] hover:bg-blue-900 text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Attendance Table --}}
    @if(!$selectedBatch)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center py-20 text-gray-400">
            <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-sm">Select a batch to mark attendance.</p>
        </div>
    @elseif(empty($students))
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center py-20 text-gray-400">
            <p class="text-sm">No students found in this batch.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-base font-semibold text-gray-800">
                        Mark Attendance — {{ collect($batches)->firstWhere('id', (int)$selectedBatch)['name'] ?? '' }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Date: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                        &middot; {{ count($students) }} students
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button wire:click="$set('attendanceData', array_fill_keys(array_column($students, 'id'), 'present'))"
                            class="text-xs font-medium text-green-600 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors">
                        Mark All Present
                    </button>
                    <button wire:click="$set('attendanceData', array_fill_keys(array_column($students, 'id'), 'absent'))"
                            class="text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                        Mark All Absent
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">#</th>
                            <th class="text-left px-6 py-3">Student Name</th>
                            <th class="text-left px-6 py-3">Roll No.</th>
                            <th class="px-6 py-3 text-center">Present</th>
                            <th class="px-6 py-3 text-center">Absent</th>
                            <th class="px-6 py-3 text-center">Late</th>
                            <th class="px-6 py-3 text-center">Leave</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($students as $idx => $student)
                            <tr class="hover:bg-gray-50 transition-colors
                                {{ ($attendanceData[$student['id']] ?? '') === 'absent' ? 'bg-red-50/40' : '' }}
                                {{ ($attendanceData[$student['id']] ?? '') === 'late' ? 'bg-yellow-50/40' : '' }}">
                                <td class="px-6 py-3 text-gray-400 text-xs">{{ $idx + 1 }}</td>
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $student['name'] }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $student['roll_number'] ?? '—' }}</td>
                                @foreach(['present', 'absent', 'late', 'leave'] as $status)
                                    <td class="px-6 py-3 text-center">
                                        <input type="radio"
                                               wire:model="attendanceData.{{ $student['id'] }}"
                                               value="{{ $status }}"
                                               class="w-4 h-4 cursor-pointer
                                                   {{ $status === 'present' ? 'accent-green-500' : '' }}
                                                   {{ $status === 'absent' ? 'accent-red-500' : '' }}
                                                   {{ $status === 'late' ? 'accent-yellow-500' : '' }}
                                                   {{ $status === 'leave' ? 'accent-blue-500' : '' }}">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <div class="flex gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-green-400 inline-block"></span>
                        Present: {{ collect($attendanceData)->filter(fn($v) => $v === 'present')->count() }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span>
                        Absent: {{ collect($attendanceData)->filter(fn($v) => $v === 'absent')->count() }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-yellow-400 inline-block"></span>
                        Late: {{ collect($attendanceData)->filter(fn($v) => $v === 'late')->count() }}
                    </span>
                </div>
                <button wire:click="saveAttendance"
                        class="bg-[#1e3a5f] hover:bg-blue-900 text-white font-semibold text-sm py-2.5 px-6 rounded-xl transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Attendance
                </button>
            </div>
        </div>
    @endif
</div>
