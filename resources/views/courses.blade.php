@extends('layouts.public', ['title' => 'NEET, IAS & TNPSC Coaching in Avadi, Chennai | NVAAK IAS & NEET Academy – Courses'])

@section('content')

{{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
<section class="bg-gradient-to-br from-nvaak-cream-50 to-nvaak-teal-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-nvaak-teal-100 text-nvaak-teal-700 mb-4">Our Programmes</span>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 mb-4 leading-tight">
            Expert Coaching for<br>
            <span class="text-nvaak-teal">Every Competitive Exam</span>
        </h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto mb-8">
            Structured programmes designed by experienced faculty to prepare you thoroughly for NEET, IAS, and TNPSC exams.
        </p>
        <a href="{{ route('admission.apply') }}"
           class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white rounded-xl shadow-md transition-colors bg-nvaak-teal hover:bg-nvaak-teal-600">
            Apply for Admission
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
    </div>
</section>

{{-- ── NEET Coaching ────────────────────────────────────────────────────── --}}
<section id="neet" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 mb-12">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center bg-nvaak-teal-50">
                        <svg class="h-5 w-5 text-nvaak-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-nvaak-teal bg-nvaak-teal-50 px-3 py-1 rounded-full">Medical Entrance</span>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900">NEET Coaching</h2>
                <p class="text-gray-500 mt-2 max-w-lg">Comprehensive preparation for the National Eligibility cum Entrance Test (NEET-UG) with Physics, Chemistry, and Biology.</p>
            </div>
            <a href="{{ route('admission.apply') }}"
               class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-lg transition-colors bg-nvaak-teal hover:bg-nvaak-teal-600">
                Enroll for NEET
            </a>
        </div>

        {{-- Batch cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">

            @foreach([
                ['Foundation Course', 'Class 11 & 12 students', '2 Years', 'Mon–Sat', 'Best suited for students starting early with strong conceptual foundation building.', ['Physics, Chemistry, Biology', 'Daily practice MCQs', 'Weekly tests', 'NCERT mastery', 'Monthly full syllabus test']],
                ['Target Batch', 'Class 12 / Droppers', '1 Year', 'Mon–Sat', 'Intensive one-year programme for students targeting top medical colleges.', ['Full NEET syllabus coverage', 'Chapter-wise tests', 'National-level mock tests', 'Rank improvement strategy', 'Previous year paper analysis']],
                ['Crash Course', 'Final 3–4 months prep', '3–4 Months', 'Mon–Sun', 'High-intensity revision programme for students in their final stretch of preparation.', ['Rapid revision of all chapters', 'Daily full-length mock tests', '1-on-1 doubt sessions', 'Performance analytics', 'Last-mile strategy sessions']],
            ] as [$name, $for, $duration, $days, $desc, $features])
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow overflow-hidden flex flex-col">
                <div class="p-6 flex-1">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $name }}</h3>
                            <p class="text-sm text-nvaak-teal font-medium mt-0.5">{{ $for }}</p>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">{{ $duration }}</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-5">{{ $desc }}</p>
                    <ul class="space-y-2">
                        @foreach($features as $f)
                        <li class="flex items-center gap-2 text-sm text-gray-700">
                            <svg class="h-4 w-4 text-nvaak-teal shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="px-6 pb-6 pt-4 border-t border-gray-50">
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                        <span>{{ $days }}</span>
                        <span class="text-nvaak-teal font-medium">Tamil / English</span>
                    </div>
                    <a href="{{ route('admission.apply') }}"
                       class="block text-center py-2.5 text-sm font-semibold text-nvaak-teal border-2 border-nvaak-teal rounded-xl hover:bg-nvaak-teal-50 transition-colors">
                        Apply Now →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- NEET subjects covered --}}
        <div class="rounded-2xl bg-nvaak-teal-50 border border-nvaak-teal-100 p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Subjects Covered</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach([
                    ['Physics', '#1a6b7a', ['Mechanics', 'Thermodynamics', 'Optics', 'Modern Physics', 'Electricity & Magnetism']],
                    ['Chemistry', '#218091', ['Physical Chemistry', 'Organic Chemistry', 'Inorganic Chemistry', 'Biomolecules', 'Coordination Compounds']],
                    ['Biology', '#145663', ['Cell Biology', 'Genetics', 'Human Physiology', 'Ecology', 'Plant Kingdom']],
                ] as [$subject, $color, $topics])
                <div class="bg-white rounded-xl p-5 border border-nvaak-teal-100">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="h-2.5 w-2.5 rounded-full" style="background:{{ $color }};"></div>
                        <h4 class="font-bold text-gray-900">{{ $subject }}</h4>
                    </div>
                    <ul class="space-y-1.5">
                        @foreach($topics as $t)
                        <li class="text-sm text-gray-600">{{ $t }}</li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── Divider ──────────────────────────────────────────────────────────── --}}
