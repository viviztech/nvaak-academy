<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'NVAAK IAS & NEET Academy – NEET & TNPSC Coaching in Avadi, Chennai' }}</title>
    <meta name="description" content="Expert NEET & TNPSC coaching in Avadi, Chennai. Live classes, 15+ expert faculty, 85% success rate. 500+ students trained.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-white">

    {{-- ── Navigation ─────────────────────────────────────────────────── --}}
    <nav x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2">
                    <img src="/logo.jpeg" alt="NVAAK Academy Logo" class="h-10 w-10 rounded-lg object-cover">
                    <div class="leading-tight">
                        <span class="text-lg font-extrabold" style="color:#1E3A5F">NVAAK</span>
                        <span class="text-xs text-gray-500 block -mt-1">IAS & NEET Academy</span>
                    </div>
                </a>

                {{-- Desktop nav --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors">Home</a>
                    <a href="{{ route('about') }}" class="text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors">About</a>
                    <a href="{{ route('faculty') }}" class="text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors">Faculty</a>
                    <a href="{{ route('testimonials') }}" class="text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors">Results</a>
                    <a href="{{ route('contact') }}" class="text-sm font-medium text-gray-700 hover:text-orange-500 transition-colors">Contact</a>
                </div>

                {{-- CTA buttons --}}
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Login</a>
                    <a href="{{ route('admission.apply') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-sm transition-colors"
                       style="background-color:#F97316;">
                        Apply Now
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>

                {{-- Mobile hamburger --}}
                <button @click="open = !open" class="md:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100">
                    <svg x-show="!open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Mobile menu --}}
            <div x-show="open" x-transition class="md:hidden pb-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">Home</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">About</a>
                <a href="{{ route('faculty') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">Faculty</a>
                <a href="{{ route('testimonials') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">Results</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">Contact</a>
                <a href="{{ route('login') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md">Login</a>
                <a href="{{ route('admission.apply') }}" class="block mx-3 mt-2 py-2.5 text-sm font-semibold text-white text-center rounded-lg" style="background-color:#F97316;">Apply Now</a>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="max-w-7xl mx-auto mt-4 px-4">
            <div class="p-4 bg-green-50 border border-green-200 rounded-xl flex items-center justify-between text-sm text-green-700">
                {{ session('success') }}
                <button @click="show = false" class="ml-4 text-green-500 hover:text-green-700">&times;</button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="max-w-7xl mx-auto mt-4 px-4">
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl flex items-center justify-between text-sm text-red-700">
                {{ session('error') }}
                <button @click="show = false" class="ml-4 text-red-500 hover:text-red-700">&times;</button>
            </div>
        </div>
    @endif

    <main>@yield('content'){{ $slot ?? '' }}</main>

    {{-- ── Footer ─────────────────────────────────────────────────────── --}}
    <footer style="background-color:#1E3A5F;" class="text-white mt-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                {{-- Brand --}}
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="/logo.jpeg" alt="NVAAK Academy Logo" class="h-10 w-10 rounded-lg object-cover">
                        <span class="text-xl font-extrabold text-white">NVAAK IAS & NEET Academy</span>
                    </div>
                    <p class="text-sm text-blue-200 leading-relaxed mb-4">
                        Expert NEET & TNPSC coaching in Avadi, Chennai. Empowering students to achieve their medical and government career dreams since 2015.
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="https://wa.me/919940528779" target="_blank"
                           class="h-9 w-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <span class="text-sm text-blue-200">+91 99405 28779</span>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-sm text-blue-200 hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-sm text-blue-200 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('faculty') }}" class="text-sm text-blue-200 hover:text-white transition-colors">Our Faculty</a></li>
                        <li><a href="{{ route('testimonials') }}" class="text-sm text-blue-200 hover:text-white transition-colors">Success Stories</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm text-blue-200 hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="{{ route('admission.apply') }}" class="text-sm text-orange-400 hover:text-orange-300 font-medium transition-colors">Apply Now →</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Contact</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2">
                            <svg class="h-4 w-4 text-orange-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-sm text-blue-200">No. 3517 A, TNHB, Avadi,<br>Chennai – 600054</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:+919940528779" class="text-sm text-blue-200 hover:text-white transition-colors">+91 99405 28779</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:info@nvaakacademy.com" class="text-sm text-blue-200 hover:text-white transition-colors">info@nvaakacademy.com</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-orange-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm text-blue-200">Mon–Sat: 9 AM – 6 PM</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-sm text-blue-300">&copy; {{ date('Y') }} NVAAK IAS & NEET Academy. All rights reserved.</p>
                <p class="text-xs text-blue-400">NEET & TNPSC Coaching Institute, Avadi, Chennai</p>
            </div>
        </div>
    </footer>

    {{-- ── CRO Components ───────────────────────────────────────────── --}}
    <x-exit-intent-popup />
    <x-entry-intent-popup />
    <x-sticky-footer-cta />
    <x-social-proof-notification />

    @livewireScripts
</body>
</html>
