<div x-data="stickyFooterCTA()"
     x-show="showCTA"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-y-full"
     x-transition:enter-end="translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="translate-y-0"
     x-transition:leave-end="translate-y-full"
     class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 shadow-lg"
     style="display: none;">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <p class="text-sm font-semibold" style="color:#218091;">Ready to start your journey?</p>
                <p class="text-xs text-gray-500">Book a free demo class today — no commitment required.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="tel:+919940528779"
                   class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Call Now
                </a>
                <a href="{{ route('admission.apply') }}"
                   class="px-6 py-2 text-sm font-bold text-white rounded-lg transition-opacity hover:opacity-90"
                   style="background-color:#F97316;">
                    Book Demo Class
                </a>
                <button @click="closeCTA" class="text-gray-400 hover:text-gray-600 p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('stickyFooterCTA', () => ({
        showCTA: false,
        lastScrollY: 0,

        init() {
            if (localStorage.getItem('stickyFooterDismissed') === 'true') return;

            // Show after scrolling 300px down
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        const currentScrollY = window.scrollY;
                        if (currentScrollY > 300) {
                            if (window.innerWidth < 768) {
                                // On mobile: hide when scrolling down, show when scrolling up
                                this.showCTA = currentScrollY < this.lastScrollY;
                            } else {
                                this.showCTA = true;
                            }
                        } else {
                            this.showCTA = false;
                        }
                        this.lastScrollY = currentScrollY;
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        },

        closeCTA() {
            this.showCTA = false;
            localStorage.setItem('stickyFooterDismissed', 'true');
        }
    }));
});
</script>
