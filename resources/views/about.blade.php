@extends('layouts.public', ['title' => 'About Us – NVAAK IAS & NEET Academy, Avadi Chennai'])
@section('content')

    {{-- ── Page Hero ──────────────────────────────────────────────────────── --}}
    <section class="py-16" style="background:linear-gradient(135deg,#218091 0%,#1a6b7a 60%,#145663 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4"
                  style="background:rgba(249,115,22,0.15); color:#F97316; border:1px solid rgba(249,115,22,0.3);">
                Established 2015 · Avadi, Chennai
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4">About NVAAK IAS & NEET Academy</h1>
            <p class="text-lg text-white/80 max-w-2xl mx-auto">
                A premier coaching institute dedicated to helping students achieve their dreams of cracking NEET and TNPSC examinations.
            </p>
        </div>
    </section>

    {{-- ── Who We Are ─────────────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-14 items-center">
                <div>
                    <span class="text-sm font-semibold uppercase tracking-widest" style="color:#F97316;">Who We Are</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold mt-2 mb-6" style="color:#218091;">More Than a Coaching Centre</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        NVAAK IAS & NEET Academy is a premier coaching institute located in Avadi, Chennai, dedicated to helping students achieve their dreams of cracking NEET and TNPSC examinations.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        With over <strong class="text-gray-800">500 successful students</strong> and an <strong class="text-gray-800">85% success rate</strong>, we have established ourselves as a trusted name in competitive exam coaching in the Avadi region. Our expert faculty, comprehensive study materials, and personalised approach ensure that every student receives the guidance they need to succeed.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        We believe that quality education should be accessible to every aspiring student, regardless of their background. That's why we offer flexible learning options — both at our Avadi centre and online — along with affordable fee structures and EMI facilities.
                    </p>
                </div>

                {{-- Stats grid --}}
                <div class="grid grid-cols-2 gap-5">
                    @foreach([
                        ['500+', 'Students Trained', '🎓'],
                        ['85%',  'NEET Success Rate', '🏆'],
                        ['15+',  'Expert Faculty',    '👨‍🏫'],
                        ['10+',  'Years of Excellence','📅'],
                    ] as [$n, $l, $e])
                    <div class="rounded-2xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-3xl mb-2">{{ $e }}</div>
                        <p class="text-3xl font-extrabold mb-1" style="color:#218091;">{{ $n }}</p>
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
                    ['🎓', 'Expert Faculty from Top Colleges',      'Learn from doctors and retired government officers who have cracked these exams themselves.'],
                    ['📱', 'Hybrid Learning Model',                  'Attend live online classes or visit our Avadi centre — your choice, your convenience.'],
                    ['📊', 'Personalised Performance Tracking',      'AI-powered analytics identify your weak areas so you can focus effort where it matters most.'],
                    ['💰', 'Affordable Fee Structure',               'Quality education at prices that won\'t burden your family, with EMI options available.'],
                    ['📚', 'Complete Study Materials',               'Digital + printed materials covering the entire NEET and TNPSC syllabus, updated every year.'],
                    ['🏆', 'Proven Track Record',                    '85% of our students clear their target exams — a result we\'re proud of and committed to maintaining.'],
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
