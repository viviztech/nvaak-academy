<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-red-800 border border-red-200">{{ session('error') }}</div>
    @endif

    {{-- Stats + Add button --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex gap-6">
            <div>
                <p class="text-sm text-gray-500">Total</p>
                <p class="text-2xl font-bold text-[#1E3A5F]">{{ $totalCount }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Active</p>
                <p class="text-2xl font-bold text-green-600">{{ $activeCount }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Pinned</p>
                <p class="text-2xl font-bold text-[#F97316]">{{ $pinnedCount }}</p>
            </div>
        </div>
        <button wire:click="openCreate"
            class="inline-flex items-center gap-2 rounded-lg bg-[#F97316] px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Announcement
        </button>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 p-4">
        <input wire:model.live.debounce.300ms="search" type="text"
            placeholder="Search announcements..."
            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
    </div>

    {{-- Announcements list --}}
    <div class="space-y-4">
        @forelse ($announcements as $ann)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            @if ($ann->is_pinned)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-[#F97316]">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.828 2.172a.5.5 0 01.707 0l7.293 7.293a.5.5 0 010 .707l-1.414 1.414a.5.5 0 01-.707 0l-.707-.707-3.18 3.18.707.707a.5.5 0 010 .707l-1.414 1.414a.5.5 0 01-.707 0L10 15.58l-.404.404a.5.5 0 01-.707 0L2.172 9.257a.5.5 0 010-.707L2.576 8.55a.5.5 0 01.707 0L3.69 9.06l3.18-3.18-.707-.707a.5.5 0 010-.707L7.577 3.05a.5.5 0 01.707 0l.707.707.837-.837z"/>
                                    </svg>
                                    Pinned
                                </span>
                            @endif
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $ann->target_audience === 'all' ? 'bg-blue-50 text-blue-700' :
                                   ($ann->target_audience === 'students' ? 'bg-purple-50 text-purple-700' :
                                   ($ann->target_audience === 'faculty' ? 'bg-green-50 text-green-700' : 'bg-orange-50 text-orange-700')) }}">
                                {{ ucfirst($ann->target_audience ?? 'All') }}
                            </span>
                            @if ($ann->published_at && $ann->published_at->isFuture())
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-yellow-50 text-yellow-700">
                                    Scheduled
                                </span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-900">{{ $ann->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $ann->body }}</p>
                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-400">
                            <span>By {{ $ann->createdBy?->name ?? 'System' }}</span>
                            <span>•</span>
                            <span>{{ $ann->published_at?->format('d M Y, h:i A') ?? 'Draft' }}</span>
                            @if ($ann->expires_at)
                                <span>•</span>
                                <span>Expires {{ $ann->expires_at->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button wire:click="togglePin({{ $ann->id }})"
                            title="{{ $ann->is_pinned ? 'Unpin' : 'Pin' }}"
                            class="p-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 text-{{ $ann->is_pinned ? '[#F97316]' : 'gray-400' }}">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.828 2.172a.5.5 0 01.707 0l7.293 7.293a.5.5 0 010 .707l-1.414 1.414a.5.5 0 01-.707 0l-.707-.707-3.18 3.18.707.707a.5.5 0 010 .707l-1.414 1.414a.5.5 0 01-.707 0L10 15.58l-.404.404a.5.5 0 01-.707 0L2.172 9.257a.5.5 0 010-.707L2.576 8.55a.5.5 0 01.707 0L3.69 9.06l3.18-3.18-.707-.707a.5.5 0 010-.707L7.577 3.05a.5.5 0 01.707 0l.707.707.837-.837z"/>
                            </svg>
                        </button>
                        <button wire:click="openEdit({{ $ann->id }})"
                            class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">Edit</button>
                        <button wire:click="delete({{ $ann->id }})"
                            wire:confirm="Delete this announcement?"
                            class="px-3 py-1.5 text-xs rounded-lg border border-red-200 text-red-600 hover:bg-red-50">Delete</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <p class="text-sm text-gray-400">No announcements yet. Create your first one.</p>
            </div>
        @endforelse
    </div>

    @if ($announcements->hasPages())
        <div class="mt-4">{{ $announcements->links() }}</div>
    @endif

    {{-- Create / Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 sticky top-0 bg-white">
                    <h3 class="font-semibold text-gray-900">{{ $editingId ? 'Edit Announcement' : 'New Announcement' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-5 space-y-5">
                    {{-- Title --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input wire:model="title" type="text"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]"
                            placeholder="Announcement title..." />
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Body --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                        <textarea wire:model="body" rows="4"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]"
                            placeholder="Write your announcement here..."></textarea>
                        @error('body') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Target audience --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Audience *</label>
                        <select wire:model.live="targetAudience"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]">
                            <option value="all">Everyone</option>
                            <option value="students">Students Only</option>
                            <option value="faculty">Faculty Only</option>
                            <option value="batch">Specific Batch(es)</option>
                        </select>
                    </div>

                    {{-- Batch selector --}}
                    @if ($targetAudience === 'batch')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Batches</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($batches as $batch)
                                    <label class="flex items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 cursor-pointer hover:bg-gray-50">
                                        <input type="checkbox" wire:model="targetBatchIds" value="{{ $batch->id }}"
                                            class="rounded border-gray-300 text-[#1E3A5F]" />
                                        <span class="text-sm text-gray-700">{{ $batch->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Scheduling + Options --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Publish At</label>
                            <input wire:model="publishedAt" type="datetime-local"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expires At</label>
                            <input wire:model="expiresAt" type="datetime-local"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
                        </div>
                    </div>

                    {{-- Pin option --}}
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="isPinned" class="rounded border-gray-300 text-[#F97316]" />
                        <span class="text-sm text-gray-700">Pin this announcement (shown at top)</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100 sticky bottom-0 bg-white">
                    <button wire:click="$set('showModal', false)"
                        class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button wire:click="save"
                        class="px-4 py-2 text-sm font-semibold text-white bg-[#1E3A5F] rounded-lg hover:bg-[#162d4a]">
                        {{ $editingId ? 'Update' : 'Publish' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
