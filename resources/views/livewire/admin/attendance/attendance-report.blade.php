<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Attendance Report</h2>
            <p class="text-sm text-gray-500 mt-0.5">Generate batch-wise attendance reports</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <div class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-xs font-semibold text-gray-600 mb-1">Batch *</label>
                <select wire:model.live="batchId"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300 bg-white">
                    <option value="">Select Batch</option>
                    @foreach ($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">From Date</label>
                <input wire:model.live="fromDate" type="date"
                       class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">To Date</label>
                <input wire:model.live="toDate" type="date"
                       class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>
            <button wire:click="generateReport"
                    class="px-5 py-2 text-white text-sm font-medium rounded-lg shadow-sm"
                    style="background-color: #f97316;">
                Generate Report
            </button>
        </div>
    </div>

    @if (!empty($report))
        @php
            $avgPct    = count($report) > 0 ? array_sum(array_column($report, 'percentage')) / count($report) : 0;
            $good      = count(array_filter($report, fn($r) => $r['status'] === 'good'));
            $warning   = count(array_filter($report, fn($r) => $r['status'] === 'warning'));
            $critical  = count(array_filter($report, fn($r) => $r['status'] === 'critical'));
        @endphp

        {{-- Summary Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
            <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Average %</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($avgPct, 1) }}%</p>
            </div>
            <div class="bg-white rounded-xl border border-green-200 p-4 text-center">
                <p class="text-xs text-green-600 uppercase tracking-wider">Good (≥75%)</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $good }}</p>
            </div>
            <div class="bg-white rounded-xl border border-yellow-200 p-4 text-center">
                <p class="text-xs text-yellow-600 uppercase tracking-wider">Warning (60–75%)</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $warning }}</p>
            </div>
            <div class="bg-white rounded-xl border border-red-200 p-4 text-center">
                <p class="text-xs text-red-600 uppercase tracking-wider">Critical (&lt;60%)</p>
                <p class="text-2xl font-bold text-red-600 mt-1">{{ $critical }}</p>
            </div>
        </div>

        {{-- Report Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Present</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Absent</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Working Days</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">Attendance %</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($report as $row)
                        @php
                            $barColor = match($row['status']) {
                                'good'     => 'bg-green-500',
                                'warning'  => 'bg-yellow-500',
                                'critical' => 'bg-red-500',
                                default    => 'bg-gray-400',
                            };
                            $badgeColor = match($row['status']) {
                                'good'     => 'bg-green-100 text-green-700',
                                'warning'  => 'bg-yellow-100 text-yellow-700',
                                'critical' => 'bg-red-100 text-red-700',
                                default    => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $row['name'] }}</td>
                            <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $row['student_code'] }}</td>
                            <td class="px-4 py-3 text-center font-medium text-green-600">{{ $row['present'] }}</td>
                            <td class="px-4 py-3 text-center font-medium text-red-500">{{ $row['absent'] }}</td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $row['working_days'] }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $barColor }}" style="width: {{ $row['percentage'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700 w-10 text-right">{{ $row['percentage'] }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                    {{ ucfirst($row['status']) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif ($batchId)
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-400">
            <p class="font-medium">Click "Generate Report" to load attendance data</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="font-medium">Select a batch to generate report</p>
            <p class="text-xs mt-1">Choose a batch and date range, then click Generate Report</p>
        </div>
    @endif
</div>
