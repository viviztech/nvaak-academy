<div x-data="exitIntentPopup()"
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
                <div class="text-5xl mb-3">🎯</div>
                <h3 class="text-xl font-extrabold mb-2" style="color:#1E3A5F;">Wait! Don't Miss Out</h3>
                <p class="text-sm text-gray-600">
                    Get <span class="font-bold" style="color:#F97316;">10% off</span> on your first enrollment when you book a demo class today!
                </p>
            </div>
            <div class="space-y-3">
                <input type="text" x-model="formData.name" placeholder="Your Name"
                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200">
                <input type="email" x-model="formData.email" placeholder="Email"
                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200">
                <input type="tel" x-model="formData.phone" placeholder="Phone Number"
                       class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200">
                <select x-model="formData.course"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="">Select Course Interest</option>
                    <option>NEET 2026 (1-Year)</option>
                    <option>NEET 2027 (2-Year)</option>
                    <option>NEET Repeater</option>
                    <option>TNPSC Group 1</option>
                    <option>TNPSC Group 2</option>
                    <option>TNPSC Group 4</option>
                </select>
                <button @click="submitForm"
                        class="w-full py-3 text-sm font-bold text-white rounded-xl transition-opacity hover:opacity-90"
                        style="background-color:#F97316;">
                    Claim 10% Off →
                </button>
                <p class="text-xs text-center text-gray-400">Limited time offer. Valid for first-time enrollments only.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('exitIntentPopup', () => ({
        showPopup: false,
        formData: { name: '', email: '', phone: '', course: '' },
        init() {
            if (localStorage.getItem('exitIntentShown') === 'true') return;
            let triggered = false;
            document.addEventListener('mouseleave', (e) => {
                if (e.clientY <= 0 && !triggered && !this.showPopup) {
                    triggered = true;
                    this.showPopup = true;
                }
            });
        },
        closePopup() {
            this.showPopup = false;
            localStorage.setItem('exitIntentShown', 'true');
        },
        submitForm() {
            if (!this.formData.name || !this.formData.phone) { alert('Please fill name and phone'); return; }
            const msg = encodeURIComponent(`Hi! I want 10% off.\nName: ${this.formData.name}\nPhone: ${this.formData.phone}\nCourse: ${this.formData.course || 'Not selected'}`);
            window.open(`https://wa.me/919940528779?text=${msg}`, '_blank');
            this.closePopup();
        }
    }));
});
</script>
