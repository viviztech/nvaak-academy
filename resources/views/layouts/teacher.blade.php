<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Teacher Portal' }} — NVAAK Academy</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div x-data="{ sidebarOpen: true }" class="flex h-screen overflow-hidden">

        {{-- ============================================================
             SIDEBAR
        ============================================================ --}}
        <aside
            :class="sidebarOpen ? 'w-64' : 'w-16'"
            class="bg-[#1e3a5f] text-white flex flex-col transition-all duration-300 overflow-hidden flex-shrink-0 shadow-xl"
        >
            {{-- Logo / Brand --}}
            <div class="flex items-center justify-between p-4 border-b border-blue-800 flex-shrink-0">
                <div x-show="sidebarOpen"
                     x-transition:enter="transition-opacity duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="flex items-center gap-2 min-w-0">
                    <div class="w-9 h-9 bg-orange-500 rounded-lg flex items-center justify-center font-bold text-white text-base flex-shrink-0">N</div>
                    <div class="min-w-0">
                        <div class="font-bold text-sm leading-tight text-white">NVAAK Academy</div>
                        <div class="text-xs text-blue-300 leading-tight">Faculty Portal</div>
                    </div>
                </div>
                <div x-show="!sidebarOpen"
                     class="w-9 h-9 bg-orange-500 rounded-lg flex items-center justify-center font-bold text-white text-base flex-shrink-0 mx-auto">N</div>
                <button @click="sidebarOpen = !sidebarOpen"
                        class="text-blue-300 hover:text-white p-1.5 rounded-md transition-colors flex-shrink-0 ml-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 py-4 px-2 space-y-0.5 overflow-y-auto">

                {{-- OVERVIEW --}}
                <div x-show="sidebarOpen" class="px-3 pt-2 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Overview</div>
                <a href="{{ route('teacher.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('teacher.dashboard') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate font-medium">Dashboard</span>
                </a>

                {{-- ACADEMICS --}}
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Academics</div>
                <div x-show="!sidebarOpen" class="border-t border-blue-800 mx-2 my-2"></div>

                <a href="{{ route('teacher.attendance') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('teacher.attendance') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Mark Attendance</span>
                </a>

                <a href="{{ route('teacher.syllabus') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('teacher.syllabus') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Syllabus Coverage</span>
                </a>

                {{-- EXAMS --}}
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Exams</div>
                <div x-show="!sidebarOpen" class="border-t border-blue-800 mx-2 my-2"></div>

                <a href="{{ route('teacher.exams') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('teacher.exams') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">My Exams</span>
                </a>

                {{-- IAS --}}
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">IAS Evaluation</div>
                <div x-show="!sidebarOpen" class="border-t border-blue-800 mx-2 my-2"></div>

                <a href="{{ route('teacher.ias.evaluate') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('teacher.ias*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Evaluate Answers</span>
                </a>

                <div class="pb-4"></div>
            </nav>

            {{-- User Info Footer --}}
            <div class="p-4 border-t border-blue-800 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-orange-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'T', 0, 1)) }}
                    </div>
                    <div x-show="sidebarOpen" class="min-w-0">
                        <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Faculty' }}</div>
                        <div class="text-xs text-blue-300 truncate">Faculty</div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ============================================================
             MAIN CONTENT AREA
        ============================================================ --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Top Header --}}
            <header class="bg-white shadow-sm border-b border-gray-200 flex items-center justify-between px-6 py-3 flex-shrink-0 z-10">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Faculty Portal' }}</h1>
                        @isset($subtitle)
                            <p class="text-xs text-gray-500 leading-tight">{{ $subtitle }}</p>
                        @endisset
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Notification Bell --}}
                    <button class="relative text-gray-400 hover:text-gray-600 transition-colors p-1" title="Notifications">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>

                    {{-- User Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-[#1e3a5f] flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'T', 0, 1)) }}
                            </div>
                            <span class="hidden sm:block font-medium">{{ auth()->user()->name ?? 'Faculty' }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
                        >
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Faculty' }}</p>
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('profile') }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                My Profile
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <button onclick="document.getElementById('teacher-logout-form').submit()"
                                    class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Sign Out
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mx-6 mt-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm flex items-center justify-between"
                     x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="text-green-400 hover:text-green-600 ml-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="mx-6 mt-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm flex items-center justify-between"
                     x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 ml-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Hidden Logout Form --}}
    <form id="teacher-logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>

    @livewireScripts
</body>
</html>
