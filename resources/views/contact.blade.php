@extends('layouts.public', ['title' => 'Contact Us – NVAAK IAS & NEET Academy, Avadi Chennai'])
@section('content')

    {{-- ── Page Hero ──────────────────────────────────────────────────────── --}}
    <section class="py-16" style="background:linear-gradient(135deg,#1E3A5F 0%,#163050 60%,#0f2240 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-4"
                  style="background:rgba(249,115,22,0.15); color:#F97316; border:1px solid rgba(249,115,22,0.3);">
                We're Here to Help
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4">Contact Us</h1>
            <p class="text-lg text-blue-200 max-w-2xl mx-auto">
                Have questions about our courses? We'd love to hear from you. Reach us by phone, WhatsApp, email or visit our centre.
            </p>
        </div>
    </section>

    {{-- ── Contact + Form ──────────────────────────────────────────────────── --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-14 items-start">

                {{-- Contact Info --}}
                <div>
                    <h2 class="text-2xl font-extrabold mb-8" style="color:#1E3A5F;">Get in Touch</h2>
                    <div class="space-y-4">
                        @foreach([
                            ['📍', 'Our Address',    'No. 3517 A, TNHB, Avadi, Chennai – 600054', null],
                            ['📞', 'Phone',          '+91 99405 28779', 'tel:+919940528779'],
                            ['💬', 'WhatsApp',       '+91 99405 28779', 'https://wa.me/919940528779'],
                            ['✉️', 'Email',          'info@nvaakacademy.com', 'mailto:info@nvaakacademy.com'],
                            ['🕐', 'Office Hours',   'Mon – Sat: 9:00 AM – 6:00 PM | Sunday: Closed', null],
                        ] as [$emoji, $label, $value, $link])
                        <div class="flex items-start gap-4 p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:border-orange-200 transition-colors">
                            <span class="text-2xl">{{ $emoji }}</span>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-0.5">{{ $label }}</p>
                                @if($link)
                                    <a href="{{ $link }}" target="{{ str_starts_with($link, 'http') ? '_blank' : '_self' }}"
                                       class="text-sm font-semibold hover:underline" style="color:#1E3A5F;">{{ $value }}</a>
                                @else
                                    <p class="text-sm font-semibold text-gray-800">{{ $value }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Map --}}
                    <div class="mt-8 rounded-2xl overflow-hidden shadow-md border border-gray-100 h-64">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3884.2!2d80.0984!3d13.1165!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTPCsDA2JzU5LjQiTiA4MMKwMDUnNTQuMiJF!5e0!3m2!1sen!2sin!4v1"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                {{-- Enquiry Form --}}
                <div>
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
                        <h2 class="text-2xl font-extrabold mb-2" style="color:#1E3A5F;">Send Us a Message</h2>
                        <p class="text-sm text-gray-500 mb-6">We usually respond within 2 hours during office hours.</p>

                        <form id="contact-form" x-data="contactForm()" @submit.prevent="submit" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Your Name *</label>
                                <input x-model="form.name" type="text" required placeholder="Full name"
                                       class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-transparent transition">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone *</label>
                                    <input x-model="form.phone" type="tel" required placeholder="+91 XXXXX XXXXX"
                                           class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-transparent transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                                    <input x-model="form.email" type="email" placeholder="you@email.com"
                                           class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-transparent transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Course Interest</label>
                                <select x-model="form.course"
                                        class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-transparent transition">
                                    <option value="">Select a course</option>
                                    <option>NEET 2026 (1-Year)</option>
                                    <option>NEET 2027 (2-Year)</option>
                                    <option>NEET Repeater</option>
                                    <option>TNPSC Group 1</option>
                                    <option>TNPSC Group 2</option>
                                    <option>TNPSC Group 4</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Message</label>
                                <textarea x-model="form.message" rows="4" placeholder="Your question or message..."
                                          class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-transparent transition"></textarea>
                            </div>

                            <div x-show="success"
                                 class="p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 font-medium">
                                ✓ Thank you! We'll get back to you within 2 hours.
                            </div>

                            <button type="submit" :disabled="loading"
                                    class="w-full py-3.5 text-sm font-bold text-white rounded-xl transition-opacity hover:opacity-90 flex items-center justify-center gap-2"
                                    style="background-color:#1E3A5F;">
                                <span x-show="!loading">Send Message →</span>
                                <span x-show="loading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Sending…
                                </span>
                            </button>

                            <p class="text-xs text-gray-400 text-center">
                                Or WhatsApp us directly:
                                <a href="https://wa.me/919940528779" target="_blank" class="font-semibold text-green-600 hover:underline">+91 99405 28779</a>
                            </p>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('contactForm', () => ({
            form: { name: '', phone: '', email: '', course: '', message: '' },
            loading: false,
            success: false,
            submit() {
                if (!this.form.name || !this.form.phone) return;
                this.loading = true;
                // WhatsApp fallback — send enquiry via WhatsApp
                const msg = encodeURIComponent(
                    `Hi NVAAK Academy!\n\nName: ${this.form.name}\nPhone: ${this.form.phone}\nEmail: ${this.form.email || '—'}\nCourse: ${this.form.course || '—'}\nMessage: ${this.form.message || '—'}`
                );
                setTimeout(() => {
                    this.loading = false;
                    this.success = true;
                    window.open(`https://wa.me/919940528779?text=${msg}`, '_blank');
                    this.form = { name: '', phone: '', email: '', course: '', message: '' };
                    setTimeout(() => this.success = false, 6000);
                }, 600);
            }
        }));
    });
    </script>

@endsection
