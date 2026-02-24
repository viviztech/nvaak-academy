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
            <button @click="show = false" class="text-green-400 hover:text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Question Bank</h2>
            <p class="text-sm text-gray-500 mt-1">Manage all exam questions</p>
        </div>
        <button wire:click="openCreateModal"
                class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Question
        </button>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center space-x-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Questions</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center space-x-3">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Verified</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['verified'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center space-x-3">
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">MCQ</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $stats['mcq'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center space-x-3">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500">Numerical</p>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['numerical'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div class="md:col-span-2">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input wire:model.live.debounce.400ms="search" type="text" placeholder="Search questions..."
                           class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>

            <select wire:model.live="bankFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                <option value="">All Banks</option>
                @foreach($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="subjectFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                <option value="">All Subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="typeFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                <option value="">All Types</option>
                <option value="mcq_single">MCQ Single</option>
                <option value="mcq_multiple">MCQ Multiple</option>
                <option value="numerical">Numerical</option>
                <option value="match_following">Match Following</option>
                <option value="assertion_reason">Assertion-Reason</option>
                <option value="true_false">True/False</option>
            </select>

            <select wire:model.live="difficultyFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                <option value="">All Levels</option>
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
                <option value="very_hard">Very Hard</option>
            </select>
        </div>

        @if($chapters->count())
            <div class="mt-3">
                <select wire:model.live="chapterFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                    <option value="">All Chapters</option>
                    @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    {{-- Questions Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Question</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Difficulty</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Marks</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($questions as $question)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-gray-500 font-mono text-xs">#{{ $question->id }}</td>
                            <td class="px-4 py-3 max-w-xs">
                                <p class="text-gray-800 font-medium truncate" title="{{ $question->question_text }}">
                                    {{ Str::limit(strip_tags($question->question_text), 70) }}
                                </p>
                                @if($question->chapter)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $question->chapter->name }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $question->type_color }}">
                                    {{ $question->type_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $question->difficulty_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $question->difficulty)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $question->subject?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-gray-800 font-medium">{{ $question->marks }}</span>
                                @if($question->negative_marks > 0)
                                    <span class="text-red-500 text-xs ml-1">(-{{ $question->negative_marks }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($question->is_verified)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        Unverified
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="openEditModal({{ $question->id }})"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</button>
                                    <button wire:click="toggleVerified({{ $question->id }})"
                                            class="text-{{ $question->is_verified ? 'yellow' : 'green' }}-600 hover:text-{{ $question->is_verified ? 'yellow' : 'green' }}-800 text-xs font-medium">
                                        {{ $question->is_verified ? 'Unverify' : 'Verify' }}
                                    </button>
                                    <button wire:click="deleteQuestion({{ $question->id }})"
                                            wire:confirm="Are you sure you want to delete this question?"
                                            class="text-red-600 hover:text-red-800 text-xs font-medium">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="font-medium">No questions found</p>
                                <p class="text-sm mt-1">Try adjusting your filters or add a new question</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($questions->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $questions->links() }}
            </div>
        @endif
    </div>

    {{-- Question Form Modal --}}
    @if($showFormModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center pt-10 overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 mb-10">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">
                        {{ $editingQuestionId ? 'Edit Question' : 'Add New Question' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    @livewire('admin.questions.question-form', ['questionId' => $editingQuestionId], key('question-form-' . ($editingQuestionId ?? 'new')))
                </div>
            </div>
        </div>
    @endif
</div>
