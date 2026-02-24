<div>
    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Search exams..."
                       class="w-full border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent">
            </div>
            <div>
                <select wire:model.live="statusFilter"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent bg-white">
                    <option value="">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div>
                <select wire:model.live="batchFilter"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#1e3a5f] focus:border-transparent bg-white">
                    <option value="">All Batches</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch['id'] }}">{{ $batch['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Exams Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Exams ({{ $exams->total() }})</h2>
        </div>

        @if($exams->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">No exams found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">Exam</th>
                            <th class="text-left px-6 py-3">Batch</th>
                            <th class="text-left px-6 py-3">Date</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-center px-6 py-3">Attempts</th>
                            <th class="text-center px-6 py-3">Avg Score</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($exams as $exam)
                            @php
                                $statusMap = [
                                    'draft'     => 'bg-gray-100 text-gray-600',
                                    'published' => 'bg-blue-100 text-blue-700',
                                    'ongoing'   => 'bg-yellow-100 text-yellow-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                ];
                                $sc = $statusMap[$exam->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-900">{{ $exam->title }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $exam->duration_minutes ?? '—' }} mins</p>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $exam->batch?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y') }}<br>
                                    <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $sc }}">
                                        {{ ucfirst($exam->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-gray-900">{{ $exam->completed_count }}</span>
                                    <span class="text-gray-400">/{{ $exam->attempt_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($exam->avg_score !== null)
                                        <span class="font-bold
                                            {{ $exam->avg_score >= 75 ? 'text-green-600' : ($exam->avg_score >= 50 ? 'text-yellow-500' : 'text-red-500') }}">
                                            {{ round($exam->avg_score) }}%
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($exam->status === 'completed')
                                        <a href="{{ route('admin.exams.rank-list', $exam->id) }}"
                                           class="text-xs font-medium text-[#1e3a5f] hover:text-orange-500 transition-colors">
                                            Rank List &rarr;
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($exams->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $exams->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
