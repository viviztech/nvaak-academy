@guest
<div x-data="socialProof()"
     x-show="showNotification"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-x-full opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-x-0 opacity-100"
     x-transition:leave-end="translate-x-full opacity-0"
     class="fixed bottom-20 right-4 z-50 max-w-xs w-full"
     style="display: none;">
    <div class="bg-white rounded-xl shadow-2xl border border-gray-100 p-4 flex items-start gap-3">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold"
                 style="background-color:#1E3A5F;">
                <span x-text="notification.initials || '👤'"></span>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm text-gray-900">
                <span class="font-semibold" x-text="notification.name"></span>
                <span class="text-gray-600" x-text="notification.message"></span>
            </p>
            <p class="text-xs text-gray-400 mt-0.5" x-text="notification.time"></p>
        </div>
        <button @click="showNotification = false" class="flex-shrink-0 text-gray-300 hover:text-gray-500 mt-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('socialProof', () => ({
        showNotification: false,
        notification: {},
        notifications: [
            { initials: 'PS', name: 'Priya S. from Chennai', message: ' just enrolled for NEET 2026 course', time: '2 minutes ago' },
            { initials: 'RK', name: 'Rajesh K. from Avadi',  message: ' booked a free demo class', time: '5 minutes ago' },
            { initials: 'AM', name: 'Anjali M. from Chennai', message: ' enrolled for TNPSC Group 1', time: '10 minutes ago' },
            { initials: 'KT', name: 'Karthik T. from Avadi', message: ' started NEET 2027 (2-Year) course', time: '18 minutes ago' },
            { initials: 'MS', name: 'Meera S. from Chennai',  message: ' registered for TNPSC Group 2', time: '25 minutes ago' },
            { initials: 'SI', name: 'Suresh I. from Avadi',   message: ' enrolled for NEET Repeater batch', time: '32 minutes ago' },
        ],

        init() {
            // Show first notification after 10 seconds
            setTimeout(() => this.showRandomNotification(), 10000);
            // Then rotate every 15–20 seconds
            setInterval(() => this.showRandomNotification(), 15000 + Math.random() * 5000);
        },

        showRandomNotification() {
            this.notification = this.notifications[Math.floor(Math.random() * this.notifications.length)];
            this.showNotification = true;
            setTimeout(() => { this.showNotification = false; }, 5000);
        }
    }));
});
</script>
@endguest