<div class="bg-gradient-to-r from-nvaak-teal-500 to-nvaak-teal-700 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
            @foreach([['Small','Batch Sizes'],['Bilingual','Tamil & English'],['Daily','Tests & Revision'],['Expert','Faculty']] as [$n,$l])
            <div>
                <p class="text-3xl font-extrabold text-white">{{ $n }}</p>
                <p class="text-sm text-white/70 mt-1">{{ $l }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ── IAS Coaching ─────────────────────────────────────────────────────── --}}
<section id="ias" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 mb-12">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center bg-nvaak-teal-50">
                        <svg class="h-5 w-5 text-nvaak-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-nvaak-teal bg-nvaak-teal-50 px-3 py-1 rounded-full">Civil Services</span>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900">IAS / UPSC Coaching</h2>
                <p class="text-gray-500 mt-2 max-w-lg">Structured preparation for the UPSC Civil Services Examination — Prelims, Mains, and Interview stages.</p>
            </div>
            <a href="{{ route('admission.apply') }}"
               class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-lg transition-colors bg-nvaak-teal hover:bg-nvaak-teal-600">
                Enroll for IAS
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">

            {{-- Prelims batch --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-bold text-gray-900">Prelims Programme</h3>
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">6 Months</span>
                    </div>
                    <p class="text-sm text-gray-500">Intensive preparation for UPSC Preliminary Examination covering GS Paper I & II (CSAT).</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3">
                        @foreach(['GS Paper I: History, Geography, Polity, Economy, Environment, Science','GS Paper II (CSAT): Comprehension, Reasoning, Maths','Current Affairs — Monthly compilation + daily sessions','Previous year papers (2012–2024)','Weekly mock prelims + detailed analysis'] as $f)
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <svg class="h-4 w-4 text-nvaak-teal shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Mains batch --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-bold text-gray-900">Mains + Interview Programme</h3>
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">8 Months</span>
                    </div>
                    <p class="text-sm text-gray-500">Deep-dive into answer writing, optional subjects, and personality development for Mains and Interview.</p>
                </div>
                <div class="p-6">
                    <ul class="space-y-3">
                        @foreach(['GS Papers I–IV (Essay, Ethics, Governance, Security)','Optional Subject coaching (Tamil Literature, History)','Daily answer writing practice with faculty evaluation','Model answers + annotated feedback','Mock interviews with retired IAS officers'] as $f)
                        <li class="flex items-start gap-2 text-sm text-gray-700">
                            <svg class="h-4 w-4 text-nvaak-teal shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- IAS features --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([
                ['Answer Writing Lab', 'Daily practice sessions with individual faculty evaluation and detailed feedback on structure and content.'],
                ['Current Affairs', 'Daily news analysis sessions covering national and international events relevant to UPSC Mains.'],
                ['Study Material', 'Curated notes covering all GS subjects, compiled from standard books and class discussions.'],
                ['Mock Interviews', 'Simulated board interviews conducted by experts — personality assessment and confidence building.'],
            ] as [$title, $desc])
            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
                <div class="h-9 w-9 rounded-lg bg-nvaak-teal-50 flex items-center justify-center mb-4">
                    <div class="h-2 w-2 rounded-full bg-nvaak-teal"></div>
                </div>
                <h4 class="font-bold text-gray-900 text-sm mb-2">{{ $title }}</h4>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── TNPSC Coaching ───────────────────────────────────────────────────── --}}
