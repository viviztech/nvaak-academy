<div class="max-w-4xl mx-auto">
    {{-- Result Pending --}}
    @if(!$result || !$result->is_published)
        <div class="py-16 text-center">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-800">Result Not Published Yet</h2>
            <p class="text-gray-500 text-sm mt-2">Your exam has been submitted. Results will be published by the admin.</p>
            <a href="{{ route('student.exams.index') }}"
               class="inline-flex mt-6 px-5 py-2.5 text-sm font-medium text-white bg-[#1e3a5f] rounded-xl hover:bg-blue-900">
                Back to My Exams
            </a>
        </div>
        @return
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Exam Result</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $exam->name }}</p>
        </div>
        <a href="{{ route('student.exams.index') }}"
           class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            Back to Exams
        </a>
    </div>

    {{-- Scorecard --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-6">
        <div class="bg-{{ $result->pass_fail === 'pass' ? 'green' : 'red' }}-600 p-6 text-white text-center">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-white bg-opacity-20 mb-3">
                {{ strtoupper($result->pass_fail) }}
            </div>
            <h3 class="text-4xl font-black">{{ $result->marks_obtained }}</h3>
            <p class="text-sm opacity-80 mt-1">out of {{ $result->total_marks }} marks</p>
            <p class="text-2xl font-bold mt-2">{{ $result->percentage }}%</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 divide-x divide-y md:divide-y-0 divide-gray-200">
            <div class="p-4 text-center">
                <p class="text-xs text-gray-500">Correct</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $result->correct_answers }}</p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-gray-500">Wrong</p>
                <p class="text-2xl font-bold text-red-500 mt-1">{{ $result->wrong_answers }}</p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-gray-500">Skipped</p>
                <p class="text-2xl font-bold text-gray-500 mt-1">{{ $result->unattempted }}</p>
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-gray-500">Batch Rank</p>
                <p class="text-2xl font-bold text-[#1e3a5f] mt-1">#{{ $batchRank ?? '-' }}</p>
                @if($totalInBatch)
                    <p class="text-xs text-gray-400">of {{ $totalInBatch }}</p>
                @endif
            </div>
            <div class="p-4 text-center">
                <p class="text-xs text-gray-500">Percentile</p>
                <p class="text-2xl font-bold text-orange-500 mt-1">{{ $percentile ?? '-' }}</p>
                @if($percentile)
                    <p class="text-xs text-gray-400">%ile</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200 mb-6">
        <div class="flex space-x-1">
            @foreach(['scorecard' => 'Subject Breakdown', 'analysis' => 'Strength & Weakness', 'solutions' => 'Solutions'] as $tab => $label)
                @if($tab !== 'solutions' || $showSolutions)
                    <button wire:click="setTab('{{ $tab }}')"
                            class="px-4 py-3 text-sm font-medium border-b-2 transition-colors
                                   {{ $activeTab === $tab ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </button>
                @endif
            @endforeach
        </div>
    </div>

    {{-- SUBJECT BREAKDOWN TAB --}}
    @if($activeTab === 'scorecard')
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            @if($result->subject_wise_scores)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Subject</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Score</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Correct</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Wrong</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Skipped</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Accuracy</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($result->subject_wise_scores as $sid => $scores)
                                @php
                                    $attempted = ($scores['correct'] ?? 0) + ($scores['wrong'] ?? 0);
                                    $accuracy  = $attempted > 0 ? round(($scores['correct'] / $attempted) * 100, 1) : 0;
                                    $pct       = ($scores['total'] ?? 0) > 0 ? round((($scores['obtained'] ?? 0) / $scores['total']) * 100, 1) : 0;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-gray-800">{{ $subjects->get($sid)?->name ?? "Subject $sid" }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="font-bold text-gray-800">{{ $scores['obtained'] ?? 0 }}</span>
                                        <span class="text-gray-400 text-xs">/ {{ $scores['total'] ?? 0 }}</span>
                                        <div class="mt-1 w-24 mx-auto bg-gray-200 rounded-full h-1.5">
                                            <div class="h-1.5 rounded-full {{ $pct >= 60 ? 'bg-green-500' : 'bg-orange-400' }}"
                                                 style="width: {{ min($pct, 100) }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center text-green-600 font-semibold">{{ $scores['correct'] ?? 0 }}</td>
                                    <td class="px-5 py-4 text-center text-red-500 font-semibold">{{ $scores['wrong'] ?? 0 }}</td>
                                    <td class="px-5 py-4 text-center text-gray-500">{{ $scores['unattempted'] ?? 0 }}</td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="font-medium {{ $accuracy >= 70 ? 'text-green-600' : ($accuracy >= 50 ? 'text-orange-500' : 'text-red-500') }}">
                                            {{ $accuracy }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-gray-500">Subject data not available.</div>
            @endif
        </div>
    @endif

    {{-- STRENGTH & WEAKNESS TAB --}}
    @if($activeTab === 'analysis')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Strengths --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Strengths</h3>
                </div>
                @if(count($strengths) > 0)
                    <div class="space-y-3">
                        @foreach($strengths as $s)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">{{ $s['subject'] }}</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 bg-gray-100 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-green-500" style="width: {{ $s['accuracy'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-green-600">{{ $s['accuracy'] }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No strong areas identified. Keep practicing!</p>
                @endif
            </div>

            {{-- Weaknesses --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Areas to Improve</h3>
                </div>
                @if(count($weaknesses) > 0)
                    <div class="space-y-3">
                        @foreach($weaknesses as $w)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-700">{{ $w['subject'] }}</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 bg-gray-100 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-red-400" style="width: {{ $w['accuracy'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-red-500">{{ $w['accuracy'] }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Great job! No major weak areas found.</p>
                @endif
            </div>

            {{-- Time Analysis --}}
            @if($result->time_taken_seconds)
                @php
                    $mins = floor($result->time_taken_seconds / 60);
                    $secs = $result->time_taken_seconds % 60;
                    $totalMins = $exam->duration_minutes;
                    $usedPct = $totalMins > 0 ? round(($mins / $totalMins) * 100, 1) : 0;
                @endphp
                <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-800 mb-4">Time Analysis</h3>
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Time Used</p>
                            <p class="text-2xl font-bold text-[#1e3a5f]">{{ $mins }}m {{ $secs }}s</p>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>0</span>
                                <span>{{ $totalMins }} min</span>
                            </div>
                            <div class="bg-gray-200 rounded-full h-4">
                                <div class="h-4 rounded-full bg-[#1e3a5f]" style="width: {{ min($usedPct, 100) }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $usedPct }}% of allotted time used</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Avg per question</p>
                            @php $avgPerQ = count($solutions) > 0 ? round($result->time_taken_seconds / max(1, $result->correct_answers + $result->wrong_answers + $result->unattempted)) : 0; @endphp
                            <p class="text-2xl font-bold text-gray-700">{{ $avgPerQ }}s</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- SOLUTIONS TAB --}}
    @if($activeTab === 'solutions' && $showSolutions)
        <div class="space-y-5">
            @forelse($solutions as $index => $sol)
                @php
                    $q = $sol['question'];
                    $statusColor = $sol['is_skipped']
                        ? 'border-gray-200'
                        : ($sol['is_correct'] ? 'border-green-400' : 'border-red-400');
                    $statusBg = $sol['is_skipped']
                        ? 'bg-gray-50'
                        : ($sol['is_correct'] ? 'bg-green-50' : 'bg-red-50');
                @endphp
                <div class="bg-white rounded-xl border-2 {{ $statusColor }} overflow-hidden">
                    <div class="px-5 py-3 flex items-center justify-between {{ $statusBg }}">
                        <span class="text-sm font-bold text-gray-700">Question {{ $index + 1 }}</span>
                        <div class="flex items-center space-x-3">
                            @if($sol['is_skipped'])
                                <span class="px-2.5 py-0.5 text-xs font-medium bg-gray-200 text-gray-700 rounded-full">Skipped</span>
                            @elseif($sol['is_correct'])
                                <span class="px-2.5 py-0.5 text-xs font-medium bg-green-200 text-green-800 rounded-full">Correct (+{{ $sol['marks_awarded'] }})</span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-medium bg-red-200 text-red-800 rounded-full">Wrong ({{ $sol['marks_awarded'] }})</span>
                            @endif
                            <span class="text-xs text-gray-400">{{ $sol['time_spent'] }}s</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-800 text-sm mb-4">{!! $q->question_text !!}</p>

                        @if(in_array($q->question_type, ['mcq_single', 'mcq_multiple', 'assertion_reason']))
                            <div class="space-y-2">
                                @foreach($q->options as $key => $optText)
                                    @if(in_array($key, ['A', 'B', 'C', 'D']))
                                        @php
                                            $isCorrectOption  = in_array($key, (array) ($q->correct_answer ?? []));
                                            $isStudentAnswer  = in_array($key, (array) ($sol['given_answer'] ?? []));
                                        @endphp
                                        <div class="flex items-start space-x-2 p-2 rounded-lg text-sm
                                                     {{ $isCorrectOption ? 'bg-green-100 border border-green-300' : ($isStudentAnswer ? 'bg-red-100 border border-red-300' : 'bg-gray-50 border border-gray-200') }}">
                                            <span class="font-bold text-xs flex-shrink-0 mt-0.5
                                                         {{ $isCorrectOption ? 'text-green-700' : ($isStudentAnswer ? 'text-red-600' : 'text-gray-500') }}">
                                                {{ $key }}.
                                            </span>
                                            <span class="{{ $isCorrectOption ? 'text-green-800' : ($isStudentAnswer ? 'text-red-700' : 'text-gray-700') }}">
                                                {{ $optText }}
                                            </span>
                                            @if($isCorrectOption)
                                                <svg class="w-4 h-4 text-green-600 flex-shrink-0 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            @elseif($isStudentAnswer)
                                                <svg class="w-4 h-4 text-red-500 flex-shrink-0 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @elseif($q->question_type === 'numerical')
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 mb-1">Your Answer</p>
                                    <p class="font-bold text-gray-800">{{ $sol['given_answer'][0] ?? 'Not answered' }}</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 mb-1">Accepted Range</p>
                                    <p class="font-bold text-green-700">{{ $q->answer_range_from }} — {{ $q->answer_range_to }}</p>
                                </div>
                            </div>
                        @endif

                        @if($q->explanation)
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs font-semibold text-blue-700 mb-1">Explanation:</p>
                                <p class="text-xs text-gray-700">{!! $q->explanation !!}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-10 text-center text-gray-500">
                    <p>Solution details are not available.</p>
                </div>
            @endforelse
        </div>
    @endif
</div>
