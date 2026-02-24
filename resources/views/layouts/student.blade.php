<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Student Portal' }} — NVAAK IAS & NEET Academy</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body class="antialiased bg-gray-50 font-sans">

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col"
           style="background-color: #1e3a5f;">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-5 py-4 border-b border-blue-900">
            <img src="/logo.jpeg" alt="NVAAK Logo" class="w-9 h-9 rounded-xl object-cover flex-shrink-0">
            <div>
                <p class="text-white font-bold text-sm leading-tight">NVAAK IAS & NEET Academy</p>
                <p class="text-blue-300 text-xs">Student Portal</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">

            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 pt-2 pb-1">Overview</p>
            <a href="{{ route('student.dashboard') }}"
               @class([
                   'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                   'text-white' => request()->routeIs('student.dashboard'),
                   'text-blue-200 hover:text-white hover:bg-white/10' => !request()->routeIs('student.dashboard'),
               ])
               @style(['background-color: #f97316' => request()->routeIs('student.dashboard')])>
                <span>&#128202;</span> Dashboard
            </a>

            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 pt-3 pb-1">Academics</p>
            <a href="#"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-blue-200 hover:text-white hover:bg-white/10 transition-colors">
                <span>&#128218;</span> My Courses
            </a>
            <a href="#"
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-blue-200 hover:text-white hover:bg-white/10 transition-colors">
                <span>&#9999;</span> Exams &amp; Tests
            </a>

            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 pt-3 pb-1">Fees</p>
            <a href="{{ route('student.fees') }}"
               @class([
                   'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                   'text-white' => request()->routeIs('student.fees') && !request()->routeIs('student.fees.history'),
                   'text-blue-200 hover:text-white hover:bg-white/10' => !request()->routeIs('student.fees') || request()->routeIs('student.fees.history'),
               ])
               @style(['background-color: #f97316' => request()->routeIs('student.fees') && !request()->routeIs('student.fees.history')])>
                <span>&#128176;</span> Pay Fees
            </a>
            <a href="{{ route('student.fees.history') }}"
               @class([
                   'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                   'text-white' => request()->routeIs('student.fees.history'),
                   'text-blue-200 hover:text-white hover:bg-white/10' => !request()->routeIs('student.fees.history'),
               ])
               @style(['background-color: #f97316' => request()->routeIs('student.fees.history')])>
                <span>&#128179;</span> Payment History
            </a>

            <p class="text-blue-400 text-xs font-semibold uppercase tracking-wider px-3 pt-3 pb-1">Attendance</p>
            <a href="{{ route('student.attendance') }}"
               @class([
                   'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                   'text-white' => request()->routeIs('student.attendance'),
                   'text-blue-200 hover:text-white hover:bg-white/10' => !request()->routeIs('student.attendance'),
               ])
               @style(['background-color: #f97316' => request()->routeIs('student.attendance')])>
                <span>&#128197;</span> My Attendance
            </a>

        </nav>

        <!-- Sidebar footer -->
        <div class="px-4 py-3 border-t border-blue-900">
            <p class="text-blue-400 text-xs text-center">NVAAK IAS & NEET Academy &copy; {{ date('Y') }}</p>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="ml-64 flex flex-col min-h-screen">

        <!-- Top Navbar -->
        <header class="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">
            <div class="flex items-center justify-between px-6 h-14">

                <!-- Page Title -->
                <h1 class="text-base font-semibold text-gray-800">{{ $title ?? 'Student Portal' }}</h1>

                <!-- Right side controls -->
                <div class="flex items-center gap-3" x-data="{ profileOpen: false }">

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button @click="profileOpen = !profileOpen"
                                class="flex items-center gap-2 px-2 py-1.5 hover:bg-gray-100 rounded-lg transition-colors">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold"
                                 style="background-color: #1e3a5f;">
                                {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                            </div>
                            <div class="text-left hidden md:block">
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Student' }}</p>
                                <p class="text-xs text-gray-400">Student</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="profileOpen" @click.outside="profileOpen = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden">
                            <div class="py-1">
                                <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <span>&#128100;</span> My Profile
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <span>&#128682;</span> Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
