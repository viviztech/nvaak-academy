<div>
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('student.ias.upload') }}" class="hover:text-[#1e3a5f]">IAS Submissions</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">Evaluation Feedback</span>
    </div>

    @if(!$evaluation)
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-base font-semibold text-yellow-800">Evaluation Pending</h3>
            <p class="text-sm text-yellow-700 mt-1">Your answer sheet is being reviewed by faculty. Please check back later.</p>
            <a href="{{ route('student.ias.upload') }}"
               class="mt-4 inline-block text-sm font-semibold text-white bg-[#1e3a5f] hover:bg-blue-900 px-4 py-2 rounded-xl transition-colors">
                Back to Submissions
            </a>
        </div>
    @else
        {{-- Header Info --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Subject</p>
                    <h2 class="text-xl font-bold text-gray-900">{{ $submission->subject?->name ?? '—' }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Submitted: {{ $submission->submitted_at?->format('d M Y, h:i A') }}
                        &middot;
                        Evaluated: {{ $evaluation->evaluated_at?->format('d M Y, h:i A') ?? '—' }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-center">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto border-4
                            {{ $evaluation->total_score >= 75 ? 'border-green-400 bg-green-50' : ($evaluation->total_score >= 50 ? 'border-yellow-400 bg-yellow-50' : 'border-red-400 bg-red-50') }}">
                            <span class="text-2xl font-bold
                                {{ $evaluation->total_score >= 75 ? 'text-green-600' : ($evaluation->total_score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $evaluation->total_score }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">out of 100</p>
                    </div>
                    <div class="text-center">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto border-4 border-[#1e3a5f] bg-blue-50">
                            <span class="text-2xl font-bold text-[#1e3a5f]">{{ $evaluation->grade ?? '—' }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Grade</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Question --}}
        <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5 mb-6">
            <p class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-2">Question</p>
            <p class="text-sm text-gray-800 leading-relaxed">{{ $submission->question_text }}</p>
        </div>

        {{-- Score Breakdown --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-base font-semibold text-gray-800 mb-5">Score Breakdown</h3>
            <div class="space-y-4">

                @php
                    $criteria = [
                        ['label' => 'Content & Accuracy',       'score' => $evaluation->score_content,    'max' => 40, 'color' => 'bg-blue-500'],
                        ['label' => 'Language & Grammar',       'score' => $evaluation->score_language,   'max' => 20, 'color' => 'bg-green-500'],
                        ['label' => 'Structure & Presentation', 'score' => $evaluation->score_structure,  'max' => 20, 'color' => 'bg-purple-500'],
                        ['label' => 'Analytical Thinking',      'score' => $evaluation->score_analytical, 'max' => 20, 'color' => 'bg-orange-500'],
                    ];
                @endphp

                @foreach($criteria as $c)
                    @php $pct = $c['max'] > 0 ? round(($c['score'] / $c['max']) * 100) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-medium text-gray-700">{{ $c['label'] }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $c['score'] }}<span class="text-gray-400 font-normal">/{{ $c['max'] }}</span></span>
                        </div>
                        <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700 {{ $c['color'] }}"
                                 style="width: {{ $pct }}%">
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Total --}}
                <div class="pt-3 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-sm font-bold text-gray-800">Total Score</span>
                        <span class="text-base font-bold
                            {{ $evaluation->total_score >= 75 ? 'text-green-600' : ($evaluation->total_score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $evaluation->total_score }}/100
                        </span>
                    </div>
                    <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-700
                            {{ $evaluation->total_score >= 75 ? 'bg-green-500' : ($evaluation->total_score >= 50 ? 'bg-yellow-400' : 'bg-red-500') }}"
                            style="width: {{ $evaluation->total_score }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Feedback Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            {{-- General Feedback --}}
            <div class="md:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-800">General Feedback</h4>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $evaluation->general_feedback ?? 'No feedback provided.' }}</p>
            </div>

            {{-- Strengths --}}
            <div class="bg-green-50 rounded-2xl border border-green-100 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-green-200 flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-green-800">Strengths</h4>
                </div>
                <p class="text-sm text-green-800 leading-relaxed">
                    {{ $evaluation->strengths ?: 'No specific strengths noted.' }}
                </p>
            </div>

            {{-- Areas for Improvement --}}
            <div class="bg-orange-50 rounded-2xl border border-orange-100 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-orange-200 flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-orange-800">Areas for Improvement</h4>
                </div>
                <p class="text-sm text-orange-800 leading-relaxed">
                    {{ $evaluation->improvements_needed ?: 'Keep up the good work!' }}
                </p>
            </div>
        </div>

        {{-- Back Link --}}
        <div class="mt-6">
            <a href="{{ route('student.ias.upload') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-[#1e3a5f] hover:text-orange-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to All Submissions
            </a>
        </div>
    @endif
</div>
