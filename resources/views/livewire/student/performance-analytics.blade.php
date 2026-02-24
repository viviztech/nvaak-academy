<div>
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">My Performance</h2>
        <p class="text-sm text-gray-500 mt-0.5">Track your progress and predicted NEET rank</p>
    </div>

    @if($snapshot)
        <!-- Performance Cards -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <!-- Overall Score -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-sm font-medium text-gray-500 mb-1">Overall Score</p>
                <p class="text-4xl font-bold text-gray-800">{{ $snapshot->overall_score_percent }}%</p>
                <div class="mt-3 w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $snapshot->overall_score_percent >= 70 ? 'bg-green-500' : ($snapshot->overall_score_percent >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                         style="width: {{ $snapshot->overall_score_percent }}%"></div>
                </div>
                @if($snapshot->improvement_from_last !== null)
                    <p class="text-xs mt-2 font-medium {{ $snapshot->improvement_from_last >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $snapshot->improvement_from_last >= 0 ? '&#8593;' : '&#8595;' }}
                        {{ abs($snapshot->improvement_from_last) }}% from last week
                    </p>
                @endif
            </div>

            <!-- Batch Rank -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-sm font-medium text-gray-500 mb-1">Batch Rank</p>
                <p class="text-4xl font-bold" style="color: #1e3a5f;">
                    #{{ $snapshot->rank_in_batch ?? 'N/A' }}
                </p>
                <p class="text-xs text-gray-400 mt-3">Based on {{ $snapshot->exams_attempted }} exams attempted</p>
                <p class="text-xs text-gray-400">Last updated: {{ $snapshot->snapshot_date?->format('d M Y') }}</p>
            </div>

            <!-- Attendance -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-sm font-medium text-gray-500 mb-1">Attendance</p>
                <div class="flex items-end gap-2">
                    <p class="text-4xl font-bold text-gray-800">{{ $snapshot->attendance_percent }}%</p>
                </div>
                <div class="mt-3 w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $snapshot->attendance_percent >= 75 ? 'bg-green-500' : 'bg-red-500' }}"
                         style="width: {{ min($snapshot->attendance_percent, 100) }}%"></div>
                </div>
                <p class="text-xs mt-1 {{ $snapshot->attendance_percent >= 75 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $snapshot->attendance_percent >= 75 ? 'Good attendance' : 'Below 75% threshold' }}
                </p>
            </div>
        </div>

        <!-- NEET Prediction Banner -->
        @if(!empty($prediction['score']))
            <div class="bg-gradient-to-r from-indigo-900 to-blue-800 rounded-xl p-5 text-white mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-200 text-sm">Predicted NEET Score</p>
                        <p class="text-5xl font-bold mt-1">{{ $prediction['score'] }}</p>
                        <p class="text-indigo-300 text-sm">out of 720</p>
                    </div>
                    <div class="text-right">
                        <p class="text-indigo-200 text-sm">Estimated Rank</p>
                        <div class="mt-1 inline-flex px-3 py-1.5 bg-white/20 rounded-lg">
                            <span class="font-semibold text-sm">{{ $prediction['rank']['label'] ?? 'N/A' }}</span>
                        </div>
                        <p class="text-indigo-400 text-xs mt-2">Based on NEET 2024 data</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Subject-wise Scores -->
        @if($snapshot->subject_scores && count($snapshot->subject_scores))
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
                <h3 class="font-semibold text-gray-800 mb-4">Subject-wise Performance</h3>
                <div class="space-y-4">
                    @foreach($snapshot->subject_scores as $score)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $score['name'] }}</span>
                                <span class="text-sm font-bold {{ ($score['percentage'] ?? 0) >= 70 ? 'text-green-600' : (($score['percentage'] ?? 0) >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $score['percentage'] ?? 0 }}%
                                </span>
                            </div>
                            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500
                                    {{ ($score['percentage'] ?? 0) >= 70 ? 'bg-green-500' : (($score['percentage'] ?? 0) >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                     style="width: {{ $score['percentage'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-12 text-center mb-6">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-indigo-100">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <p class="text-gray-700 font-semibold">Performance data not yet available</p>
            <p class="text-gray-400 text-sm mt-1">Your performance snapshot will be generated after your first exam.</p>
        </div>
    @endif

    <!-- Exam History Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Exam History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="text-left px-5 py-3">Exam Name</th>
                        <th class="text-center px-5 py-3">Type</th>
                        <th class="text-center px-5 py-3">Score</th>
                        <th class="text-center px-5 py-3">Percentage</th>
                        <th class="text-center px-5 py-3">Rank</th>
                        <th class="text-left px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($this->examHistory as $result)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-sm font-medium text-gray-800">{{ $result->exam?->title }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 uppercase">
                                    {{ $result->exam?->exam_type ?? 'exam' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center text-sm text-gray-700">{{ $result->marks_obtained }}/{{ $result->total_marks }}</td>
                            <td class="px-5 py-3 text-center">
                                <span class="text-sm font-bold {{ $result->percentage >= 70 ? 'text-green-600' : ($result->percentage >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ round($result->percentage, 1) }}%
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center text-sm text-gray-600">{{ $result->rank ?? '-' }}</td>
                            <td class="px-5 py-3 text-sm text-gray-500">{{ $result->created_at?->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400">No exam history yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
