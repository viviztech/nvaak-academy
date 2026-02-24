<div>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Rank List</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $exam->name }} &bull; {{ $exam->code }}</p>
        </div>
        <div class="flex space-x-3">
            <button wire:click="generateRanks"
                    class="px-4 py-2 text-sm font-medium text-white bg-[#1e3a5f] hover:bg-blue-900 rounded-lg">
                Recalculate Ranks
            </button>
            <button wire:click="publishResults"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg">
                Publish Results
            </button>
            <a href="{{ route('admin.exams.index') }}"
               class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Back
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-xs text-gray-500">Total Appeared</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-xs text-gray-500">Passed</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['pass'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-xs text-gray-500">Failed</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ $stats['fail'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-xs text-gray-500">Avg Marks</p>
            <p class="text-2xl font-bold text-[#1e3a5f] mt-1">{{ round($stats['avg_marks'] ?? 0, 1) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-xs text-gray-500">Highest Marks</p>
            <p class="text-2xl font-bold text-orange-500 mt-1">{{ $stats['max_marks'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <select wire:model.live="batchFilter" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                <option value="">All Batches</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="passFail" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-orange-500">
                <option value="">All Results</option>
                <option value="pass">Pass Only</option>
                <option value="fail">Fail Only</option>
            </select>
        </div>
    </div>

    {{-- Rank Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Student</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Batch</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Marks</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Percentage</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Correct</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Wrong</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Skipped</th>
                        @foreach($subjects as $subject)
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">{{ Str::limit($subject->name, 10) }}</th>
                        @endforeach
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($results as $result)
                        <tr class="hover:bg-gray-50 transition-colors {{ $result->rank_overall <= 3 ? 'bg-yellow-50' : '' }}">
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    @if($result->rank_overall === 1)
                                        <span class="text-2xl">🥇</span>
                                    @elseif($result->rank_overall === 2)
                                        <span class="text-2xl">🥈</span>
                                    @elseif($result->rank_overall === 3)
                                        <span class="text-2xl">🥉</span>
                                    @else
                                        <span class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-700 font-bold text-sm">
                                            {{ $result->rank_overall ?? '-' }}
                                        </span>
                                    @endif
                                    @if($result->rank_in_batch && $result->rank_in_batch !== $result->rank_overall)
                                        <span class="text-xs text-gray-400">(#{{ $result->rank_in_batch }} in batch)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800">{{ $result->student?->user?->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400">{{ $result->student?->student_code }}</p>
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $result->batch?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="font-bold text-gray-800">{{ $result->marks_obtained }}</span>
                                <span class="text-gray-400 text-xs">/ {{ $result->total_marks }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-800">{{ $result->percentage }}%</span>
                                    <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full {{ $result->percentage >= 60 ? 'bg-green-500' : 'bg-red-400' }}"
                                             style="width: {{ min($result->percentage, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-green-600 font-medium">{{ $result->correct_answers }}</td>
                            <td class="px-4 py-3 text-red-500 font-medium">{{ $result->wrong_answers }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $result->unattempted }}</td>
                            @foreach($subjects as $sid => $subject)
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    {{ $result->subject_wise_scores[$sid]['obtained'] ?? '-' }}
                                </td>
                            @endforeach
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $result->pass_fail_color }}">
                                    {{ ucfirst($result->pass_fail) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-12 text-center text-gray-500">
                                <p class="font-medium">No results yet</p>
                                <p class="text-sm mt-1">Results will appear after students submit the exam</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($results->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $results->links() }}
            </div>
        @endif
    </div>
</div>
