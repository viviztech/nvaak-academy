<div>
    @if(!$submission)
        <div class="flex items-center justify-center h-64 text-gray-400">
            <p>Submission not found.</p>
        </div>
    @else
    <div class="mb-5">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.ias.submissions') }}" class="hover:text-[#1e3a5f]">IAS Submissions</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-800 font-medium">Evaluate — {{ $submission->student?->name }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- ── Left: Answer Sheet Viewer ─────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-base font-semibold text-gray-800">Answer Sheet</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $submission->student?->name }} &middot;
                        {{ $submission->subject?->name }} &middot;
                        Submitted {{ $submission->submitted_at?->format('d M Y, h:i A') }}
                    </p>
                </div>
                <a href="{{ route('admin.ias.submissions') }}" class="text-xs text-gray-400 hover:text-gray-600 transition-colors">
                    &larr; Back
                </a>
            </div>

            {{-- Question --}}
            <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
                <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Question</p>
                <p class="text-sm text-gray-800 leading-relaxed">{{ $submission->question_text }}</p>
            </div>

            {{-- File viewer --}}
            <div class="p-6">
                @if($submission->file_path)
                    @php
                        $ext = strtolower(pathinfo($submission->file_name ?? '', PATHINFO_EXTENSION));
                    @endphp

                    @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ route('admin.ias.file', $submission->id) }}"
                             alt="Answer Sheet"
                             class="w-full rounded-xl border border-gray-200 shadow-sm">
                    @elseif($ext === 'pdf')
                        <div class="flex flex-col items-center justify-center py-12 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <svg class="w-12 h-12 mb-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm font-medium">{{ $submission->file_name }}</p>
                            <a href="{{ route('admin.ias.download', $submission->id) }}"
                               class="mt-3 text-sm font-semibold text-white bg-[#1e3a5f] hover:bg-blue-800 px-4 py-2 rounded-lg transition-colors inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download PDF
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-8">Preview not available. <a href="#" class="text-blue-600 underline">Download file</a></p>
                    @endif
                @else
                    <p class="text-sm text-gray-400 text-center py-8">No file uploaded.</p>
                @endif
            </div>
        </div>

        {{-- ── Right: Scoring Form ────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-800">Evaluation</h2>
                @if($evaluation)
                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2.5 py-1 rounded-full">
                        Already evaluated &middot; {{ $evaluation->grade }}
                    </span>
                @endif
            </div>

            <form wire:submit.prevent="saveEvaluation" class="p-6 space-y-6">

                {{-- Score sliders --}}
                <div class="space-y-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Scoring Criteria (Total: {{ $totalScore }}/100)</p>

                    {{-- Content --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-sm font-medium text-gray-700">Content &amp; Accuracy</label>
                            <span class="text-sm font-bold text-[#1e3a5f]">{{ $score_content }}<span class="text-gray-400 font-normal">/40</span></span>
                        </div>
                        <input type="range" wire:model.live="score_content"
                               min="0" max="40" step="1"
                               class="w-full h-2 rounded-lg appearance-none cursor-pointer accent-orange-500">
                        @error('score_content') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Language --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-sm font-medium text-gray-700">Language &amp; Grammar</label>
                            <span class="text-sm font-bold text-[#1e3a5f]">{{ $score_language }}<span class="text-gray-400 font-normal">/20</span></span>
                        </div>
                        <input type="range" wire:model.live="score_language"
                               min="0" max="20" step="1"
                               class="w-full h-2 rounded-lg appearance-none cursor-pointer accent-orange-500">
                        @error('score_language') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Structure --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-sm font-medium text-gray-700">Structure &amp; Presentation</label>
                            <span class="text-sm font-bold text-[#1e3a5f]">{{ $score_structure }}<span class="text-gray-400 font-normal">/20</span></span>
                        </div>
                        <input type="range" wire:model.live="score_structure"
                               min="0" max="20" step="1"
                               class="w-full h-2 rounded-lg appearance-none cursor-pointer accent-orange-500">
                        @error('score_structure') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Analytical --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-sm font-medium text-gray-700">Analytical Thinking</label>
                            <span class="text-sm font-bold text-[#1e3a5f]">{{ $score_analytical }}<span class="text-gray-400 font-normal">/20</span></span>
                        </div>
                        <input type="range" wire:model.live="score_analytical"
                               min="0" max="20" step="1"
                               class="w-full h-2 rounded-lg appearance-none cursor-pointer accent-orange-500">
                        @error('score_analytical') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Total Score Bar --}}
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">Total Score</span>
                            <span class="text-xl font-bold
                                {{ $totalScore >= 75 ? 'text-green-600' : ($totalScore >= 50 ? 'text-yellow-500' : 'text-red-500') }}">
                                {{ $totalScore }}/100
                            </span>
                        </div>
                        <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-300
                                {{ $totalScore >= 75 ? 'bg-green-500' : ($totalScore >= 50 ? 'bg-yellow-400' : 'bg-red-500') }}"
                                style="width: {{ $totalScore }}%">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- General Feedback --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        General Feedback <span class="text-red-500">*</span>
                    </label>
                    <textarea wire:model="general_feedback" rows="4"
                              placeholder="Write overall feedback about the answer..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent resize-none placeholder-gray-400"></textarea>
                    @error('general_feedback') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Strengths --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Strengths</label>
                    <textarea wire:model="strengths" rows="2"
                              placeholder="What did the student do well?"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent resize-none placeholder-gray-400"></textarea>
                </div>

                {{-- Areas for Improvement --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Areas for Improvement</label>
                    <textarea wire:model="improvements_needed" rows="2"
                              placeholder="What can be improved?"
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none placeholder-gray-400"></textarea>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 bg-[#1e3a5f] hover:bg-blue-900 text-white font-semibold text-sm py-3 px-6 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Evaluation
                    </button>
                    <a href="{{ route('admin.ias.submissions') }}"
                       class="px-4 py-3 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
