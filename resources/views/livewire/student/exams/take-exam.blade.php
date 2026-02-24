<div x-data="{
        remainingSeconds: {{ $totalSeconds }},
        timerInterval: null,
        startTimer(seconds) {
            this.remainingSeconds = seconds;
            this.timerInterval = setInterval(() => {
                if (this.remainingSeconds > 0) {
                    this.remainingSeconds--;
                } else {
                    clearInterval(this.timerInterval);
                    $wire.submitExam();
                }
            }, 1000);
        },
        get timerFormatted() {
            const h = Math.floor(this.remainingSeconds / 3600);
            const m = Math.floor((this.remainingSeconds % 3600) / 60);
            const s = this.remainingSeconds % 60;
            return (h > 0 ? String(h).padStart(2, '0') + ':' : '') + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        },
        get timerColor() {
            if (this.remainingSeconds <= 300) return 'text-red-500';
            if (this.remainingSeconds <= 600) return 'text-orange-500';
            return 'text-green-600';
        }
    }"
    @exam-started.window="startTimer($event.detail.remainingSeconds)"
    wire:poll.30s="autoSave">

    {{-- PRE-START SCREEN --}}
    @if($examStatus === 'not_started')
        <div class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
            <div class="bg-white rounded-2xl shadow-lg max-w-lg w-full p-8">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $exam->name }}</h2>
                    <p class="text-gray-500 text-sm mt-2">{{ $exam->code }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500">Duration</p>
                        <p class="text-xl font-bold text-gray-800 mt-1">{{ $exam->duration_minutes }} min</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500">Total Marks</p>
                        <p class="text-xl font-bold text-gray-800 mt-1">{{ $exam->total_marks }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500">Questions</p>
                        <p class="text-xl font-bold text-gray-800 mt-1">{{ count($questions) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-gray-500">Passing Marks</p>
                        <p class="text-xl font-bold text-gray-800 mt-1">{{ $exam->passing_marks }}</p>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                    <h4 class="text-sm font-semibold text-yellow-800 mb-2">Instructions</h4>
                    <ul class="text-xs text-yellow-700 space-y-1 list-disc list-inside">
                        <li>Each correct answer carries {{ $exam->total_marks / max(count($questions), 1) }} marks</li>
                        @if($exam->negative_marking_enabled)
                            <li>Wrong answers carry negative marks</li>
                        @endif
                        @if($exam->prevent_tab_switch)
                            <li>Do not switch tabs during the exam</li>
                        @endif
                        <li>Timer will auto-submit when time is up</li>
                        <li>You can mark questions for review and return to them</li>
                    </ul>
                </div>

                <button wire:click="startExam"
                        class="w-full py-3 text-base font-semibold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-colors">
                    <span wire:loading.remove wire:target="startExam">Start Exam</span>
                    <span wire:loading wire:target="startExam">Starting...</span>
                </button>

                <a href="{{ route('student.exams.index') }}"
                   class="block mt-3 py-2 text-center text-sm text-gray-500 hover:text-gray-700">
                    Go Back
                </a>
            </div>
        </div>

    {{-- EXAM SUBMITTED --}}
    @elseif($submitted || $examStatus === 'submitted')
        <div class="min-h-screen bg-gray-50 flex items-center justify-center p-6">
            <div class="bg-white rounded-2xl shadow-lg max-w-md w-full p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Exam Submitted!</h2>
                <p class="text-gray-500 mt-2 text-sm">Your answers have been recorded. Results will be available shortly.</p>
                <a href="{{ route('student.exams.index') }}"
                   class="block mt-6 py-3 text-sm font-medium text-white bg-[#1e3a5f] hover:bg-blue-900 rounded-xl">
                    Go to My Exams
                </a>
            </div>
        </div>

    {{-- EXAM IN PROGRESS --}}
    @else
        <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">

            {{-- Top Header Bar --}}
            <div class="bg-[#1e3a5f] text-white px-4 py-2 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center space-x-4">
                    <div>
                        <p class="text-sm font-semibold">{{ $exam->name }}</p>
                        <p class="text-xs text-blue-200">Question {{ $currentQuestion + 1 }} of {{ count($questions) }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    {{-- Progress --}}
                    <div class="hidden md:flex items-center space-x-3 text-xs text-blue-200">
                        <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-sm bg-green-400 mr-1"></span> {{ $answeredCount }} Answered</span>
                        <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-sm bg-yellow-400 mr-1"></span> {{ $reviewCount }} Review</span>
                        <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-sm bg-gray-400 mr-1"></span> {{ count($questions) - $answeredCount - $reviewCount }} Not Visited</span>
                    </div>

                    {{-- Timer --}}
                    <div :class="timerColor" class="text-xl font-bold font-mono tabular-nums" x-text="timerFormatted">
                        --:--
                    </div>

                    {{-- Submit Button --}}
                    <button wire:click="confirmSubmit"
                            class="px-4 py-1.5 text-sm font-medium bg-orange-500 hover:bg-orange-400 rounded-lg">
                        Submit
                    </button>
                </div>
            </div>

            {{-- Main Content Area --}}
            <div class="flex flex-1 overflow-hidden">

                {{-- LEFT: Question Panel --}}
                <div class="flex-1 overflow-y-auto p-5">

                    @if($currentQ && $question)
                        {{-- Section Info --}}
                        @if(count($exam->sections) > 0)
                            @php $section = $exam->sections->firstWhere('id', $currentQ['section_id']); @endphp
                            @if($section)
                                <div class="mb-3">
                                    <span class="text-xs font-medium text-[#1e3a5f] bg-blue-100 px-2.5 py-1 rounded-full">
                                        {{ $section->name }}
                                    </span>
                                </div>
                            @endif
                        @endif

                        {{-- Question --}}
                        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                            <div class="flex items-start justify-between mb-4">
                                <span class="text-sm font-bold text-[#1e3a5f] bg-blue-50 px-3 py-1 rounded-full">
                                    Q{{ $currentQuestion + 1 }}
                                </span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs px-2 py-0.5 rounded {{ $question->type_color }}">
                                        {{ $question->type_label }}
                                    </span>
                                    <span class="text-xs px-2 py-0.5 rounded {{ $question->difficulty_color }}">
                                        {{ ucfirst($question->difficulty) }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $currentQ['marks'] ?? $question->marks }} marks</span>
                                </div>
                            </div>

                            <div class="text-gray-800 leading-relaxed mb-6">
                                {!! $question->question_text !!}
                            </div>

                            @if($question->question_image)
                                <img src="{{ Storage::url($question->question_image) }}" alt="Question image" class="max-w-sm mb-4 rounded-lg">
                            @endif

                            {{-- MCQ SINGLE --}}
                            @if($question->question_type === 'mcq_single')
                                <div class="space-y-3">
                                    @foreach($question->options as $key => $optionText)
                                        @php $isSelected = in_array($key, $answers[$question->id] ?? []); @endphp
                                        <label class="flex items-start space-x-3 p-3 rounded-xl border-2 cursor-pointer transition-all
                                                       {{ $isSelected ? 'border-orange-400 bg-orange-50' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                                            <input type="radio"
                                                   wire:change="saveAnswer({{ $question->id }}, '{{ $key }}')"
                                                   name="answer_{{ $question->id }}"
                                                   value="{{ $key }}"
                                                   @checked($isSelected)
                                                   class="mt-0.5 w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                            <div class="flex items-start space-x-2 flex-1">
                                                <span class="font-bold text-sm {{ $isSelected ? 'text-orange-600' : 'text-gray-500' }} flex-shrink-0">{{ $key }}.</span>
                                                <span class="text-sm text-gray-800">{{ $optionText }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            {{-- MCQ MULTIPLE --}}
                            @if($question->question_type === 'mcq_multiple')
                                <p class="text-xs text-blue-600 font-medium mb-3">Select all correct answers:</p>
                                <div class="space-y-3">
                                    @foreach($question->options as $key => $optionText)
                                        @php $isSelected = in_array($key, $answers[$question->id] ?? []); @endphp
                                        <label class="flex items-start space-x-3 p-3 rounded-xl border-2 cursor-pointer transition-all
                                                       {{ $isSelected ? 'border-orange-400 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                            <input type="checkbox"
                                                   wire:change="saveAnswer({{ $question->id }}, @js(array_merge(array_filter($answers[$question->id] ?? [], fn($v) => $v !== $key), $isSelected ? [] : [$key])))"
                                                   @checked($isSelected)
                                                   class="mt-0.5 w-4 h-4 text-orange-500 rounded focus:ring-orange-500">
                                            <div class="flex items-start space-x-2 flex-1">
                                                <span class="font-bold text-sm {{ $isSelected ? 'text-orange-600' : 'text-gray-500' }} flex-shrink-0">{{ $key }}.</span>
                                                <span class="text-sm text-gray-800">{{ $optionText }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            {{-- NUMERICAL --}}
                            @if($question->question_type === 'numerical')
                                <div class="max-w-xs">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Answer:</label>
                                    <input type="number" step="any"
                                           value="{{ $answers[$question->id][0] ?? '' }}"
                                           wire:change="saveAnswer({{ $question->id }}, [$event.target.value])"
                                           class="w-full border-2 border-gray-300 rounded-xl text-lg py-3 px-4 focus:ring-2 focus:ring-orange-500 focus:border-orange-400"
                                           placeholder="Enter numerical answer">
                                    @if($question->answer_range_from !== null && $question->answer_range_to !== null)
                                        <p class="text-xs text-gray-500 mt-2">Acceptable range: {{ $question->answer_range_from }} to {{ $question->answer_range_to }}</p>
                                    @endif
                                </div>
                            @endif

                            {{-- TRUE/FALSE --}}
                            @if($question->question_type === 'true_false')
                                <div class="flex space-x-4">
                                    @foreach(['true' => 'True', 'false' => 'False'] as $val => $label)
                                        @php $isSelected = in_array($val, $answers[$question->id] ?? []); @endphp
                                        <label class="flex-1 flex items-center justify-center p-4 rounded-xl border-2 cursor-pointer transition-all
                                                       {{ $isSelected ? 'border-orange-400 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                            <input type="radio" wire:change="saveAnswer({{ $question->id }}, '{{ $val }}')"
                                                   name="answer_{{ $question->id }}" value="{{ $val }}"
                                                   @checked($isSelected) class="sr-only">
                                            <span class="font-bold text-lg {{ $isSelected ? 'text-orange-600' : 'text-gray-600' }}">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            {{-- ASSERTION REASON --}}
                            @if($question->question_type === 'assertion_reason')
                                <div class="space-y-3 mb-5">
                                    @if(isset($question->options['assertion']))
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                            <p class="text-xs font-bold text-blue-700 mb-1">Assertion (A):</p>
                                            <p class="text-sm text-gray-800">{{ $question->options['assertion'] }}</p>
                                        </div>
                                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                            <p class="text-xs font-bold text-purple-700 mb-1">Reason (R):</p>
                                            <p class="text-sm text-gray-800">{{ $question->options['reason'] }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-2">
                                    @foreach(['A' => 'Both Assertion and Reason are true and Reason is the correct explanation', 'B' => 'Both Assertion and Reason are true but Reason is NOT the correct explanation', 'C' => 'Assertion is true but Reason is false', 'D' => 'Assertion is false but Reason is true'] as $key => $desc)
                                        @php $isSelected = in_array($key, $answers[$question->id] ?? []); @endphp
                                        <label class="flex items-start space-x-3 p-3 rounded-xl border-2 cursor-pointer {{ $isSelected ? 'border-orange-400 bg-orange-50' : 'border-gray-200 hover:border-gray-300' }}">
                                            <input type="radio" wire:change="saveAnswer({{ $question->id }}, '{{ $key }}')"
                                                   name="answer_{{ $question->id }}" value="{{ $key }}"
                                                   @checked($isSelected) class="mt-0.5 w-4 h-4 text-orange-500">
                                            <span class="text-sm text-gray-700"><strong>{{ $key }}.</strong> {{ $desc }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Bottom Action Bar --}}
                        <div class="flex items-center justify-between bg-white rounded-xl border border-gray-200 px-4 py-3">
                            <div class="flex space-x-2">
                                <button wire:click="clearAnswer({{ $question->id }})"
                                        class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">
                                    Clear
                                </button>
                                <button wire:click="toggleMarkForReview({{ $question->id }})"
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
                                               {{ ($markedForReview[$question->id] ?? false) ? 'text-yellow-700 bg-yellow-100 hover:bg-yellow-200' : 'text-gray-600 bg-gray-100 hover:bg-gray-200' }}">
                                    {{ ($markedForReview[$question->id] ?? false) ? 'Unmark Review' : 'Mark for Review' }}
                                </button>
                            </div>
                            <div class="flex space-x-2">
                                <button wire:click="prevQuestion" @disabled($currentQuestion === 0)
                                        class="px-4 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">
                                    Previous
                                </button>
                                <button wire:click="nextQuestion" @disabled($currentQuestion === count($questions) - 1)
                                        class="px-4 py-1.5 text-xs font-medium text-white bg-[#1e3a5f] hover:bg-blue-900 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed">
                                    Next
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <p>No question loaded. Please go back.</p>
                        </div>
                    @endif
                </div>

                {{-- RIGHT: Navigator Panel --}}
                <div class="w-64 bg-white border-l border-gray-200 flex-shrink-0 overflow-y-auto">
                    <div class="p-4">
                        <h4 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Question Navigator</h4>

                        {{-- Legend --}}
                        <div class="grid grid-cols-2 gap-1 mb-4 text-xs">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-4 h-4 rounded bg-green-400 flex-shrink-0"></span>
                                <span class="text-gray-500">Answered</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <span class="w-4 h-4 rounded bg-yellow-400 flex-shrink-0"></span>
                                <span class="text-gray-500">Review</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <span class="w-4 h-4 rounded bg-blue-500 flex-shrink-0"></span>
                                <span class="text-gray-500">Current</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <span class="w-4 h-4 rounded bg-gray-200 flex-shrink-0"></span>
                                <span class="text-gray-500">Not Visited</span>
                            </div>
                        </div>

                        {{-- Question Buttons Grid --}}
                        <div class="grid grid-cols-5 gap-1.5">
                            @foreach($questions as $index => $q)
                                @php
                                    $status = $navigatorStatus[$index] ?? 'not_visited';
                                    $colorClass = match($status) {
                                        'current'          => 'bg-blue-500 text-white ring-2 ring-blue-300',
                                        'answered'         => 'bg-green-400 text-white hover:bg-green-500',
                                        'review'           => 'bg-yellow-400 text-white hover:bg-yellow-500',
                                        'review_answered'  => 'bg-yellow-400 text-white hover:bg-yellow-500 ring-2 ring-green-400',
                                        default            => 'bg-gray-100 text-gray-600 hover:bg-gray-200',
                                    };
                                @endphp
                                <button wire:click="goToQuestion({{ $index }})"
                                        class="w-full aspect-square rounded text-xs font-bold transition-all {{ $colorClass }}">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Submit Section --}}
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="text-xs text-gray-500 space-y-1 mb-3">
                                <div class="flex justify-between">
                                    <span>Answered:</span>
                                    <span class="font-medium text-green-600">{{ $answeredCount }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Not answered:</span>
                                    <span class="font-medium text-gray-700">{{ count($questions) - $answeredCount }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>For review:</span>
                                    <span class="font-medium text-yellow-600">{{ $reviewCount }}</span>
                                </div>
                            </div>
                            <button wire:click="confirmSubmit"
                                    class="w-full py-2.5 text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-colors">
                                Submit Exam
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Confirm Submit Modal --}}
        @if($showConfirmSubmit)
            <div class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center">
                    <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Submit Exam?</h3>
                    <p class="text-sm text-gray-500 mt-2">
                        You have answered {{ $answeredCount }} of {{ count($questions) }} questions.
                        {{ count($questions) - $answeredCount }} questions are unanswered.
                        This action cannot be undone.
                    </p>
                    <div class="flex space-x-3 mt-6">
                        <button wire:click="cancelSubmit"
                                class="flex-1 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl">
                            Review More
                        </button>
                        <button wire:click="submitExam"
                                class="flex-1 py-2.5 text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 rounded-xl">
                            <span wire:loading.remove wire:target="submitExam">Yes, Submit</span>
                            <span wire:loading wire:target="submitExam">Submitting...</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
