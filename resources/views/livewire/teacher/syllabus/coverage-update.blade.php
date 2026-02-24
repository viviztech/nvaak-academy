<div>
    @if($saved)
        <div class="mb-5 flex items-start gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-4"
             x-data x-init="setTimeout(() => { $el.remove(); $wire.set('saved', false); }, 4000)">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-green-800">Syllabus coverage updated!</p>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Batch</label>
                <select wire:model.live="selectedBatch"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent bg-white">
                    <option value="">Select batch...</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch['id'] }}">{{ $batch['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Subject</label>
                <select wire:model.live="selectedSubject"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent bg-white
                               {{ !$selectedBatch ? 'opacity-50 cursor-not-allowed' : '' }}"
                        @if(!$selectedBatch) disabled @endif>
                    <option value="">{{ $selectedBatch ? 'Select subject...' : 'Select batch first' }}</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Chapters --}}
    @if(!$selectedBatch || !$selectedSubject)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center py-20 text-gray-400">
            <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <p class="text-sm">Select a batch and subject to view syllabus.</p>
        </div>
    @elseif(empty($chapters))
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center py-20 text-gray-400">
            <p class="text-sm">No chapters configured for this batch/subject.</p>
        </div>
    @else
        @php
            $coveredCount = collect($coverageData)->filter()->count();
            $totalCount   = count($chapters);
            $pct          = $totalCount > 0 ? round(($coveredCount / $totalCount) * 100) : 0;
        @endphp

        {{-- Progress Overview --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm font-semibold text-gray-700">Overall Coverage</p>
                <span class="text-sm font-bold {{ $pct >= 75 ? 'text-green-600' : ($pct >= 40 ? 'text-yellow-600' : 'text-red-500') }}">
                    {{ $coveredCount }}/{{ $totalCount }} topics ({{ $pct }}%)
                </span>
            </div>
            <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500
                    {{ $pct >= 75 ? 'bg-green-500' : ($pct >= 40 ? 'bg-yellow-400' : 'bg-red-500') }}"
                    style="width: {{ $pct }}%">
                </div>
            </div>
        </div>

        {{-- Chapters List --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Chapters &amp; Topics</h2>
            </div>

            <div class="divide-y divide-gray-50">
                @foreach($chapters as $chapter)
                    <div class="px-6 py-4 {{ isset($coverageData[$chapter['id']]) && $coverageData[$chapter['id']] ? 'bg-green-50/30' : '' }} transition-colors">
                        <div class="flex items-start gap-4">
                            <input type="checkbox"
                                   wire:model.live="coverageData.{{ $chapter['id'] }}"
                                   class="w-5 h-5 mt-0.5 rounded accent-green-500 cursor-pointer flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $chapter['order'] ?? ($loop->iteration) }}. {{ $chapter['title'] }}
                                    </p>
                                    @if(isset($coverageData[$chapter['id']]) && $coverageData[$chapter['id']])
                                        <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-0.5 rounded-full">Covered</span>
                                    @endif
                                </div>
                                @if(isset($chapter['description']) && $chapter['description'])
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $chapter['description'] }}</p>
                                @endif
                                <div class="mt-2">
                                    <input type="text"
                                           wire:model.defer="coverageNotes.{{ $chapter['id'] }}"
                                           placeholder="Add notes (optional)..."
                                           class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1e3a5f] placeholder-gray-400">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                <button wire:click="saveCoverage"
                        class="bg-[#1e3a5f] hover:bg-blue-900 text-white font-semibold text-sm py-2.5 px-6 rounded-xl transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Coverage
                </button>
            </div>
        </div>
    @endif
</div>
