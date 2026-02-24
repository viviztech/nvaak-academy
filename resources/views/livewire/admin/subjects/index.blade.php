<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-green-800 border border-green-200">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Subjects list --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Search subjects..."
                    class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F] mr-3" />
                <button wire:click="openCreate"
                    class="inline-flex items-center gap-1 rounded-lg bg-[#F97316] px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Subject
                </button>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                @forelse ($subjects as $subject)
                    <div wire:click="selectSubject({{ $subject->id }})"
                        class="flex items-center justify-between px-5 py-4 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors
                            {{ $selectedSubject?->id === $subject->id ? 'bg-blue-50 border-l-4 border-l-[#1E3A5F]' : '' }}">
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $subject->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                @if ($subject->code)
                                    <span class="text-xs text-gray-400 font-mono">{{ $subject->code }}</span>
                                @endif
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $subject->target_exam === 'neet' ? 'bg-purple-50 text-purple-700' : ($subject->target_exam === 'ias' ? 'bg-orange-50 text-orange-700' : 'bg-blue-50 text-blue-700') }}">
                                    {{ strtoupper($subject->target_exam ?? 'NEET') }}
                                </span>
                                <span class="text-xs text-gray-400">{{ $subject->chapters_count }} chapters</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click.stop="openEdit({{ $subject->id }})"
                                class="text-xs px-2 py-1 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">Edit</button>
                            <button wire:click.stop="delete({{ $subject->id }})"
                                wire:confirm="Delete this subject and all its chapters?"
                                class="text-xs px-2 py-1 rounded border border-red-300 text-red-600 hover:bg-red-50">Delete</button>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-400 text-sm">No subjects yet. Add your first subject.</div>
                @endforelse
            </div>
        </div>

        {{-- Right: Chapters panel --}}
        <div>
            @if ($selectedSubject)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">{{ $selectedSubject->name }} — Chapters</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        @forelse ($selectedSubject->chapters->sortBy('chapter_order') as $chapter)
                            <div class="flex items-center justify-between rounded-lg border border-gray-100 px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono text-gray-400 w-5">{{ $chapter->chapter_order }}</span>
                                    <span class="text-sm text-gray-800">{{ $chapter->name }}</span>
                                </div>
                                <button wire:click="deleteChapter({{ $chapter->id }})"
                                    wire:confirm="Delete this chapter?"
                                    class="text-gray-300 hover:text-red-500 transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-4">No chapters yet</p>
                        @endforelse
                    </div>
                    <div class="px-4 pb-4 border-t border-gray-100 pt-4">
                        <p class="text-xs font-semibold text-gray-600 mb-2">Add Chapter</p>
                        <div class="flex gap-2">
                            <input wire:model="chapterName" type="text" placeholder="Chapter name..."
                                class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]"
                                wire:keydown.enter="addChapter" />
                            <button wire:click="addChapter"
                                class="px-3 py-2 rounded-lg bg-[#1E3A5F] text-white text-sm hover:bg-[#162d4a]">Add</button>
                        </div>
                        @error('chapterName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            @else
                <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-8 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-sm text-gray-400">Click a subject to manage its chapters</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Subject modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">{{ $editingId ? 'Edit Subject' : 'Add Subject' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject Name *</label>
                        <input wire:model="name" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]" />
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject Code</label>
                        <input wire:model="code" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="e.g. BIO, PHY" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target Exam *</label>
                        <select wire:model="targetExam" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E3A5F]">
                            <option value="neet">NEET</option>
                            <option value="ias">IAS</option>
                            <option value="both">Both</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="description" rows="2" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button wire:click="save" class="px-4 py-2 text-sm font-semibold text-white bg-[#1E3A5F] rounded-lg hover:bg-[#162d4a]">
                        {{ $editingId ? 'Update' : 'Create' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
