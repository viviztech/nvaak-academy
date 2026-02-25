@extends('layouts.public', ['title' => 'NVAAK IAS & NEET Academy – NEET & TNPSC Coaching in Avadi, Chennai'])
@section('content')

    {{-- ── Hero Section ────────────────────────────────────────────────── --}}
    <section class="relative overflow-hidden" style="background: linear-gradient(135deg,#1E3A5F 0%,#163050 60%,#0f2240 100%);">
        {{-- Decorative circles --}}
        <div class="absolute top-0 right-0 w-[600px] h-[600px] rounded-full opacity-5" style="background:#F97316; transform:translate(30%,-30%)"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full opacity-5" style="background:#F97316; transform:translate(-30%,30%)"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="grid lg:grid-cols-2 gap-12 items-center">

                {{-- Left: copy --}}
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-6"
                          style="background:rgba(249,115,22,0.15); color:#F97316; border:1px solid rgba(249,115,22,0.3);">
                        <span class="h-1.5 w-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                        85% Success Rate in NEET 2025
                    </span>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6">
                        Your Dream<br>
                        <span style="color:#F97316;">Medical &</span><br>
                        Government Career<br>
                        <span class="text-3xl sm:text-4xl font-bold text-blue-200">Starts Here</span>
                    </h1>

                    <p class="text-lg text-blue-200 mb-8 leading-relaxed max-w-xl">
                        Expert NEET & TNPSC coaching in Avadi, Chennai. Live classes, 15+ expert faculty, and a proven track record of 500+ successful students.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('admission.apply') }}"
                           class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-base font-bold text-white rounded-xl shadow-lg transition-transform hover:scale-105"
                           style="background-color:#F97316;">
                            Apply Now – Free Demo Class
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="/#courses"
                           class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-base font-semibold text-white rounded-xl border border-white/20 hover:bg-white/10 transition-colors">
                            Explore Courses
                        </a>
                    </div>

                    <div class="flex items-center gap-1 mt-6">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <span class="text-sm text-blue-200 ml-1">4.8/5 from 250+ student reviews</span>
                    </div>
                </div>

                {{-- Right: Stats cards --}}
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['500+', 'Students Trained', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                        ['85%', 'NEET Success Rate', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['15+', 'Expert Faculty', 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
                        ['10+', 'Years of Excellence', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ] as [$number, $label, $icon])
                    <div class="rounded-2xl p-5 border border-white/10" style="background:rgba(255,255,255,0.08);">
                        <svg class="h-7 w-7 mb-3" style="color:#F97316;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/>
                        </svg>
                        <p class="text-3xl font-extrabold text-white">{{ $number }}</p>
                        <p class="text-sm text-blue-200 mt-1">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ── Trust Banner ─────────────────────────────────────────────────── --}}
    <section class="bg-orange-50 border-y border-orange-100 py-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-center gap-8 text-sm font-medium text-gray-700">
                @foreach([
                    ['✓ 100% Secure Admission', '#F97316'],
                    ['✓ Money-Back Guarantee', '#1E3A5F'],
                    ['✓ Hybrid Learning (Online + Centre)', '#1E3A5F'],
                    ['✓ 24/7 Doubt Clearing', '#F97316'],
                ] as [$text, $color])
                    <span style="color:{{ $color }}">{{ $text }}</span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Courses Section ──────────────────────────────────────────────── --}}
    <section id="courses" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Our Programs</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-2" style="color:#1E3A5F;">Courses Designed to Get You Selected</h2>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">Comprehensive coaching programs built around the latest exam patterns, with expert faculty and personalised attention.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">

                {{-- NEET Card --}}
                <div class="rounded-2xl border-2 border-gray-100 hover:border-orange-300 overflow-hidden shadow-sm hover:shadow-lg transition-all group">
                    <div class="p-1" style="background:linear-gradient(90deg,#1E3A5F,#2a4f7a);">
                        <div class="flex items-center gap-3 px-5 py-3">
                            <span class="text-2xl">🩺</span>
                            <span class="text-white font-bold text-lg">NEET Coaching</span>
                            <span class="ml-auto text-xs font-semibold bg-orange-500 text-white px-2.5 py-1 rounded-full">Most Popular</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-2.5 mb-6">
                            @foreach([
                                '500+ Full-length mock tests',
                                'NCERT-focused concept classes',
                                'Daily doubt clearing sessions',
                                'Physics, Chemistry & Biology',
                                'Previous year paper analysis',
                                'AI-powered performance analytics',
                            ] as $feat)
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="h-4 w-4 mt-0.5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                {{ $feat }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('admission.apply') }}"
                           class="block w-full py-3 text-center text-sm font-bold text-white rounded-xl transition-opacity hover:opacity-90"
                           style="background-color:#F97316;">
                            Enroll in NEET →
                        </a>
                    </div>
                </div>

                {{-- TNPSC Card --}}
                <div class="rounded-2xl border-2 border-gray-100 hover:border-orange-300 overflow-hidden shadow-sm hover:shadow-lg transition-all group">
                    <div class="p-1" style="background:linear-gradient(90deg,#2d6a4f,#40916c);">
                        <div class="flex items-center gap-3 px-5 py-3">
                            <span class="text-2xl">🏛️</span>
                            <span class="text-white font-bold text-lg">TNPSC Coaching</span>
                            <span class="ml-auto text-xs font-semibold bg-yellow-400 text-gray-900 px-2.5 py-1 rounded-full">Group 1 / 2 / 4</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-2.5 mb-6">
                            @foreach([
                                'Group 1, Group 2 & Group 4 programmes',
                                'Daily current affairs sessions',
                                'Previous year question papers',
                                'Tamil & English medium batches',
                                'Interview preparation guidance',
                                '1000+ practice questions bank',
                            ] as $feat)
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="h-4 w-4 mt-0.5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                {{ $feat }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('admission.apply') }}"
                           class="block w-full py-3 text-center text-sm font-bold text-white rounded-xl transition-opacity hover:opacity-90"
                           style="background-color:#2d6a4f;">
                            Enroll in TNPSC →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── How It Works ──────────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Simple Process</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-2" style="color:#1E3A5F;">Your Journey to Success in 4 Simple Steps</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach([
                    ['01', 'Book Free Demo',   'Experience our teaching style with zero commitment required.'],
                    ['02', 'Choose Your Course','Select NEET or TNPSC coaching based on your career goal.'],
                    ['03', 'Start Learning',    'Join live classes, access study materials, and take mock tests.'],
                    ['04', 'Crack Your Exam',   'Clear your target exam with our expert guidance and support.'],
                ] as [$step, $title, $desc])
                <div class="text-center relative">
                    <div class="h-16 w-16 rounded-full flex items-center justify-center text-2xl font-extrabold text-white mx-auto mb-5"
                         style="background-color:#1E3A5F;">{{ $step }}</div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ $title }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Why NVAAK Section ────────────────────────────────────────────── --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Why Choose Us</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-2" style="color:#1E3A5F;">Everything You Need to Succeed</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['🎥', 'Live Interactive Classes', 'Two-way live sessions with real-time doubt resolution. Never miss a class with recorded backups.'],
                    ['📚', '1000+ Practice Questions', 'Extensive question bank with chapter-wise tests, full mocks, and previous year papers.'],
                    ['📖', 'Complete Study Materials', 'High-quality digital and printed materials aligned with the latest NEET and TNPSC syllabus.'],
                    ['🤖', 'AI Performance Analytics', 'Personalised insights on your strengths and weaknesses. Know exactly where to focus.'],
                    ['🏫', 'Hybrid Learning Model', 'Flexibility to attend at our Avadi centre or join online — whatever works best for you.'],
                    ['💬', '24/7 Doubt Clearing', 'Expert faculty available round the clock via our platform to clear all your queries.'],
                ] as [$emoji, $title, $desc])
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 rounded-xl flex items-center justify-center text-2xl mb-4" style="background:#FFF7ED;">
                        {{ $emoji }}
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ $title }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Features Tabs ─────────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Platform Features</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-2" style="color:#1E3A5F;">Everything You Need to Succeed</h2>
            </div>
            <div x-data="{ tab: 'live' }">
                {{-- Tab Nav --}}
                <div class="flex justify-center flex-wrap gap-3 mb-10">
                    @foreach([
                        ['live',      '📹', 'Live Classes'],
                        ['tests',     '📝', 'Mock Tests'],
                        ['materials', '📚', 'Study Materials'],
                        ['doubts',    '💬', 'Doubt Clearing'],
                    ] as [$key, $emoji, $label])
                    <button @click="tab = '{{ $key }}'"
                            :class="tab === '{{ $key }}' ? 'text-white' : 'bg-white text-gray-700 border border-gray-200'"
                            :style="tab === '{{ $key }}' ? 'background-color:#1E3A5F;' : ''"
                            class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all">
                        {{ $emoji }} {{ $label }}
                    </button>
                    @endforeach
                </div>

                {{-- Live Classes --}}
                <div x-show="tab === 'live'" x-transition class="grid md:grid-cols-2 gap-10 items-center bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <div>
                        <h3 class="text-2xl font-bold mb-5" style="color:#1E3A5F;">Interactive Live Online Classes</h3>
                        <ul class="space-y-3">
                            @foreach(['Two-way audio/video interaction with faculty','Real-time doubt resolution during class','Screen sharing and digital whiteboard','Automatic attendance tracking','Class recordings available 24/7'] as $f)
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="h-4 w-4 mt-0.5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                {{ $f }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('admission.apply') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 text-sm font-bold text-white rounded-xl transition-opacity hover:opacity-90" style="background-color:#F97316;">
                            Try Free Demo Class →
                        </a>
                    </div>
                    <div class="rounded-2xl flex items-center justify-center h-52 text-6xl" style="background:linear-gradient(135deg,#1E3A5F,#163050);">📹</div>
                </div>

                {{-- Mock Tests --}}
                <div x-show="tab === 'tests'" x-transition class="grid md:grid-cols-2 gap-10 items-center bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <div>
                        <h3 class="text-2xl font-bold mb-5" style="color:#1E3A5F;">Comprehensive Mock Tests</h3>
                        <ul class="space-y-3">
                            @foreach(['1000+ practice questions in question bank','Previous year NEET & TNPSC papers','Detailed subject-wise performance analysis','Time-bound exam simulation with NEET pattern','Instant results with answer explanations'] as $f)
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="h-4 w-4 mt-0.5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                {{ $f }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('admission.apply') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 text-sm font-bold text-white rounded-xl transition-opacity hover:opacity-90" style="background-color:#F97316;">
                            Start Practicing →
                        </a>
                    </div>
                    <div class="rounded-2xl flex items-center justify-center h-52 text-6xl" style="background:linear-gradient(135deg,#F97316,#ea6c00);">📝</div>
                </div>

                {{-- Study Materials --}}
                <div x-show="tab === 'materials'" x-transition class="grid md:grid-cols-2 gap-10 items-center bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <div>
                        <h3 class="text-2xl font-bold mb-5" style="color:#1E3A5F;">Complete Study Materials</h3>
                        <ul class="space-y-3">
                            @foreach(['Digital PDFs and chapter-wise notes','Video lecture library accessible anytime','Printed materials available at centre','Updated annually with latest exam patterns','Mobile app access for offline study'] as $f)
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="h-4 w-4 mt-0.5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                {{ $f }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('admission.apply') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 text-sm font-bold text-white rounded-xl transition-opacity hover:opacity-90" style="background-color:#F97316;">
                            Access Materials →
                        </a>
                    </div>
                    <div class="rounded-2xl flex items-center justify-center h-52 text-6xl" style="background:linear-gradient(135deg,#2d6a4f,#40916c);">📚</div>
                </div>

                {{-- Doubt Clearing --}}
                <div x-show="tab === 'doubts'" x-transition class="grid md:grid-cols-2 gap-10 items-center bg-gray-50 rounded-2xl p-8 border border-gray-100">
                    <div>
                        <h3 class="text-2xl font-bold mb-5" style="color:#1E3A5F;">24/7 Doubt Clearing</h3>
                        <ul class="space-y-3">
                            @foreach(['Dedicated daily doubt-clearing sessions','One-on-one support from subject experts','Quick response via WhatsApp & platform','Video explanations for complex topics','Subject-wise expert faculty assigned'] as $f)
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <svg class="h-4 w-4 mt-0.5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                {{ $f }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="https://wa.me/919940528779" target="_blank" class="inline-flex items-center gap-2 mt-6 px-6 py-3 text-sm font-bold text-white rounded-xl transition-opacity hover:opacity-90" style="background-color:#25D366;">
                            Chat on WhatsApp →
                        </a>
                    </div>
                    <div class="rounded-2xl flex items-center justify-center h-52 text-6xl" style="background:linear-gradient(135deg,#7c3aed,#6d28d9);">💬</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Faculty Preview ───────────────────────────────────────────────── --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Our Team</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-2" style="color:#1E3A5F;">Learn from the Best</h2>
                <p class="text-gray-500 mt-3">Subject matter experts with years of teaching and examination experience.</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['👨‍🏫', 'Dr. Rajesh Kumar',  'Physics Expert',   'MBBS · 12+ yrs', '500+ NEET selections'],
                    ['👩‍🏫', 'Dr. Priya Menon',   'Chemistry Expert', 'MD · 10+ yrs',    '400+ NEET selections'],
                    ['👨‍🏫', 'Prof. Suresh Iyer',  'TNPSC Expert',     'IAS (Retd.) · 15+ yrs', '300+ TNPSC selections'],
                    ['👩‍🏫', 'Dr. Anjali Reddy',  'Biology Expert',   'MBBS · 8+ yrs',   '350+ NEET selections'],
                ] as [$emoji, $name, $role, $qual, $badge])
                <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="h-20 w-20 rounded-full flex items-center justify-center text-4xl mx-auto mb-4"
                         style="background:#EEF2FF;">{{ $emoji }}</div>
                    <h3 class="font-bold text-gray-900 mb-1">{{ $name }}</h3>
                    <p class="text-sm font-semibold mb-1" style="color:#F97316;">{{ $role }}</p>
                    <p class="text-xs text-gray-400 mb-3">{{ $qual }}</p>
                    <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full" style="background:#FFF7ED; color:#C2410C;">🏆 {{ $badge }}</span>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('faculty') }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold rounded-xl border-2 transition-colors hover:bg-white"
                   style="border-color:#1E3A5F; color:#1E3A5F;">
                    Meet All Faculty →
                </a>
            </div>
        </div>
    </section>

    {{-- ── Pricing Cards ─────────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Fee Structure</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-2" style="color:#1E3A5F;">Flexible Plans for Every Student</h2>
                <p class="text-gray-500 mt-3">Quality coaching at prices that won't burden your family. EMI available on all plans.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach([
                    ['Most Popular', 'NEET 2027 (2-Year)', '₹45,000', '/2 years', '₹3,750/month',
                     ['Complete 2-year syllabus coverage','1000+ mock tests included','Printed + digital study materials','Daily doubt-clearing sessions','AI-powered performance tracking']],
                    [null, 'TNPSC Group 1', '₹35,000', '/year', 'EMI available',
                     ['Complete TNPSC syllabus','Daily current affairs sessions','Interview preparation guidance','Previous year question papers','Mock interview sessions']],
                    [null, 'NEET 2026 (1-Year)', '₹25,000', '/year', 'EMI available',
                     ['Intensive 1-year preparation','500+ full-length mock tests','Study materials included','Doubt-clearing sessions','Performance analytics']],
                ] as [$badge, $title, $price, $unit, $emi, $features])
                <div class="rounded-2xl border-2 p-8 transition-all hover:shadow-lg {{ $badge ? 'border-orange-400 shadow-md relative' : 'border-gray-100' }}">
                    @if($badge)
                    <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 text-xs font-bold text-white rounded-full" style="background-color:#F97316;">{{ $badge }}</span>
                    @endif
                    <h3 class="text-xl font-bold mb-4" style="color:#1E3A5F;">{{ $title }}</h3>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold" style="color:#1E3A5F;">{{ $price }}</span>
                        <span class="text-base text-gray-500">{{ $unit }}</span>
                    </div>
                    <ul class="space-y-2.5 mb-8">
                        @foreach($features as $f)
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <svg class="h-4 w-4 mt-0.5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('admission.apply') }}"
                       class="block w-full py-3 text-center text-sm font-bold text-white rounded-xl transition-opacity hover:opacity-90"
                       style="background-color:{{ $badge ? '#F97316' : '#1E3A5F' }};">
                        Enroll Now →
                    </a>
                    <p class="text-center text-xs text-gray-400 mt-3">💳 EMI from {{ $emi }}</p>
                </div>
                @endforeach
            </div>
            <div class="flex justify-center gap-8 mt-10 flex-wrap text-sm text-gray-500">
                <span>🔒 100% Secure Payment</span>
                <span>💯 Money-back Guarantee</span>
                <span>📞 24/7 Support</span>
            </div>
        </div>
    </section>

    {{-- ── Results / Success Stories ────────────────────────────────────── --}}
    <section id="results" style="background:linear-gradient(135deg,#1E3A5F,#163050);" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-sm font-semibold uppercase tracking-widest text-orange-400">Proven Results</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">Our Students' Success Stories</h2>
                <p class="text-blue-200 mt-3">Real students, real results. Here's what NVAAK IAS & NEET Academy has achieved.</p>
            </div>

            {{-- Stats row --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mb-14">
                @foreach([
                    ['500+', 'Students Trained'],
                    ['85%', 'NEET Success Rate'],
                    ['15+', 'Expert Faculty'],
                    ['4.8★', '250 Reviews'],
                ] as [$num, $lbl])
                <div class="text-center">
                    <p class="text-4xl font-extrabold text-white">{{ $num }}</p>
                    <p class="text-sm text-blue-200 mt-1">{{ $lbl }}</p>
                </div>
                @endforeach
            </div>

            {{-- Testimonial cards --}}
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white/10 backdrop-blur rounded-2xl p-6 border border-white/10">
                    <div class="flex items-center gap-1 mb-3">
                        @for($i = 0; $i < 5; $i++)<svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <p class="text-white font-semibold text-lg mb-1">Priya Sharma</p>
                    <p class="text-orange-400 text-sm font-bold mb-3">NEET 2025 – AIR 245 · Madras Medical College</p>
                    <p class="text-blue-100 text-sm leading-relaxed">
                        "NVAAK IAS & NEET Academy's structured approach and dedicated faculty helped me crack NEET in my first attempt. The mock tests and doubt sessions were game-changers for me."
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur rounded-2xl p-6 border border-white/10">
                    <div class="flex items-center gap-1 mb-3">
                        @for($i = 0; $i < 5; $i++)<svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <p class="text-white font-semibold text-lg mb-1">Rajesh Kumar</p>
                    <p class="text-orange-400 text-sm font-bold mb-3">TNPSC Group 1 – Selected</p>
                    <p class="text-blue-100 text-sm leading-relaxed">
                        "The TNPSC programme at NVAAK is incredibly well-structured. The daily current affairs and previous year analysis gave me the edge I needed to clear Group 1."
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA / Enroll Banner ──────────────────────────────────────────── --}}
    <section class="py-16 bg-orange-50 border-y border-orange-100">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold mb-4" style="color:#1E3A5F;">
                Limited Seats for 2026 Batch
            </h2>
            <p class="text-gray-600 mb-2">Enroll before <strong>December 31</strong> and get 10% off on your first enrollment.</p>
            <p class="text-sm text-gray-500 mb-8">Book a free demo class today — no commitment required.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('admission.apply') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-bold text-white rounded-xl shadow-lg transition-transform hover:scale-105"
                   style="background-color:#F97316;">
                    Book Free Demo Class
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="tel:+919940528779"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-semibold rounded-xl border-2 transition-colors"
                   style="border-color:#1E3A5F; color:#1E3A5F;">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    Call +91 99405 28779
                </a>
            </div>
        </div>
    </section>

    {{-- ── Contact Section ──────────────────────────────────────────────── --}}
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Get In Touch</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-2" style="color:#1E3A5F;">Visit Our Centre</h2>
                <p class="text-gray-500 mt-3">We're located in the heart of Avadi, Chennai — easy to reach by road and rail.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-10 items-start">

                {{-- Contact info --}}
                <div class="space-y-5">
                    @foreach([
                        ['📍', 'Our Address', 'No. 3517 A, TNHB, Avadi, Chennai – 600054', null],
                        ['📞', 'Phone', '+91 99405 28779', 'tel:+919940528779'],
                        ['💬', 'WhatsApp', '+91 99405 28779', 'https://wa.me/919940528779'],
                        ['✉️', 'Email', 'info@nvaakacademy.com', 'mailto:info@nvaakacademy.com'],
                        ['🕐', 'Office Hours', 'Mon – Sat: 9:00 AM – 6:00 PM | Sunday: Closed', null],
                    ] as [$emoji, $label, $value, $link])
                    <div class="flex items-start gap-4 p-5 rounded-2xl bg-gray-50 border border-gray-100">
                        <span class="text-2xl">{{ $emoji }}</span>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">{{ $label }}</p>
                            @if($link)
                                <a href="{{ $link }}" class="text-sm font-semibold hover:underline" style="color:#1E3A5F;" target="{{ str_starts_with($link, 'http') ? '_blank' : '_self' }}">{{ $value }}</a>
                            @else
                                <p class="text-sm font-semibold text-gray-800">{{ $value }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    <a href="{{ route('admission.apply') }}"
                       class="flex items-center justify-center gap-2 w-full py-4 text-base font-bold text-white rounded-xl shadow-md transition-opacity hover:opacity-90"
                       style="background-color:#F97316;">
                        Start Your Admission Application →
                    </a>
                </div>

                {{-- Map --}}
                <div class="rounded-2xl overflow-hidden shadow-md border border-gray-100 h-80 lg:h-[420px]">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3884.2!2d80.0984!3d13.1165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTPCsDA2JzU5LjQiTiA4MMKwMDUnNTQuMiJF!5e0!3m2!1sen!2sin!4v1"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Floating WhatsApp Button ─────────────────────────────────────── --}}
    <a href="https://wa.me/919940528779?text=Hi%2C%20I%20want%20to%20know%20more%20about%20NVAAK%20Academy%20courses"
       target="_blank"
       class="fixed bottom-6 right-6 z-50 h-14 w-14 rounded-full shadow-xl flex items-center justify-center transition-transform hover:scale-110"
       style="background-color:#25D366;" title="Chat on WhatsApp">
        <svg class="h-7 w-7 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

@endsection
