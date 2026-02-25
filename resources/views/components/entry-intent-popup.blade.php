@guest
<div x-data="entryIntentPopup()"
     x-show="showPopup"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
     style="display:none;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full relative" @click.away="closePopup">
        <button @click="closePopup" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="p-8">
            <div class="text-center mb-6">
                <div class="text-5xl mb-3">🎓</div>
                <h3 class="text-xl font-extrabold mb-2" style="color:#218091;">Welcome to NVAAK Academy!</h3>
                <p class="text-sm text-gray-600 mb-3">Start your journey to success with our expert NEET & TNPSC coaching.</p>
                <div class="rounded-xl p-3 text-sm" style="background:#FFF7ED;">
                    <strong>Special Offer:</strong> Book a free demo class and get <span class="font-bold" style="color:#F97316;">10% off</span> on enrollment!
                </div>
            </div>
            <div class="space-y-3">
                <input type="text" x-model="formData.name" placeholder="Your Name"
                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200">
                <input type="email" x-model="formData.email" placeholder="Email"
                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200">
                <input type="tel" x-model="formData.phone" placeholder="Phone Number"
                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200">
                <button @click="submitForm"
                        class="w-full py-3 text-sm font-bold text-white rounded-xl transition-opacity hover:opacity-90"
                        style="background-color:#218091;">
                    Get Started →
                </button>
                <p class="text-xs text-center text-gray-400">By submitting, you agree to receive communications from us.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('entryIntentPopup', () => ({
        showPopup: false,
        formData: { name: '', email: '', phone: '' },
        init() {
            if (localStorage.getItem('entryIntentShown') === 'true') return;
            setTimeout(() => { this.showPopup = true; }, 5000);
        },
        closePopup() {
            this.showPopup = false;
            localStorage.setItem('entryIntentShown', 'true');
        },
        submitForm() {
            if (!this.formData.name || !this.formData.phone) { alert('Please fill name and phone'); return; }
            const msg = encodeURIComponent(`Hi NVAAK Academy! I'd like to book a free demo.\nName: ${this.formData.name}\nPhone: ${this.formData.phone}\nEmail: ${this.formData.email || '—'}`);
            window.open(`https://wa.me/919940528779?text=${msg}`, '_blank');
            this.closePopup();
        }
    }));
});
</script>
@endguest
