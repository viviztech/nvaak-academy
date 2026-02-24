<div>
    {{-- ── Welcome Banner ──────────────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-[#1e3a5f] to-blue-700 rounded-2xl px-6 py-5 mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-white font-bold text-xl">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-blue-200 text-sm mt-0.5">Here's your academic overview for today.</p>
        </div>
        <div class="hidden md:flex items-center gap-3">
            <div class="text-right">
                <p class="text-blue-200 text-xs">Attendance</p>
                <p class="text-white font-bold text-2xl">{{ $attendancePct }}%</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- ── Stats Row ───────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">

        {{-- Attendance --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-gray-500">This Month Attendance</p>
                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $attendancePct }}<span class="text-lg font-medium text-gray-400">%</span></p>
            <div class="mt-2 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500
                    {{ $attendancePct >= 75 ? 'bg-green-500' : ($attendancePct >= 50 ? 'bg-yellow-400' : 'bg-red-500') }}"
                    style="width: {{ $attendancePct }}%">
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ $attendancePct >= 75 ? 'Good standing' : 'Needs improvement' }}</p>
        </div>

        {{-- Fees Due --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-gray-500">Fees Due</p>
                <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            @if($feesDue > 0)
                <p class="text-3xl font-bold text-gray-900">&#8377;{{ number_format($feesDue) }}</p>
                @if($nextDueDate)
                    <p class="text-xs text-red-500 mt-1 font-medium">Due: {{ \Carbon\Carbon::parse($nextDueDate)->format('d M Y') }}</p>
                @endif
                <a href="{{ route('student.fees') }}"
                   class="inline-block mt-2 text-xs font-semibold text-white bg-orange-500 hover:bg-orange-600 px-3 py-1.5 rounded-lg transition-colors">
                    Pay Now
                </a>
            @else
                <p class="text-3xl font-bold text-green-600">Clear</p>
                <p class="text-xs text-gray-400 mt-1">No pending fees</p>
            @endif
        </div>

        {{-- Upcoming Exams --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-gray-500">Upcoming Exams</p>
                <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $upcomingExams->count() }}</p>
            <a href="{{ route('student.exams.index') }}"
               class="text-xs text-purple-600 hover:text-purple-800 font-medium mt-1 inline-block">
                View all &rarr;
            </a>
        </div>
    </div>

    {{-- ── Main Content Grid ───────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Upcoming Exams List --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800">Upcoming Exams</h3>
                <a href="{{ route('student.exams.index') }}" class="text-sm text-[#1e3a5f] hover:text-orange-500 font-medium transition-colors">
                    View all &rarr;
                </a>
            </div>

            @if($upcomingExams->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-gray-400">
                    <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm">No upcoming exams scheduled.</p>
                </div>
            @else
                <ul class="divide-y divide-gray-50">
                    @foreach($upcomingExams as $exam)
                        <li class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $exam->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y, h:i A') }}
                                    &middot; {{ $exam->duration_minutes ?? '—' }} mins
                                </p>
                            </div>
                            <a href="{{ route('student.exams.take', $exam->id) }}"
                               class="ml-4 flex-shrink-0 text-xs font-semibold text-white bg-[#1e3a5f] hover:bg-orange-500 px-3 py-1.5 rounded-lg transition-colors">
                                Start
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Right Column: Recent Results + Announcements --}}
        <div class="space-y-6">

            {{-- Recent Results --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-800">Recent Results</h3>
                </div>
                @if($recentResults->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">No results yet.</p>
                @else
                    <ul class="divide-y divide-gray-50">
                        @foreach($recentResults as $attempt)
                            <li class="flex items-center justify-between px-5 py-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">{{ $attempt->exam?->title ?? 'Exam' }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($attempt->submitted_at)->format('d M') }}</p>
                                </div>
                                <span class="ml-2 flex-shrink-0 text-sm font-bold
                                    {{ ($attempt->score ?? 0) >= 75 ? 'text-green-600' : (($attempt->score ?? 0) >= 50 ? 'text-yellow-500' : 'text-red-500') }}">
                                    {{ $attempt->score ?? 0 }}%
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Announcements --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-base font-semibold text-gray-800">Announcements</h3>
                </div>
                @if($announcements->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">No announcements.</p>
                @else
                    <ul class="divide-y divide-gray-50">
                        @foreach($announcements as $announcement)
                            <li class="px-5 py-3">
                                <p class="text-sm font-medium text-gray-800">{{ $announcement->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $announcement->body }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $announcement->created_at->diffForHumans() }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
