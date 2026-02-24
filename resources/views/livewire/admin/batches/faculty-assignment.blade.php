<div>
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
            <button @click="show = false" class="text-green-400 hover:text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Faculty Assignment</h2>
            <p class="text-sm text-gray-500 mt-1">Assign faculty members to batches and subjects</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Assignment Form --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-4">New Assignment</h3>
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Batch <span class="text-red-500">*</span></label>
                    <select wire:model.live="batch_id"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('batch_id') border-red-500 @enderror">
                        <option value="">Choose a Batch</option>
                        @foreach($this->batches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }} ({{ strtoupper($b->course_type) }})</option>
                        @endforeach
                    </select>
                    @error('batch_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Faculty <span class="text-red-500">*</span></label>
                    <select wire:model="faculty_id"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('faculty_id') border-red-500 @enderror">
                        <option value="">Choose a Faculty Member</option>
                        @foreach($this->facultyList as $f)
                            <option value="{{ $f->id }}">{{ $f->user->name }} — {{ $f->designation ?? 'Faculty' }}</option>
                        @endforeach
                    </select>
                    @error('faculty_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Subject <span class="text-red-500">*</span></label>
                    <select wire:model="subject_id"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none @error('subject_id') border-red-500 @enderror"
                            {{ !$batch_id ? 'disabled' : '' }}>
                        <option value="">{{ $batch_id ? 'Choose a Subject' : 'Select a batch first' }}</option>
                        @foreach($this->subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    @error('subject_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-2">
                    <input wire:model="is_primary" type="checkbox" id="primary_chk"
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded">
                    <label for="primary_chk" class="text-sm text-gray-700">Mark as Primary Faculty for this subject</label>
                </div>

                <button type="submit"
                        class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <span wire:loading.remove wire:target="save">Assign Faculty</span>
                    <span wire:loading wire:target="save">Assigning...</span>
                </button>
            </form>
        </div>

        {{-- Current Assignments --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-800">
                    Current Assignments
                    @if($batch_id)
                        <span class="text-sm font-normal text-gray-500">— {{ $this->batch->name ?? '' }}</span>
                    @endif
                </h3>
            </div>
            @if(!$batch_id)
                <div class="p-8 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm">Select a batch to view assignments</p>
                </div>
            @elseif($this->existingAssignments->isEmpty())
                <div class="p-8 text-center text-gray-400">
                    <p class="text-sm">No faculty assigned to this batch yet.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($this->existingAssignments as $assignment)
                        <div class="flex items-center justify-between px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-xs font-semibold text-indigo-700">{{ strtoupper(substr($assignment->faculty->user->name ?? 'F', 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $assignment->faculty->user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $assignment->subject->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($assignment->is_primary)
                                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs rounded">Primary</span>
                                @endif
                                <button wire:click="removeAssignment({{ $assignment->id }})"
                                        wire:confirm="Remove this assignment?"
                                        class="text-red-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
