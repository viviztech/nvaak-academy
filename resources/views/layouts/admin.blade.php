<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — NVAAK IAS & NEET Academy</title>
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
                        <div class="font-bold text-sm leading-tight text-white">NVAAK IAS & NEET Academy</div>
                        <div class="text-xs text-blue-300 leading-tight">Management System</div>
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

            {{-- Navigation (scrollable) --}}
            <nav class="flex-1 py-4 px-2 space-y-0.5 overflow-y-auto">

                {{-- OVERVIEW --}}
                <div x-show="sidebarOpen" class="px-3 pt-2 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Overview</div>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate font-medium">Dashboard</span>
                </a>

                {{-- ADMISSIONS --}}
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Admissions</div>
                <div x-show="!sidebarOpen" class="border-t border-blue-800 mx-2 my-2"></div>

                <a href="{{ route('admin.enquiries.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.enquiries*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Enquiries</span>
                </a>

                <a href="{{ route('admin.admissions.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.admissions*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Admissions</span>
                </a>

                {{-- ACADEMICS --}}
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Academics</div>
                <div x-show="!sidebarOpen" class="border-t border-blue-800 mx-2 my-2"></div>

                <a href="{{ route('admin.batches.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.batches*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Batches</span>
                </a>

                <a href="{{ route('admin.students.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.students*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Students</span>
                </a>

                <a href="{{ route('admin.faculty.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.faculty*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Faculty</span>
                </a>

                <a href="{{ route('admin.subjects.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.subjects*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Subjects</span>
                </a>

                <a href="{{ route('admin.syllabus.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.syllabus*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Syllabus</span>
                </a>

                {{-- EXAMS --}}
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Exams</div>
                <div x-show="!sidebarOpen" class="border-t border-blue-800 mx-2 my-2"></div>

                <a href="{{ route('admin.exams.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.exams*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Exams</span>
                </a>

                <a href="{{ route('admin.questions.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.questions*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Question Bank</span>
                </a>

                {{-- FINANCIALS --}}
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Financials</div>
                <div x-show="!sidebarOpen" class="border-t border-blue-800 mx-2 my-2"></div>

                <a href="{{ route('admin.fees.structures') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.fees*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Fee Management</span>
                </a>

                {{-- MANAGEMENT --}}
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Management</div>
                <div x-show="!sidebarOpen" class="border-t border-blue-800 mx-2 my-2"></div>

                <a href="{{ route('admin.attendance.mark') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.attendance*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Attendance</span>
                </a>

                <a href="{{ route('admin.materials.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.materials*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Study Materials</span>
                </a>

                {{-- INSIGHTS --}}
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Insights</div>
                <div x-show="!sidebarOpen" class="border-t border-blue-800 mx-2 my-2"></div>

                <a href="{{ route('admin.analytics.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.analytics*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Analytics</span>
                </a>

                <a href="{{ route('admin.ias.submissions') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.ias*') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">IAS Evaluation</span>
                </a>

                {{-- COMMUNICATION --}}
                <div x-show="sidebarOpen" class="px-3 pt-4 pb-1 text-xs font-semibold text-blue-400 uppercase tracking-wider">Communication</div>
                <div x-show="!sidebarOpen" class="border-t border-blue-800 mx-2 my-2"></div>

                <a href="{{ route('admin.communication.announcements') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.communication.announcements') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Announcements</span>
                </a>

                <a href="{{ route('admin.communication.sms') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors
                          {{ request()->routeIs('admin.communication.sms') ? 'bg-orange-500 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span x-show="sidebarOpen" class="truncate">Bulk SMS</span>
                </a>

                <div class="pb-4"></div>
            </nav>

            {{-- User Info Footer --}}
            <div class="p-4 border-t border-blue-800 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-orange-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div x-show="sidebarOpen" class="min-w-0">
                        <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Admin User' }}</div>
                        <div class="text-xs text-blue-300 truncate">{{ auth()->user()->getRoleNames()->first() ?? 'Administrator' }}</div>
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
                    {{-- Mobile hamburger --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
                        @isset($subtitle)
                            <p class="text-xs text-gray-500 leading-tight">{{ $subtitle }}</p>
                        @endisset
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Notification Bell --}}
                    <button class="relative text-gray-400 hover:text-gray-600 transition-colors p-1" title="Notifications">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-0.5 right-0.5 w-2 h-2 bg-orange-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    {{-- User Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-[#1e3a5f] flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="hidden sm:block font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
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
                                <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</p>
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
                            <button onclick="document.getElementById('logout-form').submit()"
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
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-400 hover:text-green-600 ml-4 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="mx-6 mt-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm flex items-center justify-between"
                     x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 ml-4 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif
            @if(session('warning'))
                <div class="mx-6 mt-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg text-sm flex items-center justify-between"
                     x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('warning') }}</span>
                    </div>
                    <button @click="show = false" class="text-yellow-400 hover:text-yellow-600 ml-4 flex-shrink-0">
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
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>

    @livewireScripts
</body>
</html>
