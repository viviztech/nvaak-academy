<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Exam Analytics</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $exam->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.exams.rank-list', $exam->id) }}"
               class="px-4 py-2 text-sm font-medium text-white bg-[#1e3a5f] hover:bg-blue-900 rounded-lg">
                View Rank List
            </a>
            <a href="{{ route('admin.exams.index') }}"
               class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Back
            </a>
        </div>
    </div>

    {{-- Overview Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Total Attempts</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $overviewStats['total_attempts'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Average Score</p>
            <p class="text-3xl font-bold text-[#1e3a5f] mt-1">{{ $overviewStats['avg_marks'] }}</p>
            <p class="text-xs text-gray-400">{{ $overviewStats['avg_pct'] }}%</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Pass / Fail</p>
            <p class="mt-1">
                <span class="text-2xl font-bold text-green-600">{{ $overviewStats['pass'] }}</span>
                <span class="text-gray-400 mx-1">/</span>
                <span class="text-2xl font-bold text-red-500">{{ $overviewStats['fail'] }}</span>
            </p>
            @if($overviewStats['total_attempts'] > 0)
                <p class="text-xs text-gray-400">
                    Pass rate: {{ round(($overviewStats['pass'] / $overviewStats['total_attempts']) * 100, 1) }}%
                </p>
            @endif
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Score Range</p>
            <p class="mt-1">
                <span class="text-lg font-bold text-orange-500">{{ $overviewStats['highest'] }}</span>
                <span class="text-gray-400 text-sm mx-1">—</span>
                <span class="text-lg font-bold text-red-400">{{ $overviewStats['lowest'] }}</span>
            </p>
            <p class="text-xs text-gray-400">Highest — Lowest</p>
        </div>
    </div>

    {{-- Analytics Tabs --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        {{-- Tab Nav --}}
        <div class="border-b border-gray-200 px-6">
            <div class="flex space-x-1 -mb-px">
                @foreach(['overview' => 'Overview', 'questions' => 'Question Analysis', 'subjects' => 'Subject Performance'] as $tab => $label)
                    <button wire:click="setTab('{{ $tab }}')"
                            class="px-4 py-3 text-sm font-medium border-b-2 transition-colors
                                   {{ $activeTab === $tab ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="p-6">

            {{-- OVERVIEW TAB --}}
            @if($activeTab === 'overview')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Score Distribution --}}
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Score Distribution</h4>
                        <div class="space-y-3">
                            @foreach($scoreDistribution as $range => $count)
                                @php
                                    $max = max(array_values($scoreDistribution) ?: [1]);
                                    $pct = $max > 0 ? ($count / $max * 100) : 0;
                                    $colors = ['0-20' => 'bg-red-400', '21-40' => 'bg-orange-400', '41-60' => 'bg-yellow-400', '61-80' => 'bg-blue-400', '81-100' => 'bg-green-400'];
                                @endphp
                                <div class="flex items-center space-x-3">
                                    <span class="w-14 text-xs text-gray-600 text-right">{{ $range }}%</span>
                                    <div class="flex-1 bg-gray-100 rounded-full h-6 relative">
                                        <div class="h-6 rounded-full {{ $colors[$range] ?? 'bg-blue-400' }} transition-all"
                                             style="width: {{ $pct }}%"></div>
                                        <span class="absolute inset-0 flex items-center px-2 text-xs font-medium text-gray-700">
                                            {{ $count }} students
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Difficulty Distribution --}}
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Questions by Difficulty</h4>
                        <div class="space-y-3">
                            @foreach(['easy' => ['label' => 'Easy', 'color' => 'bg-green-400'], 'medium' => ['label' => 'Medium', 'color' => 'bg-yellow-400'], 'hard' => ['label' => 'Hard', 'color' => 'bg-orange-400'], 'very_hard' => ['label' => 'Very Hard', 'color' => 'bg-red-500']] as $diff => $info)
                                @php
                                    $count = $difficultyDist[$diff] ?? 0;
                                    $total = array_sum($difficultyDist) ?: 1;
                                    $pct = round(($count / $total) * 100);
                                @endphp
                                <div class="flex items-center space-x-3">
                                    <span class="w-16 text-xs text-gray-600">{{ $info['label'] }}</span>
                                    <div class="flex-1 bg-gray-100 rounded-full h-5 relative">
                                        <div class="h-5 rounded-full {{ $info['color'] }}" style="width: {{ $pct }}%"></div>
                                        <span class="absolute inset-0 flex items-center px-2 text-xs font-medium text-gray-700">
                                            {{ $count }} questions ({{ $pct }}%)
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Time Statistics --}}
                    <div class="md:col-span-2">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Time Statistics</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <p class="text-xs text-gray-500">Average Time Taken</p>
                                @php
                                    $mins = floor($overviewStats['avg_time'] / 60);
                                    $secs = $overviewStats['avg_time'] % 60;
                                @endphp
                                <p class="text-2xl font-bold text-[#1e3a5f] mt-1">{{ $mins }}m {{ $secs }}s</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <p class="text-xs text-gray-500">Pass Rate</p>
                                <p class="text-2xl font-bold text-green-600 mt-1">
                                    {{ $overviewStats['total_attempts'] > 0 ? round(($overviewStats['pass'] / $overviewStats['total_attempts']) * 100, 1) : 0 }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- QUESTION ANALYSIS TAB --}}
            @if($activeTab === 'questions')
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Question</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Correct</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Wrong</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Skipped</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Accuracy</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Avg Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($questionWiseData as $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-500">{{ $row['position'] }}</td>
                                    <td class="px-4 py-3 max-w-xs">
                                        <p class="text-gray-800 truncate text-xs">{{ Str::limit(strip_tags($row['question']->question_text ?? ''), 60) }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-xs px-1.5 py-0.5 rounded {{ $row['question']->type_color ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $row['question']->type_label ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-green-600 font-medium">{{ $row['correct'] }}</td>
                                    <td class="px-4 py-3 text-red-500 font-medium">{{ $row['wrong'] }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $row['skipped'] }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full {{ $row['accuracy'] >= 60 ? 'bg-green-500' : 'bg-red-400' }}"
                                                     style="width: {{ $row['accuracy'] }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium text-gray-700">{{ $row['accuracy'] }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs">{{ $row['avg_time'] }}s</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-12 text-center text-gray-500">No question data available yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- SUBJECT PERFORMANCE TAB --}}
            @if($activeTab === 'subjects')
                <div class="space-y-4">
                    @forelse($subjectWiseData as $sid => $data)
                        <div class="border border-gray-200 rounded-xl p-5 bg-gray-50">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-semibold text-gray-800">{{ $data['subject']?->name ?? "Subject $sid" }}</h4>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-[#1e3a5f]">{{ $data['avg_percent'] }}% avg</span>
                                    <span class="text-xs text-gray-400">({{ $data['total_students'] }} students)</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mb-3">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">Correct</p>
                                    <p class="text-xl font-bold text-green-600">{{ $data['total_correct'] }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">Wrong</p>
                                    <p class="text-xl font-bold text-red-500">{{ $data['total_wrong'] }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">Avg Score</p>
                                    <p class="text-xl font-bold text-gray-700">{{ $data['avg_score'] }}</p>
                                </div>
                            </div>
                            <div class="bg-gray-200 rounded-full h-3">
                                <div class="h-3 rounded-full {{ $data['avg_percent'] >= 60 ? 'bg-green-500' : 'bg-orange-400' }}"
                                     style="width: {{ min($data['avg_percent'], 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-gray-500">
                            <p>No subject data available yet.</p>
                        </div>
                    @endforelse
                </div>
            @endif

        </div>
    </div>
</div>
