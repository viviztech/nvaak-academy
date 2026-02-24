<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    {{-- Heading --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold" style="color:#1E3A5F;">Welcome back</h1>
        <p class="text-sm text-gray-500 mt-1">Sign in to your NVAAK Academy account</p>
    </div>

    {{-- Session status --}}
    @if (session('status'))
        <div class="mb-5 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
            <input wire:model="form.email"
                   id="email" name="email" type="email"
                   required autofocus autocomplete="username"
                   placeholder="you@example.com"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400
                          focus:outline-none focus:ring-2 focus:border-transparent transition"
                   style="focus:ring-color:#1E3A5F;"
                   onfocus="this.style.boxShadow='0 0 0 3px rgba(30,58,95,0.15)'; this.style.borderColor='#1E3A5F';"
                   onblur="this.style.boxShadow=''; this.style.borderColor='#e5e7eb';">
            @error('form.email')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                       class="text-xs font-medium hover:underline" style="color:#F97316;">
                        Forgot password?
                    </a>
                @endif
            </div>
            <input wire:model="form.password"
                   id="password" name="password" type="password"
                   required autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 placeholder-gray-400
                          focus:outline-none transition"
                   onfocus="this.style.boxShadow='0 0 0 3px rgba(30,58,95,0.15)'; this.style.borderColor='#1E3A5F';"
                   onblur="this.style.boxShadow=''; this.style.borderColor='#e5e7eb';">
            @error('form.password')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-2">
            <input wire:model="form.remember"
                   id="remember" name="remember" type="checkbox"
                   class="h-4 w-4 rounded border-gray-300 cursor-pointer"
                   style="accent-color:#1E3A5F;">
            <label for="remember" class="text-sm text-gray-600 cursor-pointer select-none">Remember me</label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-3.5 rounded-xl text-sm font-bold text-white shadow-md transition-opacity hover:opacity-90 flex items-center justify-center gap-2"
                style="background-color:#1E3A5F;">
            <span wire:loading.remove wire:target="login">Sign In</span>
            <span wire:loading wire:target="login" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Signing in…
            </span>
        </button>
    </form>

    {{-- Divider --}}
    <div class="relative my-7">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
        <div class="relative flex justify-center">
            <span class="px-3 bg-white text-xs text-gray-400">New to NVAAK?</span>
        </div>
    </div>

    {{-- Apply CTA --}}
    <a href="{{ route('admission.apply') }}"
       class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl text-sm font-bold border-2 transition-colors hover:bg-orange-50"
       style="border-color:#F97316; color:#F97316;">
        Apply for Admission →
    </a>

    {{-- Back to home --}}
    <p class="text-center text-xs text-gray-400 mt-6">
        <a href="/" class="hover:underline hover:text-gray-600 transition-colors">← Back to Home</a>
    </p>
</div>