<section id="tnpsc" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 mb-12">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center bg-nvaak-teal-50">
                        <svg class="h-5 w-5 text-nvaak-teal" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-nvaak-teal bg-nvaak-teal-50 px-3 py-1 rounded-full">State Government Exams</span>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900">TNPSC Coaching</h2>
                <p class="text-gray-500 mt-2 max-w-lg">Focused preparation for Tamil Nadu Public Service Commission exams — Group I, II, IIA, IV, and VAO.</p>
            </div>
            <a href="{{ route('admission.apply') }}"
               class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white rounded-lg transition-colors bg-nvaak-teal hover:bg-nvaak-teal-600">
                Enroll for TNPSC
            </a>
        </div>

        {{-- TNPSC group cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-12">
            @foreach([
                ['Group I', 'Deputy Collector, DSP, etc.', '12 Months', 'Prelims + Mains + Interview'],
                ['Group II', 'Sub-Registrar, HR Officer', '8 Months', 'Prelims + Mains (Written)'],
                ['Group IIA', 'Junior Assistant, Typist', '4 Months', 'Objective + Descriptive'],
                ['Group IV & VAO', 'Junior Asst, VAO', '3 Months', 'Objective type exam'],
            ] as [$group, $posts, $duration, $pattern])
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 bg-nvaak-teal-50 text-nvaak-teal text-xs font-bold px-3 py-1 rounded-full mb-3">
                        {{ $group }}
                    </div>
                    <h3 class="font-bold text-gray-900 text-base">{{ $posts }}</h3>
                </div>
                <div class="space-y-1.5 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <svg class="h-3.5 w-3.5 text-nvaak-teal shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $duration }}
                    </div>
                    <div class="flex items-start gap-2">
                        <svg class="h-3.5 w-3.5 text-nvaak-teal shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        {{ $pattern }}
                    </div>
                </div>
                <a href="{{ route('admission.apply') }}"
                   class="mt-auto block text-center py-2 text-sm font-semibold text-nvaak-teal border-2 border-nvaak-teal rounded-xl hover:bg-nvaak-teal-50 transition-colors">
                    Apply →
                </a>
            </div>
            @endforeach
        </div>

        {{-- Syllabus overview --}}
        <div class="rounded-2xl bg-nvaak-teal-50 border border-nvaak-teal-100 p-8">
            <h3 class="text-lg font-bold text-gray-900 mb-6">General Studies Syllabus</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-3">
                @foreach([
                    'Tamil Nadu History & Culture',
                    'Indian National Movement',
                    'Geography of India & Tamil Nadu',
                    'Indian Polity & Constitution',
                    'Indian Economy',
                    'Current Events — National & International',
                    'Aptitude & Mental Ability',
                    'Science & Technology',
                    'Tamil Language (Unit VIII)',
                ] as $topic)
                <div class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="h-4 w-4 text-nvaak-teal shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $topic }}
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── Why Choose NVAAK ─────────────────────────────────────────────────── --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Why Students Choose NVAAK</h2>
        <p class="text-gray-500 mb-12 max-w-xl mx-auto">Every programme is designed with one goal — your thorough preparation.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['Expert', 'Qualified Faculty', 'Faculty with strong subject expertise dedicated to NEET, IAS, and TNPSC preparation.'],
                ['Small Batches', 'Personalised Attention', 'Batch sizes capped to ensure every student gets individual focus and support.'],
                ['Daily Tests', 'Continuous Assessment', 'Regular tests, quizzes, and mock exams to track progress and identify gaps.'],
                ['Tamil Medium', 'Bilingual Teaching', 'Classes available in Tamil and English, making concepts accessible to all.'],
                ['Study Material', 'Curated Notes', 'Comprehensive, exam-focused study material updated every academic year.'],
                ['Doubt Sessions', '1-on-1 Mentorship', 'Dedicated doubt-clearing sessions with faculty before and after class.'],
            ] as [$label, $title, $desc])
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-left">
                <p class="text-2xl font-extrabold text-nvaak-teal mb-1">{{ $label }}</p>
                <h3 class="font-bold text-gray-900 mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ───────────────────────────────────────────────────────────────── --}}
<section class="py-16 bg-gradient-to-r from-nvaak-teal-600 to-nvaak-teal-800">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-extrabold text-white mb-4">Ready to Begin Your Preparation?</h2>
        <p class="text-white/80 mb-8 text-lg">Admissions open for our 2026–27 batch. Limited seats — apply early.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('admission.apply') }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-nvaak-teal text-sm font-bold rounded-xl shadow-lg hover:bg-gray-50 transition-colors">
                Apply for Admission
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 border-2 border-white text-white text-sm font-bold rounded-xl hover:bg-white/10 transition-colors">
                Talk to a Counsellor
            </a>
        </div>
    </div>
</section>

@endsection
