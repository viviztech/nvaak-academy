<div>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Analytics Dashboard</h2>
        <p class="text-sm text-gray-500 mt-0.5">Performance insights across all batches</p>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($this->stats['total_students']) }}</p>
            <p class="text-sm text-gray-500">Total Students</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $this->stats['avg_attendance'] }}%</p>
            <p class="text-sm text-gray-500">Avg Attendance</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $this->stats['avg_exam_score'] }}%</p>
            <p class="text-sm text-gray-500">Avg Exam Score</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($this->stats['exams_conducted']) }}</p>
            <p class="text-sm text-gray-500">Exams Conducted</p>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="flex gap-3 mb-6">
        <a href="{{ route('admin.analytics.rank-predictor') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg transition-colors"
           style="background-color: #f97316;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            NEET Rank Predictor
        </a>
    </div>

    <!-- Batch Performance Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Batch Performance Summary</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Batch</th>
                        <th class="text-center px-5 py-3">Students</th>
                        <th class="text-center px-5 py-3">Avg Score</th>
                        <th class="text-center px-5 py-3">Pass Rate</th>
                        <th class="text-center px-5 py-3">Results</th>
                        <th class="text-left px-5 py-3">Top Scorer</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($this->batchPerformance as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <p class="text-sm font-medium text-gray-800">{{ $item['batch']->name }}</p>
                                <p class="text-xs text-gray-400 uppercase">{{ $item['batch']->course_type }}</p>
                            </td>
                            <td class="px-5 py-3 text-center text-sm text-gray-700">{{ $item['students_count'] }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $item['avg_score'] >= 70 ? 'bg-green-100 text-green-700' : ($item['avg_score'] >= 50 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $item['avg_score'] }}%
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $item['pass_rate'] >= 70 ? 'bg-green-500' : ($item['pass_rate'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                             style="width: {{ $item['pass_rate'] }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $item['pass_rate'] }}%</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-center text-sm text-gray-600">{{ $item['total_results'] }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $item['top_scorer'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">No performance data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Exam Results -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Recent Exam Results</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Student</th>
                        <th class="text-left px-5 py-3">Exam</th>
                        <th class="text-center px-5 py-3">Score</th>
                        <th class="text-center px-5 py-3">Percentage</th>
                        <th class="text-center px-5 py-3">Rank</th>
                        <th class="text-left px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($this->recentResults as $result)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-sm font-medium text-gray-800">{{ $result->student?->user?->name }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $result->exam?->title }}</td>
                            <td class="px-5 py-3 text-center text-sm text-gray-700">{{ $result->marks_obtained }}/{{ $result->total_marks }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="text-sm font-semibold {{ $result->percentage >= 70 ? 'text-green-600' : ($result->percentage >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ round($result->percentage, 1) }}%
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center text-sm text-gray-600">{{ $result->rank ?? '-' }}</td>
                            <td class="px-5 py-3 text-sm text-gray-500">{{ $result->created_at?->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">No exam results yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
