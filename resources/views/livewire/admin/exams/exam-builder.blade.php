<div>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $examId ? 'Edit Exam' : 'Create New Exam' }}</h2>
            @if($examId)
                <p class="text-sm text-gray-500 mt-1">Exam ID: #{{ $examId }} &bull; {{ $exam?->code }}</p>
            @endif
        </div>
        <div class="flex space-x-3">
            <button wire:click="saveDraft"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Save Draft
            </button>
            <a href="{{ route('admin.exams.index') }}"
               class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Back to Exams
            </a>
        </div>
    </div>

    {{-- Tab Progress --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <div class="flex items-center">
            @foreach([
                [1, 'Basics', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                [2, 'Schedule', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                [3, 'Sections', 'M4 6h16M4 12h16M4 18h7'],
                [4, 'Questions', 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                [5, 'Settings', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
            ] as [$num, $label, $icon])
                <div class="flex items-center {{ $num < 5 ? 'flex-1' : '' }}">
                    <button wire:click="goToTab({{ $num }})"
                            class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-colors
                                   {{ $currentTab === $num ? 'bg-[#1e3a5f] text-white' : ($currentTab > $num ? 'bg-green-100 text-green-700 cursor-pointer' : 'bg-gray-100 text-gray-500 cursor-pointer') }}">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                    {{ $currentTab === $num ? 'bg-white text-[#1e3a5f]' : ($currentTab > $num ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600') }}">
                            @if($currentTab > $num)
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                {{ $num }}
                            @endif
                        </div>
                        <span class="text-xs font-medium hidden md:inline">{{ $label }}</span>
                    </button>
                    @if($num < 5)
                        <div class="flex-1 h-0.5 mx-2 {{ $currentTab > $num ? 'bg-green-400' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">

        {{-- TAB 1: Basics --}}
        @if($currentTab === 1)
            <h3 class="text-lg font-bold text-gray-800 mb-5">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Exam Name <span class="text-red-500">*</span></label>
                    <input wire:model="name" type="text"
                           class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                           placeholder="e.g. NEET Full Mock Test - January 2025">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Exam Code <span class="text-red-500">*</span></label>
                    <input wire:model="code" type="text"
                           class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                           placeholder="e.g. NEET_MOCK_JAN25">
                    @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Exam Type</label>
                    <select wire:model="exam_type" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                        <option value="mock_test">Mock Test</option>
                        <option value="chapter_test">Chapter Test</option>
                        <option value="full_length">Full Length</option>
                        <option value="practice">Practice Test</option>
                        <option value="sectional">Sectional Test</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Course Type</label>
                    <select wire:model="course_type" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                        <option value="neet">NEET</option>
                        <option value="ias">IAS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Batch (optional)</label>
                    <select wire:model="batch_id" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                        <option value="">All Batches</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->name }} ({{ strtoupper($batch->course_type) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Subject (optional)</label>
                    <select wire:model="subject_id" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Exam Series (optional)</label>
                    <select wire:model="exam_series_id" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                        <option value="">No Series</option>
                        @foreach($examSeries as $series)
                            <option value="{{ $series->id }}">{{ $series->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                    <textarea wire:model="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                              placeholder="Brief description of this exam..."></textarea>
                </div>
            </div>
        @endif

        {{-- TAB 2: Schedule --}}
        @if($currentTab === 2)
            <h3 class="text-lg font-bold text-gray-800 mb-5">Schedule & Scoring</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Start Time</label>
                    <input wire:model="start_time" type="datetime-local"
                           class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                    @error('start_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">End Time</label>
                    <input wire:model="end_time" type="datetime-local"
                           class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                    @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Duration (minutes) <span class="text-red-500">*</span></label>
                    <input wire:model="duration_minutes" type="number" min="1"
                           class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                    @error('duration_minutes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Max Attempts</label>
                    <input wire:model="max_attempts" type="number" min="1"
                           class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Total Marks <span class="text-red-500">*</span></label>
                    <input wire:model="total_marks" type="number" min="1"
                           class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                    @error('total_marks') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Passing Marks <span class="text-red-500">*</span></label>
                    <input wire:model="passing_marks" type="number" min="0"
                           class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                    @error('passing_marks') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        @endif

        {{-- TAB 3: Sections --}}
        @if($currentTab === 3)
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-800">Exam Sections</h3>
                <button wire:click="addSection"
                        class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-orange-600 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Section
                </button>
            </div>

            <div class="space-y-4">
                @foreach($sections as $i => $section)
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-semibold text-gray-700">Section {{ $i + 1 }}</span>
                            @if(count($sections) > 1)
                                <button wire:click="removeSection({{ $i }})"
                                        class="text-red-400 hover:text-red-600 text-xs">Remove</button>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            <div class="md:col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Section Name</label>
                                <input wire:model="sections.{{ $i }}.name" type="text"
                                       class="w-full border border-gray-300 rounded-lg text-sm py-1.5 px-3 focus:ring-2 focus:ring-orange-500 bg-white">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Total Questions</label>
                                <input wire:model="sections.{{ $i }}.total_questions" type="number" min="1"
                                       class="w-full border border-gray-300 rounded-lg text-sm py-1.5 px-3 focus:ring-2 focus:ring-orange-500 bg-white">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Marks/Correct</label>
                                <input wire:model="sections.{{ $i }}.marks_per_correct" type="number" step="0.5" min="0"
                                       class="w-full border border-gray-300 rounded-lg text-sm py-1.5 px-3 focus:ring-2 focus:ring-orange-500 bg-white">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Negative Marks</label>
                                <input wire:model="sections.{{ $i }}.negative_marks" type="number" step="0.25" min="0"
                                       class="w-full border border-gray-300 rounded-lg text-sm py-1.5 px-3 focus:ring-2 focus:ring-orange-500 bg-white">
                            </div>
                        </div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Min Questions to Attempt (0 = no min)</label>
                                <input wire:model="sections.{{ $i }}.min_questions_to_attempt" type="number" min="0"
                                       class="w-full border border-gray-300 rounded-lg text-sm py-1.5 px-3 focus:ring-2 focus:ring-orange-500 bg-white">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- TAB 4: Questions --}}
        @if($currentTab === 4)
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-gray-800">Add Questions</h3>
                <div class="text-sm text-gray-500">
                    {{ count($addedQuestionIds) }} question(s) added to this exam
                </div>
            </div>

            @if(!$examId)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-yellow-700">Please save the basics first before adding questions.</p>
                </div>
            @else
                {{-- Section Question Counts --}}
                @if(count($dbSections) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                        @foreach($dbSections as $section)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center">
                                <p class="text-xs font-medium text-gray-600">{{ $section->name }}</p>
                                <p class="text-lg font-bold text-[#1e3a5f]">{{ $sectionQuestionCounts[$section->id] ?? 0 }}</p>
                                <p class="text-xs text-gray-400">/ {{ $section->total_questions }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Browse Filters --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
                    <div class="md:col-span-2">
                        <input wire:model.live.debounce.400ms="qSearch" type="text" placeholder="Search questions..."
                               class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                    </div>
                    <select wire:model.live="qSubjectFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->id }}">{{ $subj->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="qDifficultyFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                        <option value="">All Levels</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                        <option value="very_hard">Very Hard</option>
                    </select>
                    <select wire:model.live="qTypeFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                        <option value="">All Types</option>
                        <option value="mcq_single">MCQ Single</option>
                        <option value="mcq_multiple">MCQ Multiple</option>
                        <option value="numerical">Numerical</option>
                    </select>
                </div>

                {{-- Questions List --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    @forelse($browseQuestions as $q)
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 hover:bg-gray-50 last:border-b-0">
                            <div class="flex-1 min-w-0 mr-4">
                                <p class="text-sm text-gray-800 truncate">{{ Str::limit(strip_tags($q->question_text), 80) }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="text-xs px-1.5 py-0.5 rounded {{ $q->type_color }}">{{ $q->type_label }}</span>
                                    <span class="text-xs px-1.5 py-0.5 rounded {{ $q->difficulty_color }}">{{ ucfirst($q->difficulty) }}</span>
                                    <span class="text-xs text-gray-400">{{ $q->subject?->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $q->marks }}M</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 flex-shrink-0">
                                @if(count($dbSections) > 0)
                                    <select class="border border-gray-300 rounded text-xs py-1 px-2" id="section_for_{{ $q->id }}">
                                        <option value="">No Section</option>
                                        @foreach($dbSections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                @endif

                                @if(in_array($q->id, $addedQuestionIds))
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded font-medium">Added</span>
                                    <button wire:click="removeQuestionFromExam({{ $q->id }})"
                                            class="px-2 py-1 text-xs text-red-600 hover:bg-red-50 rounded">Remove</button>
                                @else
                                    <button wire:click="addQuestionToExam({{ $q->id }})"
                                            class="px-3 py-1 text-xs bg-orange-500 text-white rounded hover:bg-orange-600 font-medium">Add</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <p class="text-sm">No questions match your filters. Adjust the search criteria.</p>
                        </div>
                    @endforelse
                </div>
            @endif
        @endif

        {{-- TAB 5: Settings --}}
        @if($currentTab === 5)
            <h3 class="text-lg font-bold text-gray-800 mb-5">Exam Settings</h3>
            <div class="space-y-4 max-w-2xl">
                @foreach([
                    ['negative_marking_enabled', 'Enable Negative Marking', 'Deduct marks for wrong answers'],
                    ['randomize_questions', 'Randomize Question Order', 'Questions appear in different order for each student'],
                    ['randomize_options', 'Randomize Answer Options', 'MCQ options shuffled per student'],
                    ['show_results_immediately', 'Show Results Immediately', 'Students see score right after submission'],
                    ['allow_review_after_submit', 'Allow Review After Submit', 'Students can review questions after submission'],
                    ['show_correct_answers', 'Show Correct Answers', 'Display correct answers in result view'],
                    ['prevent_tab_switch', 'Prevent Tab Switching', 'Warn or disqualify on tab switching'],
                ] as [$key, $label, $desc])
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $label }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $desc }}</p>
                        </div>
                        <button wire:click="$toggle('{{ $key }}')"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none
                                       {{ $$key ? 'bg-orange-500' : 'bg-gray-300' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform
                                         {{ $$key ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Tab Navigation Buttons --}}
        <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-200">
            @if($currentTab > 1)
                <button wire:click="prevTab"
                        class="inline-flex items-center px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Previous
                </button>
            @else
                <div></div>
            @endif

            @if($currentTab < 5)
                <button wire:click="nextTab"
                        class="inline-flex items-center px-5 py-2 text-sm font-medium text-white bg-[#1e3a5f] hover:bg-blue-900 rounded-lg">
                    Next
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @else
                <button wire:click="saveSettings"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save & Finish
                </button>
            @endif
        </div>
    </div>
</div>
