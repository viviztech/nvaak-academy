@extends('layouts.public', ['title' => 'Our Expert Faculty – NVAAK IAS & NEET Academy'])
@section('content')

    {{-- ── Page Hero ──────────────────────────────────────────────────────── --}}
    <section class="py-16" style="background:linear-gradient(135deg,#218091 0%,#1a6b7a 60%,#145663 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4"
                  style="background:rgba(249,115,22,0.15); color:#F97316; border:1px solid rgba(249,115,22,0.3);">
                Expert Mentors
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4">Our Expert Faculty</h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto">
                Learn from experienced educators and subject matter experts who have cracked these exams themselves.
            </p>
        </div>
    </section>

    {{-- ── Faculty Grid ────────────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach([
                    ['Dr. Rajesh Kumar',  'Physics Expert',   'MBBS',        '12+ Years', '500+ NEET Selections',  '#218091', '👨‍🏫',
                     'Dr. Rajesh Kumar is one of the most sought-after physics teachers in Avadi. His unique problem-solving methods and ability to simplify complex concepts have helped hundreds of students ace NEET Physics.'],
                    ['Dr. Priya Menon',   'Chemistry Expert', 'MD',          '10+ Years', '400+ NEET Selections',  '#2d6a4f', '👩‍🏫',
                     'With a background in medicine and a passion for teaching, Dr. Priya Menon makes Organic and Inorganic Chemistry approachable and even enjoyable for NEET aspirants.'],
                    ['Prof. Suresh Iyer', 'TNPSC Expert',     'IAS (Retd.)', '15+ Years', '300+ TNPSC Selections', '#7c3aed', '👨‍🏫',
                     'A retired IAS officer with firsthand experience in the civil services, Prof. Suresh Iyer brings unmatched insight into TNPSC Group 1, 2 and 4 preparation strategies.'],
                    ['Dr. Anjali Reddy',  'Biology Expert',   'MBBS',        '8+ Years',  '350+ NEET Selections',  '#b45309', '👩‍🏫',
                     'Dr. Anjali Reddy specialises in Botany and Zoology, making even the most intricate biological concepts crystal clear with her visual teaching approach.'],
                ] as [$name, $role, $qual, $exp, $sel, $color, $emoji, $bio])
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-shadow overflow-hidden">
                    <div class="p-6 text-center border-b border-gray-100">
                        <div class="h-24 w-24 rounded-full mx-auto mb-4 flex items-center justify-center text-4xl"
                             style="background-color:{{ $color }}1a;">
                            {{ $emoji }}
                        </div>
                        <h3 class="font-extrabold text-gray-900 text-base">{{ $name }}</h3>
                        <p class="text-sm font-semibold mt-0.5" style="color:{{ $color }}">{{ $role }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $qual }} · {{ $exp }}</p>
                        <span class="inline-flex items-center gap-1 mt-2 text-xs font-semibold text-green-700 bg-green-50 px-2.5 py-1 rounded-full">
                            🏆 {{ $sel }}
                        </span>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-gray-500 leading-relaxed">{{ $bio }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- More faculty note --}}
            <p class="text-center text-gray-500 text-sm mt-10">+ 11 more expert faculty members across all subjects</p>
        </div>
    </section>

    {{-- ── Teaching Approach ───────────────────────────────────────────────── --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Our Approach</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-2" style="color:#218091;">How Our Faculty Teaches</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['📹', 'Live Interactive Classes',    'Two-way sessions with real-time doubt resolution and full class recordings.'],
                    ['📝', 'Concept-First Approach',      'Every topic starts with fundamentals before moving to exam-level application.'],
                    ['💬', 'Personal Doubt Sessions',     'Dedicated one-on-one time with faculty to clear even the toughest doubts.'],
                    ['📊', 'Performance Feedback',        'Regular assessments with detailed feedback so students know exactly where to improve.'],
                ] as [$emoji, $title, $desc])
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm text-center">
                    <div class="h-12 w-12 rounded-xl flex items-center justify-center text-2xl mb-4 mx-auto" style="background:#FFF7ED;">{{ $emoji }}</div>
                    <h3 class="font-bold text-gray-900 mb-2 text-sm">{{ $title }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── CTA ─────────────────────────────────────────────────────────────── --}}
    <section class="py-16" style="background:linear-gradient(135deg,#218091,#1a6b7a);">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-extrabold text-white mb-3">Want to Learn from Our Expert Faculty?</h2>
            <p class="text-white/80 mb-8">Book a free demo class and experience our teaching methodology firsthand.</p>
            <a href="{{ route('admission.apply') }}"
               class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-bold text-white rounded-xl shadow-md transition-opacity hover:opacity-90"
               style="background-color:#F97316;">
                Book Free Demo Class →
            </a>
        </div>
    </section>

@endsection
