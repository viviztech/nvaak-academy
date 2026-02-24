<div>
    <form wire:submit="save" class="space-y-6">

        {{-- Classification Row --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Question Bank</label>
                <select wire:model.live="question_bank_id" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                    <option value="">No Bank</option>
                    @foreach($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Subject <span class="text-red-500">*</span></label>
                <select wire:model.live="subject_id" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                @error('subject_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Chapter</label>
                <select wire:model.live="chapter_id" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500" @disabled(!$subject_id)>
                    <option value="">Select Chapter</option>
                    @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Topic</label>
                <select wire:model="topic_id" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500" @disabled(!$chapter_id)>
                    <option value="">Select Topic</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Question Type --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-2">Question Type <span class="text-red-500">*</span></label>
            <div class="flex flex-wrap gap-2">
                @foreach(['mcq_single' => 'MCQ Single', 'mcq_multiple' => 'MCQ Multiple', 'numerical' => 'Numerical', 'match_following' => 'Match Following', 'assertion_reason' => 'Assertion-Reason', 'true_false' => 'True/False'] as $type => $label)
                    <button type="button" wire:click="$set('question_type', '{{ $type }}')"
                            class="px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors
                                   {{ $question_type === $type ? 'bg-[#1e3a5f] text-white border-[#1e3a5f]' : 'bg-white text-gray-600 border-gray-300 hover:border-gray-400' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Question Text --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Question Text <span class="text-red-500">*</span></label>
            <textarea wire:model="question_text" rows="4"
                      class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                      placeholder="Enter the question text..."></textarea>
            @error('question_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- MCQ Options --}}
        @if(in_array($question_type, ['mcq_single', 'mcq_multiple']))
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-3">
                    Answer Options
                    @if($question_type === 'mcq_single')
                        <span class="text-gray-400 font-normal">(select one correct answer)</span>
                    @else
                        <span class="text-gray-400 font-normal">(select all correct answers)</span>
                    @endif
                </label>
                <div class="space-y-3">
                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                        <div class="flex items-center space-x-3">
                            @if($question_type === 'mcq_single')
                                <input type="radio" wire:model="correct_answer.0" value="{{ $opt }}"
                                       id="radio_{{ $opt }}"
                                       class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                            @else
                                <input type="checkbox" wire:click="toggleMcqMultipleAnswer('{{ $opt }}')"
                                       @checked(in_array($opt, $correct_answer))
                                       id="check_{{ $opt }}"
                                       class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
                            @endif
                            <label for="{{ $question_type === 'mcq_single' ? 'radio' : 'check' }}_{{ $opt }}"
                                   class="w-7 h-7 flex items-center justify-center rounded-full font-bold text-sm
                                          {{ in_array($opt, $correct_answer) ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600' }}">
                                {{ $opt }}
                            </label>
                            <input wire:model="options.{{ $opt }}" type="text"
                                   class="flex-1 border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                                   placeholder="Option {{ $opt }}...">
                        </div>
                        @error("options.{{ $opt }}") <p class="text-red-500 text-xs mt-1 ml-14">{{ $message }}</p> @enderror
                    @endforeach
                </div>
                @error('correct_answer') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>
        @endif

        {{-- Numerical Range --}}
        @if($question_type === 'numerical')
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-3">Answer Range</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">From (minimum)</label>
                        <input wire:model="answer_range_from" type="number" step="any"
                               class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                               placeholder="0.00">
                        @error('answer_range_from') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">To (maximum)</label>
                        <input wire:model="answer_range_to" type="number" step="any"
                               class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                               placeholder="100.00">
                        @error('answer_range_to') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        @endif

        {{-- True/False --}}
        @if($question_type === 'true_false')
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-3">Correct Answer</label>
                <div class="flex space-x-4">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" wire:model="tf_correct" value="true" class="w-4 h-4 text-green-500">
                        <span class="text-sm font-medium text-gray-700">True</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" wire:model="tf_correct" value="false" class="w-4 h-4 text-red-500">
                        <span class="text-sm font-medium text-gray-700">False</span>
                    </label>
                </div>
                @error('tf_correct') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        @endif

        {{-- Assertion-Reason --}}
        @if($question_type === 'assertion_reason')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Assertion (A)</label>
                    <textarea wire:model="assertion" rows="2"
                              class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                              placeholder="Enter Assertion..."></textarea>
                    @error('assertion') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Reason (R)</label>
                    <textarea wire:model="reason" rows="2"
                              class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                              placeholder="Enter Reason..."></textarea>
                    @error('reason') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs font-semibold text-gray-600 mb-3">Correct Option:</p>
                    <div class="space-y-2">
                        @foreach([
                            'A' => 'Both Assertion and Reason are true and Reason is the correct explanation',
                            'B' => 'Both Assertion and Reason are true but Reason is NOT the correct explanation',
                            'C' => 'Assertion is true but Reason is false',
                            'D' => 'Assertion is false but Reason is true',
                        ] as $opt => $desc)
                            <label class="flex items-start space-x-3 cursor-pointer">
                                <input type="radio" wire:model="ar_correct_option" value="{{ $opt }}"
                                       class="mt-0.5 w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                <span class="text-sm text-gray-700"><strong>{{ $opt }}.</strong> {{ $desc }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('ar_correct_option') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        @endif

        {{-- Match Following --}}
        @if($question_type === 'match_following')
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-3">Match the Following</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-2 font-medium">Column A (Left)</p>
                        @foreach([0, 1, 2, 3] as $i)
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="w-6 text-center text-xs font-bold text-gray-500">{{ chr(65 + $i) }}.</span>
                                <input wire:model="match_left.{{ $i }}" type="text"
                                       class="flex-1 border border-gray-300 rounded-lg text-sm py-1.5 px-3 focus:ring-2 focus:ring-orange-500"
                                       placeholder="Item {{ chr(65 + $i) }}">
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-2 font-medium">Column B (Right)</p>
                        @foreach([0, 1, 2, 3] as $i)
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="w-6 text-center text-xs font-bold text-gray-500">{{ $i + 1 }}.</span>
                                <input wire:model="match_right.{{ $i }}" type="text"
                                       class="flex-1 border border-gray-300 rounded-lg text-sm py-1.5 px-3 focus:ring-2 focus:ring-orange-500"
                                       placeholder="Match {{ $i + 1 }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-3 bg-blue-50 rounded-lg p-3">
                    <p class="text-xs font-medium text-blue-700 mb-2">Correct Matches (A→?, B→?, C→?, D→?):</p>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(['A', 'B', 'C', 'D'] as $key)
                            <div>
                                <label class="text-xs text-gray-600">{{ $key }} matches:</label>
                                <select wire:model="match_correct.{{ $key }}"
                                        class="w-full mt-1 border border-gray-300 rounded text-xs py-1 px-2">
                                    <option value="">-</option>
                                    @foreach([1, 2, 3, 4] as $n)
                                        <option value="{{ $n }}">{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Marks, Difficulty, Meta --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Marks</label>
                <input wire:model="marks" type="number" step="0.5" min="0"
                       class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Negative Marks</label>
                <input wire:model="negative_marks" type="number" step="0.25" min="0"
                       class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Difficulty</label>
                <select wire:model="difficulty" class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                    <option value="very_hard">Very Hard</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Year Asked</label>
                <input wire:model="year_asked" type="number" min="1990" max="{{ now()->year }}"
                       class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                       placeholder="e.g. 2023">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Source</label>
                <input wire:model="source" type="text"
                       class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                       placeholder="e.g. NEET 2023, AIIMS Mock">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tags (comma separated)</label>
                <input wire:model="tags_input" type="text"
                       class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                       placeholder="e.g. genetics, cell division, NEET">
            </div>
        </div>

        {{-- Explanation --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Explanation / Solution</label>
            <textarea wire:model="explanation" rows="3"
                      class="w-full border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500"
                      placeholder="Enter the explanation for the correct answer..."></textarea>
        </div>

        {{-- Preview --}}
        @if($question_text)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-blue-700 mb-2">Preview</p>
                <p class="text-sm text-gray-800 font-medium">{{ $question_text }}</p>
                @if(in_array($question_type, ['mcq_single', 'mcq_multiple']))
                    <div class="mt-3 grid grid-cols-2 gap-2">
                        @foreach(['A', 'B', 'C', 'D'] as $opt)
                            @if($options[$opt])
                                <div class="flex items-start space-x-2 p-2 rounded-lg {{ in_array($opt, $correct_answer) ? 'bg-green-100 border border-green-300' : 'bg-white border border-gray-200' }}">
                                    <span class="font-bold text-xs {{ in_array($opt, $correct_answer) ? 'text-green-700' : 'text-gray-500' }}">{{ $opt }}.</span>
                                    <span class="text-xs text-gray-700">{{ $options[$opt] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
                @if($question_type === 'numerical' && $answer_range_from !== '' && $answer_range_to !== '')
                    <p class="mt-2 text-xs text-blue-700">Answer range: {{ $answer_range_from }} to {{ $answer_range_to }}</p>
                @endif
                @if($explanation)
                    <div class="mt-3 pt-3 border-t border-blue-200">
                        <p class="text-xs font-medium text-blue-700">Explanation:</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $explanation }}</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Form Actions --}}
        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
            <button type="button" wire:click="cancel"
                    class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button type="submit"
                    class="px-6 py-2 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition-colors">
                <span wire:loading.remove wire:target="save">{{ $questionId ? 'Update Question' : 'Save Question' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </form>
</div>
