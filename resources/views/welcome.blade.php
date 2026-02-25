@extends('layouts.public', ['title' => 'NVAAK IAS & NEET Academy – NEET & TNPSC Coaching in Avadi, Chennai'])
@section('content')

    {{-- ── Hero Section ────────────────────────────────────────────────── --}}
    <section class="bg-gradient-to-br from-nvaak-cream-50 to-nvaak-teal-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">

                {{-- Left: copy --}}
                <div>
                    <span class="inline-block bg-yellow-400 text-gray-900 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                        🏆 85% Success Rate in NEET 2025
                    </span>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 text-gray-900">
                        Your Dream Medical &
                        <span class="text-nvaak-teal">Government Career</span> Starts Here
                    </h1>
                    <p class="text-xl mb-8 text-gray-700">
                        Expert NEET & TNPSC coaching in Avadi, Chennai.
                        Live classes, expert faculty, proven results.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a href="{{ route('admission.apply') }}"
                           class="bg-nvaak-teal text-white px-8 py-3 rounded-lg hover:bg-nvaak-teal-600 transition font-semibold text-center">
                            Book Free Demo Class 🎯
                        </a>
                        <a href="#courses"
                           class="bg-white text-nvaak-teal border-2 border-nvaak-teal px-8 py-3 rounded-lg hover:bg-nvaak-teal-50 transition font-semibold text-center">
                            View Courses →
                        </a>
                    </div>

                    <div class="flex flex-wrap gap-8">
                        <div>
                            <strong class="text-2xl text-nvaak-teal block">500+</strong>
                            <span class="text-gray-600">Students Trained</span>
                        </div>
                        <div>
                            <strong class="text-2xl text-nvaak-teal block">85%</strong>
                            <span class="text-gray-600">Success Rate</span>
                        </div>
                        <div>
                            <strong class="text-2xl text-nvaak-teal block">15+</strong>
                            <span class="text-gray-600">Expert Faculty</span>
                        </div>
                    </div>
                </div>

                {{-- Right: visual --}}
                <div>
                    <div class="bg-gradient-to-br from-nvaak-teal to-nvaak-teal-600 rounded-2xl p-8 text-white text-center h-96 flex items-center justify-center">
                        <div>
                            <div class="text-6xl mb-4">🎓</div>
                            <p class="text-xl font-semibold">Success Stories</p>
                            <p class="text-white/80 mt-2">500+ students cleared NEET & TNPSC</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Courses Section ──────────────────────────────────────────────── --}}
    <section id="courses" class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-bold mb-12 text-gray-900">Choose Your Path to Success</h2>

            <div class="grid md:grid-cols-2 gap-8">
                {{-- NEET Card --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 hover:shadow-xl transition">
                    <div class="text-5xl mb-4">🩺</div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">NEET Coaching</h3>
                    <p class="text-gray-700 mb-6">Crack medical entrance with expert guidance</p>
                    <ul class="space-y-2 mb-6 text-gray-700">
                        <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> Live Online Classes</li>
                        <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> 500+ Mock Tests</li>
                        <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> NCERT-focused preparation</li>
                        <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> Doubt clearing sessions</li>
                    </ul>
                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admission.apply') }}" class="bg-nvaak-teal text-white px-6 py-2 rounded-lg hover:bg-nvaak-teal-600 transition font-semibold">
                            Enroll Now →
                        </a>
                    </div>
                </div>

                {{-- TNPSC Card --}}
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 hover:shadow-xl transition">
                    <div class="text-5xl mb-4">🏛️</div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">TNPSC Coaching</h3>
                    <p class="text-gray-700 mb-6">Secure your government job with expert coaching</p>
                    <ul class="space-y-2 mb-6 text-gray-700">
                        <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> Tamil & English Medium</li>
                        <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> Daily Current Affairs</li>
                        <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> Previous year papers</li>
                        <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> Interview preparation</li>
                    </ul>
                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admission.apply') }}" class="bg-nvaak-teal text-white px-6 py-2 rounded-lg hover:bg-nvaak-teal-600 transition font-semibold">
                            Enroll Now →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Why NVAAK Section ────────────────────────────────────────────── --}}
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-bold mb-4 text-gray-900">Why Students Choose NVAAK Academy</h2>
            <p class="text-center text-gray-600 mb-12">Located in the heart of Avadi, Chennai — your neighbourhood coaching centre</p>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach([
                    ['🎓', 'Expert Faculty from Top Colleges',       'Learn from doctors and government officers who\'ve cracked these exams'],
                    ['📱', 'Hybrid Learning Model',                   'Attend live online classes or visit our Avadi centre — your choice!'],
                    ['📊', 'Personalised Performance Tracking',       'AI-powered analytics to identify weak areas and improve scores'],
                    ['💰', 'Affordable Fee Structure',                'Quality education at prices that won\'t burden your family'],
                    ['📚', 'Complete Study Materials',                'Digital + printed materials covering the entire syllabus'],
                    ['🏆', 'Proven Track Record',                     '85% of our students clear their target exams'],
                ] as [$emoji, $title, $desc])
                <div class="text-center bg-white p-6 rounded-lg shadow-sm">
                    <div class="text-5xl mb-4">{{ $emoji }}</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900">{{ $title }}</h3>
                    <p class="text-gray-600">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Success Stories ──────────────────────────────────────────────── --}}
    <section class="bg-gradient-to-r from-nvaak-teal-500 to-nvaak-teal-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-bold mb-4">Our Students' Success Stories</h2>
            <p class="text-center text-white/80 mb-12">Real students, Real results</p>

            <div x-data="{ slide: 0, slides: 2 }" class="relative max-w-3xl mx-auto">
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500" :style="`transform: translateX(-${slide * 100}%)`">

                        <div class="min-w-full px-4">
                            <div class="bg-white text-gray-900 rounded-2xl p-8 flex flex-col md:flex-row gap-8 items-center">
                                <div class="w-24 h-24 bg-nvaak-teal rounded-full flex items-center justify-center text-4xl shrink-0">👤</div>
                                <div>
                                    <h3 class="text-xl font-bold mb-1">Priya Sharma</h3>
                                    <p class="text-nvaak-teal font-semibold mb-3">NEET 2025 – AIR 245 · Madras Medical College</p>
                                    <p class="text-gray-600">"NVAAK Academy's structured approach and constant doubt-clearing sessions helped me secure admission in Madras Medical College."</p>
                                    <div class="text-yellow-400 mt-3">⭐⭐⭐⭐⭐</div>
                                </div>
                            </div>
                        </div>

                        <div class="min-w-full px-4">
                            <div class="bg-white text-gray-900 rounded-2xl p-8 flex flex-col md:flex-row gap-8 items-center">
                                <div class="w-24 h-24 bg-nvaak-teal rounded-full flex items-center justify-center text-4xl shrink-0">👤</div>
                                <div>
                                    <h3 class="text-xl font-bold mb-1">Rajesh Kumar</h3>
                                    <p class="text-nvaak-teal font-semibold mb-3">TNPSC Group 1 – Selected</p>
                                    <p class="text-gray-600">"The daily current affairs updates and comprehensive study materials made all the difference. Thank you NVAAK Academy!"</p>
                                    <div class="text-yellow-400 mt-3">⭐⭐⭐⭐⭐</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <button @click="slide = (slide - 1 + slides) % slides"
                        class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white text-nvaak-teal p-3 rounded-full hover:bg-gray-100 shadow-md">←</button>
                <button @click="slide = (slide + 1) % slides"
                        class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white text-nvaak-teal p-3 rounded-full hover:bg-gray-100 shadow-md">→</button>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('testimonials') }}" class="bg-white text-nvaak-teal px-8 py-3 rounded-lg hover:bg-gray-100 transition font-semibold">
                    View All Success Stories →
                </a>
            </div>
        </div>
    </section>

    {{-- ── How It Works ──────────────────────────────────────────────────── --}}
    <section class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-bold mb-12 text-gray-900">Your Journey to Success in 4 Simple Steps</h2>

            <div class="grid md:grid-cols-4 gap-8">
                @foreach([
                    ['01', 'Book Free Demo',    'Experience our teaching style with zero commitment'],
                    ['02', 'Choose Your Course','Select NEET or TNPSC based on your goals'],
                    ['03', 'Start Learning',    'Join live classes, access materials, take tests'],
                    ['04', 'Crack Your Exam',   'Clear your target with our expert guidance'],
                ] as [$step, $title, $desc])
                <div class="text-center">
                    <div class="bg-nvaak-teal text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">{{ $step }}</div>
                    <h3 class="text-xl font-semibold mb-2 text-gray-900">{{ $title }}</h3>
                    <p class="text-gray-600">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Features Tabs ─────────────────────────────────────────────────── --}}
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-3xl font-bold mb-12 text-gray-900">Everything You Need to Succeed</h2>

            <div x-data="{ tab: 'live' }">
                <div class="flex justify-center gap-4 mb-12 flex-wrap">
                    @foreach([
                        ['live',      '📹', 'Live Classes'],
                        ['tests',     '📝', 'Mock Tests'],
                        ['materials', '📚', 'Study Materials'],
                        ['doubts',    '💬', 'Doubt Clearing'],
                    ] as [$key, $emoji, $label])
                    <button @click="tab = '{{ $key }}'"
                            :class="tab === '{{ $key }}' ? 'bg-nvaak-teal text-white' : 'bg-white text-gray-700'"
                            class="px-6 py-3 rounded-lg font-semibold transition">
                        {{ $emoji }} {{ $label }}
                    </button>
                    @endforeach
                </div>

                <div x-show="tab === 'live'" x-transition class="grid md:grid-cols-2 gap-12 items-center bg-white p-8 rounded-lg">
                    <div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-900">Interactive Live Online Classes</h3>
                        <ul class="space-y-3 text-gray-700 mb-6">
                            @foreach(['Two-way audio/video interaction with faculty','Real-time doubt resolution during class','Screen sharing and digital whiteboard','Automatic attendance tracking','Class recordings available 24/7'] as $f)
                            <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> {{ $f }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('admission.apply') }}" class="bg-nvaak-teal text-white px-6 py-3 rounded-lg hover:bg-nvaak-teal-600 transition font-semibold">
                            Try Free Demo Class
                        </a>
                    </div>
                    <div class="bg-gradient-to-br from-nvaak-teal to-nvaak-teal-600 rounded-lg h-64 flex items-center justify-center text-white text-6xl">📹</div>
                </div>

                <div x-show="tab === 'tests'" x-transition class="grid md:grid-cols-2 gap-12 items-center bg-white p-8 rounded-lg">
                    <div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-900">Comprehensive Mock Tests</h3>
                        <ul class="space-y-3 text-gray-700">
                            @foreach(['1000+ practice questions in question bank','Previous year NEET & TNPSC papers','Detailed subject-wise performance analysis','Time-bound exam simulation with NEET pattern','Instant results with answer explanations'] as $f)
                            <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> {{ $f }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg h-64 flex items-center justify-center text-white text-6xl">📝</div>
                </div>

                <div x-show="tab === 'materials'" x-transition class="grid md:grid-cols-2 gap-12 items-center bg-white p-8 rounded-lg">
                    <div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-900">Complete Study Materials</h3>
                        <ul class="space-y-3 text-gray-700">
                            @foreach(['Digital PDFs and chapter-wise notes','Video lecture library accessible anytime','Printed materials available at centre','Updated annually with latest exam patterns','Mobile app access for offline study'] as $f)
                            <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> {{ $f }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg h-64 flex items-center justify-center text-white text-6xl">📚</div>
                </div>

                <div x-show="tab === 'doubts'" x-transition class="grid md:grid-cols-2 gap-12 items-center bg-white p-8 rounded-lg">
                    <div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-900">24/7 Doubt Clearing</h3>
                        <ul class="space-y-3 text-gray-700">
                            @foreach(['Dedicated daily doubt-clearing sessions','One-on-one support from subject experts','Quick response via WhatsApp & platform','Video explanations for complex topics','Subject-wise expert faculty assigned'] as $f)
                            <li class="flex items-center gap-2"><span class="text-nvaak-teal font-bold">✓</span> {{ $f }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg h-64 flex items-center justify-center text-white text-6xl">💬</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Final CTA ─────────────────────────────────────────────────────── --}}
    <section class="bg-gradient-to-r from-nvaak-teal-600 to-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Start Your Success Journey?</h2>
            <p class="text-xl mb-8 text-white/80">Join 500+ students who are already preparing with NVAAK Academy</p>

            <div class="flex justify-center gap-4 flex-wrap">
                <a href="{{ route('admission.apply') }}"
                   class="bg-white text-nvaak-teal px-8 py-3 rounded-lg hover:bg-gray-100 transition font-semibold">
                    Book Free Demo Class
                </a>
                <a href="{{ route('contact') }}"
                   class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg hover:bg-white hover:text-nvaak-teal transition font-semibold">
                    Contact Us
                </a>
            </div>

            <p class="mt-8 text-sm text-white/70">⏰ Limited seats available for 2026 batch — Enroll before December 31!</p>
        </div>
    </section>

    {{-- ── Contact Section ──────────────────────────────────────────────── --}}
    <section id="contact" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-start">

                {{-- Contact info --}}
                <div>
                    <h2 class="text-3xl font-bold mb-6 text-gray-900">Visit Our Avadi Centre</h2>
                    <div class="space-y-4">
                        @foreach([
                            ['📍', 'Address',      'No. 3517 A, TNHB, Avadi, Chennai – 600054', null],
                            ['📞', 'Phone',        '+91 99405 28779', 'tel:+919940528779'],
                            ['💬', 'WhatsApp',     '+91 99405 28779', 'https://wa.me/919940528779'],
                            ['✉️', 'Email',        'info@nvaakacademy.com', 'mailto:info@nvaakacademy.com'],
                            ['🕐', 'Office Hours', 'Mon – Sat: 9:00 AM – 6:00 PM | Sunday: Closed', null],
                        ] as [$emoji, $label, $value, $link])
                        <div class="flex items-start gap-4">
                            <div class="text-2xl">{{ $emoji }}</div>
                            <div>
                                <strong class="text-gray-900">{{ $label }}</strong><br>
                                @if($link)
                                    <a href="{{ $link }}" class="text-nvaak-teal hover:underline" target="{{ str_starts_with($link, 'http') ? '_blank' : '_self' }}">{{ $value }}</a>
                                @else
                                    <span class="text-gray-600">{{ $value }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('admission.apply') }}"
                       class="inline-block bg-nvaak-teal text-white px-6 py-3 rounded-lg hover:bg-nvaak-teal-600 transition mt-8 font-semibold">
                        Book Free Demo Class
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
