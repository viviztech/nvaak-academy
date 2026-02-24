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
            <h2 class="text-2xl font-bold text-gray-800">Exams</h2>
            <p class="text-sm text-gray-500 mt-1">Manage all exams and test series</p>
        </div>
        <a href="{{ route('admin.exams.create') }}"
           class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Exam
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        @foreach([
            ['label' => 'Total', 'key' => 'total', 'color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['label' => 'Draft', 'key' => 'draft', 'color' => 'gray', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
            ['label' => 'Published', 'key' => 'published', 'color' => 'indigo', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
            ['label' => 'Live', 'key' => 'live', 'color' => 'green', 'icon' => 'M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728M12 21a9 9 0 100-18 9 9 0 000 18z'],
            ['label' => 'Completed', 'key' => 'completed', 'color' => 'red', 'icon' => 'M5 13l4 4L19 7'],
        ] as $stat)
            <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center space-x-3">
                <div class="w-9 h-9 bg-{{ $stat['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
                    <p class="text-xl font-bold text-gray-800">{{ $stats[$stat['key']] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="md:col-span-2">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input wire:model.live.debounce.400ms="search" type="text" placeholder="Search by name or code..."
                           class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>

            <select wire:model.live="statusFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                <option value="">All Statuses</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="live">Live</option>
                <option value="completed">Completed</option>
            </select>

            <select wire:model.live="typeFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                <option value="">All Types</option>
                <option value="mock_test">Mock Test</option>
                <option value="chapter_test">Chapter Test</option>
                <option value="full_length">Full Length</option>
                <option value="practice">Practice</option>
                <option value="sectional">Sectional</option>
            </select>

            <select wire:model.live="batchFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                <option value="">All Batches</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Exams Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Exam</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Batch</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Questions</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Marks</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Start Time</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($exams as $exam)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800">{{ $exam->name }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $exam->code }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-gray-600">{{ ucwords(str_replace('_', ' ', $exam->exam_type)) }}</span>
                                <span class="ml-1 px-1.5 py-0.5 rounded text-xs font-medium {{ $exam->course_type === 'neet' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ strtoupper($exam->course_type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $exam->batch?->name ?? 'All Batches' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-gray-800">{{ $exam->questions_count }}</span>
                                <span class="text-gray-400 text-xs"> questions</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700 text-sm font-medium">
                                {{ $exam->total_marks ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $exam->status_color }}">
                                    {{ ucfirst($exam->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $exam->start_time ? $exam->start_time->format('d M Y, h:i A') : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('admin.exams.edit', $exam->id) }}"
                                       class="px-2 py-1 text-xs text-blue-600 hover:bg-blue-50 rounded font-medium">Edit</a>

                                    @if($exam->status === 'draft')
                                        <button wire:click="publishExam({{ $exam->id }})"
                                                class="px-2 py-1 text-xs text-green-600 hover:bg-green-50 rounded font-medium">Publish</button>
                                    @elseif($exam->status === 'published')
                                        <button wire:click="goLive({{ $exam->id }})"
                                                class="px-2 py-1 text-xs text-orange-600 hover:bg-orange-50 rounded font-medium">Go Live</button>
                                        <button wire:click="unpublishExam({{ $exam->id }})"
                                                class="px-2 py-1 text-xs text-gray-600 hover:bg-gray-50 rounded font-medium">Unpublish</button>
                                    @elseif($exam->status === 'live')
                                        <button wire:click="markCompleted({{ $exam->id }})"
                                                class="px-2 py-1 text-xs text-red-600 hover:bg-red-50 rounded font-medium">End</button>
                                    @endif

                                    @if($exam->status === 'completed')
                                        <a href="{{ route('admin.exams.rank-list', $exam->id) }}"
                                           class="px-2 py-1 text-xs text-indigo-600 hover:bg-indigo-50 rounded font-medium">Ranks</a>
                                        <a href="{{ route('admin.exams.analytics', $exam->id) }}"
                                           class="px-2 py-1 text-xs text-purple-600 hover:bg-purple-50 rounded font-medium">Analytics</a>
                                    @endif

                                    <button wire:click="duplicateExam({{ $exam->id }})"
                                            class="px-2 py-1 text-xs text-gray-600 hover:bg-gray-50 rounded font-medium">Duplicate</button>

                                    <button wire:click="deleteExam({{ $exam->id }})"
                                            wire:confirm="Are you sure you want to delete this exam? This cannot be undone."
                                            class="px-2 py-1 text-xs text-red-600 hover:bg-red-50 rounded font-medium">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="font-medium">No exams found</p>
                                <p class="text-sm mt-1">Create your first exam to get started</p>
                                <a href="{{ route('admin.exams.create') }}"
                                   class="inline-flex items-center mt-4 px-4 py-2 bg-orange-500 text-white text-sm rounded-lg hover:bg-orange-600">
                                    Create Exam
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($exams->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $exams->links() }}
            </div>
        @endif
    </div>
</div>
