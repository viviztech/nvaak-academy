<div>
    {{-- ── Evaluation Modal ─────────────────────────────────────────────────── --}}
    @if($showEvalForm && $selectedSubmission)
        <div class="fixed inset-0 z-50 flex items-start justify-center bg-black/50 overflow-y-auto py-8 px-4"
             x-data x-on:keydown.escape.window="$wire.closeEvaluation()">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl my-auto">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Evaluate Answer</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $selectedSubmission->student?->name }}
                            &middot; {{ $selectedSubmission->subject?->name }}
                        </p>
                    </div>
                    <button wire:click="closeEvaluation" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Question --}}
                <div class="px-6 py-3 bg-blue-50 border-b border-blue-100">
                    <p class="text-xs font-semibold text-blue-700 mb-1">Question</p>
                    <p class="text-sm text-gray-800">{{ $selectedSubmission->question_text }}</p>
                </div>

                <form wire:submit.prevent="saveEvaluation" class="p-6 space-y-5">

                    {{-- Score sliders --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Scoring</p>
                            <span class="text-sm font-bold
                                {{ $totalScore >= 75 ? 'text-green-600' : ($totalScore >= 50 ? 'text-yellow-500' : 'text-red-500') }}">
                                Total: {{ $totalScore }}/100
                            </span>
                        </div>

                        @php
                            $scoreFields = [
                                ['prop' => 'score_content',    'label' => 'Content & Accuracy',       'max' => 40],
                                ['prop' => 'score_language',   'label' => 'Language & Grammar',       'max' => 20],
                                ['prop' => 'score_structure',  'label' => 'Structure & Presentation', 'max' => 20],
                                ['prop' => 'score_analytical', 'label' => 'Analytical Thinking',      'max' => 20],
                            ];
                        @endphp

                        @foreach($scoreFields as $field)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                                    <span class="text-sm font-bold text-[#1e3a5f]">
                                        {{ $this->{$field['prop']} }}<span class="text-gray-400 font-normal">/{{ $field['max'] }}</span>
                                    </span>
                                </div>
                                <input type="range" wire:model.live="{{ $field['prop'] }}"
                                       min="0" max="{{ $field['max'] }}" step="1"
                                       class="w-full h-2 rounded-lg appearance-none cursor-pointer accent-orange-500">
                                @error($field['prop']) <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endforeach
                    </div>

                    {{-- General Feedback --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            General Feedback <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="general_feedback" rows="3"
                                  placeholder="Provide overall feedback..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent resize-none placeholder-gray-400"></textarea>
                        @error('general_feedback') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Strengths</label>
                            <textarea wire:model="strengths" rows="2"
                                      placeholder="What was done well..."
                                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent resize-none placeholder-gray-400"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Improvements Needed</label>
                            <textarea wire:model="improvements_needed" rows="2"
                                      placeholder="Areas to improve..."
                                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none placeholder-gray-400"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-1">
                        <button type="submit"
                                class="flex-1 bg-[#1e3a5f] hover:bg-blue-900 text-white font-semibold text-sm py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Evaluation
                        </button>
                        <button type="button" wire:click="closeEvaluation"
                                class="px-5 py-3 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ── Submissions List ─────────────────────────────────────────────────── --}}

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Search students..."
                       class="w-full border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent">
            </div>
            <div>
                <select wire:model.live="statusFilter"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent bg-white">
                    <option value="pending">Pending</option>
                    <option value="evaluated">Evaluated</option>
                    <option value="">All</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">IAS Submissions ({{ $submissions->total() }})</h2>
        </div>

        @if($submissions->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                <p class="text-sm">No submissions found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-5 py-3">Student</th>
                            <th class="text-left px-5 py-3">Subject</th>
                            <th class="text-left px-5 py-3">Submitted</th>
                            <th class="text-left px-5 py-3">Status</th>
                            <th class="text-center px-5 py-3">Score</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($submissions as $submission)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-900">{{ $submission->student?->name ?? '—' }}</p>
                                </td>
                                <td class="px-5 py-4 text-gray-600">{{ $submission->subject?->name ?? '—' }}</td>
                                <td class="px-5 py-4 text-gray-500 whitespace-nowrap">
                                    {{ $submission->submitted_at?->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $statusMap = [
                                            'pending'   => 'bg-yellow-100 text-yellow-700',
                                            'evaluated' => 'bg-green-100 text-green-700',
                                        ];
                                        $sc = $statusMap[$submission->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $sc }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($submission->evaluation)
                                        <span class="font-bold
                                            {{ $submission->evaluation->total_score >= 75 ? 'text-green-600' : ($submission->evaluation->total_score >= 50 ? 'text-yellow-500' : 'text-red-500') }}">
                                            {{ $submission->evaluation->total_score }}/100
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <button wire:click="openEvaluation({{ $submission->id }})"
                                            class="text-xs font-semibold
                                                {{ $submission->status === 'pending' ? 'text-white bg-orange-500 hover:bg-orange-600' : 'text-[#1e3a5f] bg-blue-50 hover:bg-blue-100' }}
                                                px-3 py-1.5 rounded-lg transition-colors">
                                        {{ $submission->status === 'pending' ? 'Evaluate' : 'Re-Evaluate' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($submissions->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $submissions->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
