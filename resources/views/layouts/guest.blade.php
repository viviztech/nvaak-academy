<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NVAAK IAS & NEET Academy') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">

    <div class="min-h-screen flex">

        {{-- ── Left panel: brand / illustration ──────────────────────── --}}
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden"
             style="background: linear-gradient(135deg,#1E3A5F 0%,#163050 60%,#0f2240 100%);">

            {{-- Decorative circles --}}
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-10"
                 style="background:#F97316; transform:translate(40%,-40%)"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full opacity-10"
                 style="background:#F97316; transform:translate(-40%,40%)"></div>

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3 relative z-10">
                <img src="/logo.jpeg" alt="NVAAK Academy Logo" class="h-10 w-10 rounded-xl object-cover">
                <div class="leading-tight">
                    <span class="text-xl font-extrabold text-white">NVAAK</span>
                    <span class="text-xs text-blue-300 block -mt-1">IAS & NEET Academy</span>
                </div>
            </a>

            {{-- Centre copy --}}
            <div class="relative z-10 space-y-6">
                <div>
                    <h2 class="text-4xl font-extrabold text-white leading-tight mb-4">
                        Your Path to<br>
                        <span style="color:#F97316;">Medical &</span><br>
                        Government Success
                    </h2>
                    <p class="text-blue-200 text-base leading-relaxed max-w-sm">
                        Expert NEET & TNPSC coaching in Avadi, Chennai. Join 500+ students who achieved their dreams with us.
                    </p>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4">
                    @foreach([['500+','Students'],['85%','Success Rate'],['15+','Faculty']] as [$n,$l])
                    <div class="rounded-xl p-4 text-center border border-white/10" style="background:rgba(255,255,255,0.07);">
                        <p class="text-2xl font-extrabold text-white">{{ $n }}</p>
                        <p class="text-xs text-blue-300 mt-0.5">{{ $l }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <p class="text-blue-400 text-xs relative z-10">
                &copy; {{ date('Y') }} NVAAK IAS & NEET Academy · Avadi, Chennai
            </p>
        </div>

        {{-- ── Right panel: form slot ──────────────────────────────────── --}}
        <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-white">

            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8">
                <a href="/" class="flex items-center gap-2">
                    <img src="/logo.jpeg" alt="NVAAK Academy Logo" class="h-10 w-10 rounded-xl object-cover">
                    <div class="leading-tight">
                        <span class="text-lg font-extrabold" style="color:#1E3A5F;">NVAAK</span>
                        <span class="text-xs text-gray-500 block -mt-1">IAS & NEET Academy</span>
                    </div>
                </a>
            </div>

            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
