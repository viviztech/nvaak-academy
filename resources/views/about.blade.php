@extends('layouts.public', ['title' => 'About Us – NVAAK IAS & NEET Academy | NEET & IAS Coaching in Avadi, Chennai'])
@section('content')

    {{-- ── Page Hero ──────────────────────────────────────────────────────── --}}
    <section class="py-16" style="background:linear-gradient(135deg,#218091 0%,#1a6b7a 60%,#145663 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4"
                  style="background:rgba(249,115,22,0.15); color:#F97316; border:1px solid rgba(249,115,22,0.3);">
                Inaugurated April 2026 · Avadi, Chennai
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4">About NVAAK IAS & NEET Academy</h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto">
                A dedicated coaching institute in Avadi, Chennai for UPSC, TNPSC, NEET, JEE and other competitive examinations.
            </p>
        </div>
    </section>

    {{-- ── Who We Are ─────────────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-14 items-center">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Who We Are</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold mt-2 mb-6" style="color:#218091;">Coaching That Puts You First</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        NVAAK IAS & NEET Academy is a newly inaugurated coaching institute located in Avadi, Chennai — built with one purpose: to give students in this neighbourhood the same quality of competitive exam preparation available in the city, without the commute or the cost.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        We offer coaching for UPSC (IAS/IPS/IFS), TNPSC (Group 1, 2, 2A, 4), NEET, JEE, DEO/BEO, PG-TRB/TET, and SI/Police exams. Our faculty brings deep subject expertise and a genuine commitment to each student's progress.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        We keep our batch sizes small deliberately — so every student gets individual attention, personalised feedback, and the focused guidance needed to succeed in highly competitive exams.
                    </p>
                </div>

                {{-- Highlights grid --}}
                <div class="grid grid-cols-2 gap-5">
                    @foreach([
                        ['UPSC · TNPSC · NEET · JEE', 'Courses Offered',        '📚'],
                        ['Small',                      'Batch Sizes',            '👥'],
                        ['Bilingual',                  'Tamil & English Medium', '🗣️'],
                        ['Avadi',                      'Near Ponnu Super Bazaar','📍'],
                    ] as [$n, $l, $e])
                    <div class="rounded-2xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-3xl mb-2">{{ $e }}</div>
                        <p class="text-lg font-extrabold mb-1 leading-tight" style="color:#218091;">{{ $n }}</p>
                        <p class="text-sm text-gray-500">{{ $l }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ── Mission & Vision ────────────────────────────────────────────────── --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="h-12 w-12 rounded-xl flex items-center justify-center text-2xl mb-5" style="background:#FFF7ED;">🎯</div>
                    <h3 class="text-xl font-bold mb-3" style="color:#218091;">Our Mission</h3>
                    <p class="text-gray-600 leading-relaxed">
                        To provide quality education and guidance to aspiring medical and government service candidates, helping them achieve their career goals through expert coaching, comprehensive study materials, and unwavering personal support.
                    </p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="h-12 w-12 rounded-xl flex items-center justify-center text-2xl mb-5" style="background:#FFF7ED;">🌟</div>
                    <h3 class="text-xl font-bold mb-3" style="color:#218091;">Our Vision</h3>
                    <p class="text-gray-600 leading-relaxed">
                        To be the most trusted and results-driven coaching institute in Tamil Nadu — where every student who walks through our doors leaves with the knowledge, confidence, and skills to succeed in their target examinations.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Why Choose Us ───────────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Why Choose Us</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold mt-2" style="color:#218091;">What Makes NVAAK Different</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['🎓', 'Experienced Faculty',                    'Faculty with strong subject expertise dedicated to UPSC, TNPSC, NEET and JEE preparation.'],
                    ['📱', 'Hybrid Learning Model',                  'Attend live online classes or visit our Avadi centre — your choice, your convenience.'],
                    ['📊', 'Personalised Performance Tracking',      'Regular assessments and one-on-one feedback to keep your preparation on track.'],
                    ['💰', 'Affordable Fee Structure',               'Quality education at prices that won\'t burden your family, with EMI options available.'],
                    ['📚', 'Complete Study Materials',               'Digital + printed materials covering the full syllabus for every course we offer.'],
                    ['👥', 'Small Batch Sizes',                      'Limited seats per batch so every student gets individual attention and focused guidance.'],
                ] as [$emoji, $title, $desc])
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="h-12 w-12 rounded-xl flex items-center justify-center text-2xl mb-4" style="background:#FFF7ED;">{{ $emoji }}</div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ $title }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── CTA ─────────────────────────────────────────────────────────────── --}}
    <section class="py-16 bg-orange-50 border-y border-orange-100">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-extrabold mb-3" style="color:#218091;">Ready to Begin Your Journey?</h2>
            <p class="text-gray-600 mb-8">Book a free demo class and see the NVAAK difference for yourself — no commitment required.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('admission.apply') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-bold text-white rounded-xl shadow-md transition-opacity hover:opacity-90"
                   style="background-color:#F97316;">
                    Apply Now →
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-semibold rounded-xl border-2 transition-colors hover:bg-white"
                   style="border-color:#218091; color:#218091;">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

@endsection
