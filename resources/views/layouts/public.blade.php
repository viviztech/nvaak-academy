<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'NVAAK Academy') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Public Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center space-x-2">
                    <span class="text-2xl font-bold text-indigo-600">NVAAK</span>
                    <span class="text-sm text-gray-500 font-medium">Academy</span>
                </a>
                <div class="flex items-center space-x-6">
                    <a href="/" class="text-sm text-gray-600 hover:text-indigo-600">Home</a>
                    <a href="{{ route('admission.form') }}" class="text-sm text-gray-600 hover:text-indigo-600">Apply Now</a>
                    <a href="{{ route('admission.track') }}" class="text-sm text-gray-600 hover:text-indigo-600">Track Application</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="p-4 bg-green-50 border border-green-200 rounded-md flex items-center justify-between">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
            <div class="p-4 bg-red-50 border border-red-200 rounded-md flex items-center justify-between">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} NVAAK Academy. All rights reserved.</p>
                <p class="text-xs text-gray-500 mt-1">NEET & IAS Coaching Institute</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
