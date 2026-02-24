<div>
    {{-- ── Welcome Banner ──────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-[#1e3a5f] to-blue-700 rounded-2xl px-6 py-5 mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-white font-bold text-xl">Good {{ date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }}!</h2>
            <p class="text-blue-200 text-sm mt-0.5">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="hidden md:flex items-center gap-3">
            @if($pendingEvaluations > 0)
                <div class="bg-orange-500 rounded-xl px-4 py-2 text-center">
                    <p class="text-white font-bold text-xl leading-tight">{{ $pendingEvaluations }}</p>
                    <p class="text-orange-100 text-xs leading-tight">Pending IAS</p>
                </div>
            @endif
            <div class="bg-white/10 rounded-xl px-4 py-2 text-center">
                <p class="text-white font-bold text-xl leading-tight">{{ $todaysBatches->count() }}</p>
                <p class="text-blue-200 text-xs leading-tight">Batches</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Today's Batches & Attendance Status --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Attendance Status --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-800">Today's Attendance Status</h3>
                    <a href="{{ route('teacher.attendance') }}"
                       class="text-sm text-[#1e3a5f] hover:text-orange-500 font-medium transition-colors">
                        Mark Attendance &rarr;
                    </a>
                </div>

                @if($attendanceSummary->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                        <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                        </svg>
                        <p class="text-sm">No batches assigned.</p>
                    </div>
                @else
                    <ul class="divide-y divide-gray-50">
                        @foreach($attendanceSummary as $item)
                            <li class="flex items-center justify-between px-6 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $item['batch']['name'] ?? 'Batch' }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['batch']['course'] ?? '' }}</p>
                                </div>
                                @if($item['marked'])
                                    <span class="flex items-center gap-1 text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Marked
                                    </span>
                                @else
                                    <a href="{{ route('teacher.attendance') }}"
                                       class="text-xs font-semibold text-orange-600 bg-orange-50 hover:bg-orange-100 px-3 py-1 rounded-full transition-colors">
                                        Mark Now
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Syllabus Coverage --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-800">Syllabus Coverage</h3>
                    <a href="{{ route('teacher.syllabus') }}"
                       class="text-sm text-[#1e3a5f] hover:text-orange-500 font-medium transition-colors">
                        Update &rarr;
                    </a>
                </div>

                @if($syllabusProgress->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">No syllabus data available.</p>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($syllabusProgress as $prog)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-semibold text-gray-800">{{ $prog['batch']['name'] ?? 'Batch' }}</p>
                                    <span class="text-xs font-bold
                                        {{ $prog['pct'] >= 75 ? 'text-green-600' : ($prog['pct'] >= 40 ? 'text-yellow-600' : 'text-red-500') }}">
                                        {{ $prog['pct'] }}%
                                    </span>
                                </div>
                                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500
                                        {{ $prog['pct'] >= 75 ? 'bg-green-500' : ($prog['pct'] >= 40 ? 'bg-yellow-400' : 'bg-red-500') }}"
                                        style="width: {{ $prog['pct'] }}%">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $prog['covered'] }} / {{ $prog['total'] }} topics covered</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">

            {{-- Upcoming Exams --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-800">Upcoming Exams</h3>
                    <a href="{{ route('teacher.exams') }}"
                       class="text-sm text-[#1e3a5f] hover:text-orange-500 font-medium transition-colors">
                        View all &rarr;
                    </a>
                </div>
                @if($upcomingExams->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">No upcoming exams.</p>
                @else
                    <ul class="divide-y divide-gray-50">
                        @foreach($upcomingExams as $exam)
                            <li class="px-5 py-3">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $exam->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y, h:i A') }}
                                </p>
                                <p class="text-xs text-blue-600 mt-0.5">{{ $exam->batch?->name ?? '' }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Pending IAS Submissions --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-800">IAS Submissions</h3>
                    <a href="{{ route('teacher.ias.evaluate') }}"
                       class="text-sm text-[#1e3a5f] hover:text-orange-500 font-medium transition-colors">
                        Evaluate &rarr;
                    </a>
                </div>
                <div class="p-5 text-center">
                    @if($pendingEvaluations > 0)
                        <div class="w-16 h-16 mx-auto rounded-full bg-orange-100 flex items-center justify-center mb-3">
                            <span class="text-2xl font-bold text-orange-500">{{ $pendingEvaluations }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Pending evaluations</p>
                        <a href="{{ route('teacher.ias.evaluate') }}"
                           class="mt-3 inline-block text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 px-4 py-2 rounded-lg transition-colors">
                            Start Evaluating
                        </a>
                    @else
                        <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">All caught up!</p>
                    @endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-2">
                <h3 class="text-base font-semibold text-gray-800 mb-3">Quick Links</h3>
                <a href="{{ route('teacher.attendance') }}"
                   class="flex items-center gap-2 text-sm text-gray-700 hover:text-[#1e3a5f] py-1.5 transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Mark Attendance
                </a>
                <a href="{{ route('teacher.syllabus') }}"
                   class="flex items-center gap-2 text-sm text-gray-700 hover:text-[#1e3a5f] py-1.5 transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Update Syllabus
                </a>
                <a href="{{ route('teacher.exams') }}"
                   class="flex items-center gap-2 text-sm text-gray-700 hover:text-[#1e3a5f] py-1.5 transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    View Exams
                </a>
                <a href="{{ route('teacher.ias.evaluate') }}"
                   class="flex items-center gap-2 text-sm text-gray-700 hover:text-[#1e3a5f] py-1.5 transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    IAS Evaluation
                </a>
            </div>
        </div>
    </div>
</div>
