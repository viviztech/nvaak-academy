@extends('layouts.public', ['title' => 'Success Stories – NVAAK IAS & NEET Academy'])
@section('content')

    {{-- ── Page Hero ──────────────────────────────────────────────────────── --}}
    <section class="py-16" style="background:linear-gradient(135deg,#1E3A5F 0%,#163050 60%,#0f2240 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4"
                  style="background:rgba(249,115,22,0.15); color:#F97316; border:1px solid rgba(249,115,22,0.3);">
                Proven Results
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4">Success Stories</h1>
            <p class="text-lg text-blue-200 max-w-2xl mx-auto">
                Real students, real results — see how NVAAK IAS & NEET Academy helped them achieve their dreams.
            </p>
        </div>
    </section>

    {{-- ── Stats ───────────────────────────────────────────────────────────── --}}
    <section class="py-12 bg-orange-50 border-b border-orange-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
                @foreach([['500+','Students Trained'],['85%','NEET Success Rate'],['300+','TNPSC Selections'],['4.8★','Average Rating']] as [$n,$l])
                <div>
                    <p class="text-3xl font-extrabold" style="color:#1E3A5F;">{{ $n }}</p>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $l }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Testimonials ────────────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @foreach([
                ['Priya Sharma',  'NEET 2025 – AIR 245 · Madras Medical College',
                 '"NVAAK Academy\'s structured approach and constant doubt-clearing sessions helped me secure admission in Madras Medical College. The faculty\'s dedication and personalised attention made all the difference. I\'m grateful for their support throughout my journey."',
                 'neet', '👤'],
                ['Rajesh Kumar',  'TNPSC Group 1 – Selected',
                 '"The daily current affairs updates and comprehensive study materials made all the difference. Thank you NVAAK Academy! The mock tests and interview preparation sessions were particularly helpful. I couldn\'t have done it without your guidance."',
                 'tnpsc', '👤'],
                ['Anjali Menon',  'NEET 2024 – AIR 512 · Coimbatore Medical College',
                 '"The live classes and recorded sessions were a game-changer for me. Being able to revisit difficult topics at my own pace helped me understand concepts better. The faculty\'s teaching methods are excellent and the study materials are comprehensive."',
                 'neet', '👤'],
                ['Suresh Iyer',   'TNPSC Group 2 – Selected',
                 '"NVAAK Academy provided excellent coaching for TNPSC Group 2. The bilingual approach (Tamil & English) was perfect for me. The regular mock tests and performance analysis helped me identify and improve my weak areas significantly."',
                 'tnpsc', '👤'],
            ] as [$name, $result, $quote, $type, $avatar])
            <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <div class="h-20 w-20 rounded-full flex items-center justify-center text-3xl shrink-0"
                         style="background:{{ $type === 'neet' ? '#EFF6FF' : '#F0FDF4' }};">
                        {{ $avatar }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-1 mb-2">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900">{{ $name }}</h3>
                        <p class="text-sm font-bold mb-3" style="color:#F97316;">{{ $result }}</p>
                        <p class="text-gray-600 leading-relaxed italic">{{ $quote }}</p>
                        <span class="inline-flex items-center mt-3 text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $type === 'neet' ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700' }}">
                            {{ $type === 'neet' ? 'NEET' : 'TNPSC' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ── CTA ─────────────────────────────────────────────────────────────── --}}
    <section class="py-16" style="background:linear-gradient(135deg,#1E3A5F,#163050);">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-extrabold text-white mb-3">Ready to Write Your Success Story?</h2>
            <p class="text-blue-200 mb-8">Join hundreds of successful students at NVAAK IAS & NEET Academy.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-3">
                <a href="{{ route('admission.apply') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-bold text-white rounded-xl shadow-md transition-opacity hover:opacity-90"
                   style="background-color:#F97316;">
                    Apply Now →
                </a>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-semibold text-white rounded-xl border-2 border-white/30 hover:bg-white/10 transition-colors">
                    Book Free Demo
                </a>
            </div>
        </div>
    </section>

@endsection
