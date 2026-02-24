<div>
    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">My Exams</h2>
        <p class="text-sm text-gray-500 mt-1">View and attempt your scheduled exams</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="border-b border-gray-200 mb-6">
        <div class="flex space-x-1">
            @foreach(['upcoming' => 'Upcoming', 'live' => 'Live Now', 'completed' => 'Completed'] as $tab => $label)
                <button wire:click="setTab('{{ $tab }}')"
                        class="relative px-5 py-3 text-sm font-medium border-b-2 transition-colors
                               {{ $activeTab === $tab ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                    {{ $label }}
                    @if($tab === 'live' && $liveExams->count() > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse">
                            {{ $liveExams->count() }}
                        </span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- UPCOMING EXAMS --}}
    @if($activeTab === 'upcoming')
        @if($upcomingExams->isEmpty())
            <div class="py-16 text-center text-gray-500">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="font-medium">No upcoming exams</p>
                <p class="text-sm mt-1">Check back later for scheduled exams</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($upcomingExams as $exam)
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="bg-[#1e3a5f] px-5 py-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-blue-200 uppercase tracking-wide">
                                    {{ ucwords(str_replace('_', ' ', $exam->exam_type)) }}
                                </span>
                                <span class="px-2 py-0.5 bg-blue-400 text-white text-xs rounded-full">
                                    Upcoming
                                </span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-800 text-sm">{{ $exam->name }}</h3>
                            <p class="text-xs text-gray-400 mt-1">{{ $exam->code }}</p>

                            <div class="mt-4 space-y-2">
                                <div class="flex items-center text-xs text-gray-500 space-x-2">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $exam->duration_minutes }} minutes</span>
                                </div>
                                <div class="flex items-center text-xs text-gray-500 space-x-2">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span>{{ $exam->total_marks }} total marks</span>
                                </div>
                                @if($exam->start_time)
                                    <div class="flex items-center text-xs text-gray-500 space-x-2">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ $exam->start_time->format('d M Y, h:i A') }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100">
                                <button disabled
                                        class="w-full py-2 text-sm font-medium text-gray-500 bg-gray-100 rounded-lg cursor-not-allowed">
                                    Not Started Yet
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    {{-- LIVE EXAMS --}}
    @if($activeTab === 'live')
        @if($liveExams->isEmpty())
            <div class="py-16 text-center text-gray-500">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                    </svg>
                </div>
                <p class="font-medium">No live exams right now</p>
                <p class="text-sm mt-1">Check the upcoming tab for scheduled exams</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($liveExams as $exam)
                    <div class="bg-white rounded-xl border-2 border-green-400 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="bg-green-500 px-5 py-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium text-green-100 uppercase tracking-wide flex items-center">
                                    <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                                    LIVE NOW
                                </span>
                                <span class="text-xs text-green-100">{{ ucwords(str_replace('_', ' ', $exam->exam_type)) }}</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-800 text-sm">{{ $exam->name }}</h3>
                            <p class="text-xs text-gray-400 mt-1">{{ $exam->code }}</p>

                            <div class="mt-4 space-y-2">
                                <div class="flex items-center text-xs text-gray-500 space-x-2">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $exam->duration_minutes }} minutes</span>
                                </div>
                                <div class="flex items-center text-xs text-gray-500 space-x-2">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <span>{{ $exam->total_marks }} marks</span>
                                </div>
                                @if($exam->end_time)
                                    <div class="flex items-center text-xs text-red-500 space-x-2 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Ends: {{ $exam->end_time->format('h:i A') }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100">
                                @if($exam->student_exam && $exam->student_exam->status === 'submitted')
                                    <span class="block w-full py-2 text-center text-sm font-medium text-gray-500 bg-gray-100 rounded-lg">
                                        Already Submitted
                                    </span>
                                @elseif($exam->student_exam && $exam->student_exam->status === 'in_progress')
                                    <a href="{{ route('student.exams.take', $exam->id) }}"
                                       class="block w-full py-2 text-center text-sm font-medium text-white bg-green-500 hover:bg-green-600 rounded-lg transition-colors">
                                        Resume Exam
                                    </a>
                                @else
                                    <a href="{{ route('student.exams.take', $exam->id) }}"
                                       class="block w-full py-2 text-center text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition-colors">
                                        Start Exam
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    {{-- COMPLETED EXAMS --}}
    @if($activeTab === 'completed')
        @if($completedExams->isEmpty())
            <div class="py-16 text-center text-gray-500">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-medium">No completed exams yet</p>
                <p class="text-sm mt-1">Your completed exams will appear here</p>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Exam</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Submitted</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Marks</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Percentage</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Rank</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($completedExams as $studentExam)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-800">{{ $studentExam->exam?->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $studentExam->exam?->code }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs">
                                        {{ $studentExam->submitted_at?->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($studentExam->result && $studentExam->result->is_published)
                                            <span class="font-bold text-gray-800">{{ $studentExam->result->marks_obtained }}</span>
                                            <span class="text-gray-400 text-xs">/ {{ $studentExam->result->total_marks }}</span>
                                        @else
                                            <span class="text-gray-400 text-xs">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($studentExam->result && $studentExam->result->is_published)
                                            <span class="font-medium text-gray-700">{{ $studentExam->result->percentage }}%</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($studentExam->result && $studentExam->result->is_published && $studentExam->result->rank_in_batch)
                                            <span class="font-medium text-[#1e3a5f]">#{{ $studentExam->result->rank_in_batch }}</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($studentExam->result && $studentExam->result->is_published)
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $studentExam->result->pass_fail_color }}">
                                                {{ ucfirst($studentExam->result->pass_fail) }}
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                Result Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($studentExam->result && $studentExam->result->is_published)
                                            <a href="{{ route('student.exams.result', $studentExam->id) }}"
                                               class="text-sm font-medium text-orange-500 hover:text-orange-700">
                                                View Result
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">Result not released yet</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
</div>
