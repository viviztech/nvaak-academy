<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">IAS Answer Submissions</h2>
            <p class="text-sm text-gray-500 mt-0.5">Evaluate student answer scripts for IAS preparation</p>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="flex gap-2 mb-5 bg-white rounded-xl border border-gray-100 shadow-sm p-2">
        @foreach(['submitted' => 'Pending', 'under_evaluation' => 'Under Evaluation', 'evaluated' => 'Evaluated', 'returned' => 'Returned'] as $status => $label)
            <button wire:click="$set('statusFilter', '{{ $status }}')"
                    class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ $statusFilter === $status ? 'text-white' : 'text-gray-600 hover:bg-gray-100' }}"
                    @style(["background-color: #f97316" => $statusFilter === $status])>
                {{ $label }}
                <span class="px-1.5 py-0.5 rounded-full text-xs
                    {{ $statusFilter === $status ? 'bg-white/30 text-white' : 'bg-gray-100 text-gray-500' }}">
                    {{ $this->statusCounts[$status] ?? 0 }}
                </span>
            </button>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="flex gap-3 mb-5">
        <select wire:model.live="batchFilter" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none">
            <option value="">All Batches</option>
            @foreach($this->batches as $batch)
                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="subjectFilter" class="px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none">
            <option value="">All Subjects</option>
            @foreach($this->subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <th class="text-left px-5 py-3">Student</th>
                    <th class="text-left px-5 py-3">Subject</th>
                    <th class="text-left px-5 py-3">Type</th>
                    <th class="text-center px-5 py-3">Word Count</th>
                    <th class="text-left px-5 py-3">Submitted</th>
                    <th class="text-center px-5 py-3">Status</th>
                    <th class="text-center px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($this->submissions as $submission)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3">
                            <p class="text-sm font-medium text-gray-800">{{ $submission->student?->user?->name }}</p>
                            <p class="text-xs text-gray-400">{{ $submission->batch?->name }}</p>
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $submission->subject?->name }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 capitalize">
                                {{ str_replace('_', ' ', $submission->submission_type) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center text-sm text-gray-600">
                            {{ $submission->word_count ? number_format($submission->word_count) . ' words' : '-' }}
                        </td>
                        <td class="px-5 py-3 text-sm text-gray-600">
                            {{ $submission->submitted_at?->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-5 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                @if($submission->status === 'submitted') bg-yellow-100 text-yellow-700
                                @elseif($submission->status === 'under_evaluation') bg-blue-100 text-blue-700
                                @elseif($submission->status === 'evaluated') bg-green-100 text-green-700
                                @else bg-indigo-100 text-indigo-700 @endif capitalize">
                                {{ str_replace('_', ' ', $submission->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if($submission->status === 'submitted')
                                    <button wire:click="markUnderEvaluation({{ $submission->id }})"
                                            class="text-xs px-2 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                                        Start Evaluation
                                    </button>
                                @endif
                                <a href="{{ route('admin.ias.evaluate', $submission->id) }}"
                                   class="text-xs px-2 py-1 text-white rounded-lg transition-colors"
                                   style="background-color: #f97316;">
                                    {{ $submission->status === 'evaluated' || $submission->status === 'returned' ? 'View' : 'Evaluate' }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-sm text-gray-400">
                            No submissions with status "{{ str_replace('_', ' ', $statusFilter) }}" found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($this->submissions->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $this->submissions->links() }}
            </div>
        @endif
    </div>
</div>
